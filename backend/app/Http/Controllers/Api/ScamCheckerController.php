<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScamCheckRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SafePulse Scam & Risk Checker
 *
 * Two-layer detection:
 *   Layer 1 — Rule-based engine (keyword, regex, pattern matching)
 *   Layer 2 — Mistral AI (mistral-small-latest, Paris 🇫🇷)
 *             Context-aware, multilingual scam analysis
 *
 * Framework alignment:
 *   • ANSSI SecNumCloud: privacy-by-design, no data retention
 *   • INTERPOL I-GRIP:   threat taxonomy for reporting
 */
class ScamCheckerController extends Controller
{
    // ── Rule-based detection lists ─────────────────────────────────────────

    private const HIGH_RISK_KEYWORDS = [
        'send money urgently','wire transfer now','bitcoin investment guaranteed',
        'you have won','claim your prize','verify your bank account',
        'urgent action required','limited time offer','act now or lose',
        'secret investment','double your money','risk free profit',
        'gift card payment','western union','moneygram',
        'your account will be suspended','click here to verify',
        'nigerian prince','inheritance funds','lottery winner',
        'transfer fee required','customs clearance fee','release your funds',
    ];

    private const MEDIUM_RISK_KEYWORDS = [
        'investment opportunity','earn from home','passive income',
        'work from home','make money fast','get rich quick',
        'exclusive deal','limited slots','join now',
        'referral bonus','recruit others','downline',
        'unsubscribe here','update your information',
        'congratulations you have been selected',
    ];

    private const SUSPICIOUS_DOMAINS = [
        'paypa1.com','paypai.com','go0gle.com','arnazon.com',
        'faceb00k.com','netfliix.com','steamcommunlty.com',
        'micros0ft.com','app1e.com','lnstagram.com',
    ];

    private const SUSPICIOUS_TLDS   = ['.xyz','.top','.click','.gq','.tk','.cf','.ml'];
    private const URL_SHORTENERS    = ['bit.ly','tinyurl.com','t.co','goo.gl','ow.ly','rb.gy'];

    private const HIGH_RISK_PHONE_PREFIXES = [
        '+44 70','+44 71','+1 900','+268','+269',
    ];

    // ── Entry point ────────────────────────────────────────────────────────

    public function check(ScamCheckRequest $request): JsonResponse
    {
        $reasons = [];
        $score   = 0;
        $sources = [];   // which layers contributed

        // ── Layer 1: Rule-based ──────────────────────────────────────────
        if ($text = $request->input('message_text')) {
            [$s, $r] = $this->analyseText($text);
            $score  += $s;
            $reasons = array_merge($reasons, $r);
        }
        if ($url = $request->input('url')) {
            [$s, $r] = $this->analyseUrl($url);
            $score  += $s;
            $reasons = array_merge($reasons, $r);
        }
        if ($phone = $request->input('phone_number')) {
            [$s, $r] = $this->analysePhone($phone);
            $score  += $s;
            $reasons = array_merge($reasons, $r);
        }
        if ($account = $request->input('bank_account')) {
            [$s, $r] = $this->analyseAccount($account);
            $score  += $s;
            $reasons = array_merge($reasons, $r);
        }

        if ($score > 0 || !empty($reasons)) {
            $sources[] = 'rule-engine';
        }

        // ── Layer 2: Mistral AI (🇫🇷 Paris) ─────────────────────────────
        $mistralResult = $this->callMistralAI($request->only([
            'message_text','url','phone_number','bank_account',
        ]));

        if ($mistralResult !== null) {
            $score   += $mistralResult['risk_boost'];
            $reasons  = array_merge($reasons, $mistralResult['findings']);
            $sources[] = 'mistral-ai';
        }

        // ── Finalize ─────────────────────────────────────────────────────
        $score = min(100, $score);

        $level = match(true) {
            $score >= 65 => 'High',
            $score >= 35 => 'Medium',
            default      => 'Low',
        };

        if (empty($reasons)) {
            $reasons[] = 'No obvious red flags detected — always stay cautious.';
        }

        return response()->json([
            'score'        => $score,
            'level'        => $level,
            'reasons'      => array_values(array_unique($reasons)),
            // Metadata shown to user — confirms Mistral AI was used
            'analysis_by'  => $sources,
            'powered_by'   => in_array('mistral-ai', $sources)
                ? 'SafePulse Rule Engine + Mistral AI (🇫🇷 Paris)'
                : 'SafePulse Rule Engine',
            // ANSSI/privacy notice: no content is stored
            'privacy_note' => 'No submitted content is stored or logged. Analysis is ephemeral.',
        ]);
    }

    // ── Mistral AI — Layer 2 ───────────────────────────────────────────────

    private function callMistralAI(array $inputs): ?array
    {
        $apiKey = config('services.mistral.key', env('MISTRAL_API_KEY'));

        if (blank($apiKey)) {
            // Mistral key not configured — gracefully skip Layer 2
            return null;
        }

        // Build a single analysis string from all non-empty inputs
        $content = collect([
            'message' => $inputs['message_text'] ?? null,
            'url'     => $inputs['url']          ?? null,
            'phone'   => $inputs['phone_number'] ?? null,
            'account' => $inputs['bank_account'] ?? null,
        ])->filter()->map(fn($v, $k) => strtoupper($k).': '.$v)->implode("\n");

        if (blank($content)) {
            return null;
        }

        $prompt = <<<PROMPT
You are a digital fraud detection expert specialising in Southeast Asian scam patterns.
Analyse the following content for contextual deception — things rule-based engines miss:
- Deceptive reassurance ("this is 100% safe, trust me")
- Emotional manipulation (love bombing, urgency, fear)
- Social engineering tactics (authority impersonation, exclusivity)
- Pig-butchering or romance-scam conversational patterns
- Money-mule recruitment language
- Radicalization or extremist recruitment signals

Content to analyse:
{$content}

Respond ONLY with valid JSON (no markdown, no explanation):
{"risk_boost": <integer 0-30>, "findings": ["finding1", "finding2"]}

Rules:
- risk_boost: 0 if no contextual red flags, up to 30 for clear deceptive context
- findings: 1-3 specific contextual findings in plain language (empty array if none)
- Each finding must start with "🤖 Mistral:"
PROMPT;

        try {
            $response = Http::withToken($apiKey)
                ->timeout(8)
                ->post('https://api.mistral.ai/v1/chat/completions', [
                    'model'           => 'mistral-small-latest',
                    'messages'        => [['role' => 'user', 'content' => $prompt]],
                    'max_tokens'      => 200,
                    'temperature'     => 0.1,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->failed()) {
                Log::warning('Mistral API non-200', ['status' => $response->status()]);
                return null;
            }

            $body    = $response->json();
            $raw     = $body['choices'][0]['message']['content'] ?? '{}';
            $decoded = json_decode($raw, true);

            if (!is_array($decoded)) {
                return null;
            }

            return [
                'risk_boost' => min(30, max(0, (int)($decoded['risk_boost'] ?? 0))),
                'findings'   => array_slice((array)($decoded['findings'] ?? []), 0, 3),
            ];

        } catch (\Throwable $e) {
            Log::warning('Mistral AI call failed — falling back to rule engine only', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ── Text analysis ─────────────────────────────────────────────────────

    private function analyseText(string $text): array
    {
        $lower   = strtolower($text);
        $score   = 0;
        $reasons = [];

        foreach (self::HIGH_RISK_KEYWORDS as $kw) {
            if (str_contains($lower, $kw)) {
                $score   += 30;
                $reasons[] = "High-risk phrase detected: \"{$kw}\"";
            }
        }
        foreach (self::MEDIUM_RISK_KEYWORDS as $kw) {
            if (str_contains($lower, $kw)) {
                $score   += 15;
                $reasons[] = "Suspicious phrase: \"{$kw}\"";
            }
        }

        // Excessive capitalisation
        $capsRatio = preg_match_all('/[A-Z]/', $text) / max(strlen($text), 1);
        if ($capsRatio > 0.4 && strlen($text) > 30) {
            $score   += 10;
            $reasons[] = 'Excessive capitalisation — common in scam messages.';
        }

        // Currency + urgency
        if (preg_match('/\$[\d,]+/', $text) && preg_match('/urgent|immediately|right now/i', $text)) {
            $score   += 20;
            $reasons[] = 'Monetary amount combined with urgency language.';
        }

        return [$score, $reasons];
    }

    // ── URL analysis ──────────────────────────────────────────────────────

    private function analyseUrl(string $url): array
    {
        $score   = 0;
        $reasons = [];
        $lower   = strtolower($url);

        if (str_starts_with($lower, 'http://')) {
            $score += 15;
            $reasons[] = 'URL uses insecure HTTP instead of HTTPS.';
        }

        foreach (self::SUSPICIOUS_DOMAINS as $d) {
            if (str_contains($lower, $d)) {
                $score += 50;
                $reasons[] = "Domain matches known phishing pattern: {$d}";
            }
        }

        $host = parse_url($lower, PHP_URL_HOST) ?? '';
        foreach (self::SUSPICIOUS_TLDS as $tld) {
            if (str_ends_with($host, $tld)) {
                $score += 20;
                $reasons[] = "Top-level domain \"{$tld}\" frequently used in scam sites.";
            }
        }

        if (preg_match('/https?:\/\/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $lower)) {
            $score += 35;
            $reasons[] = 'URL uses a raw IP address instead of a domain name.';
        }

        if (substr_count($host, '.') >= 4) {
            $score += 15;
            $reasons[] = 'URL has an unusual number of sub-domain levels.';
        }

        foreach (self::URL_SHORTENERS as $sh) {
            if (str_contains($lower, $sh)) {
                $score += 10;
                $reasons[] = 'URL uses a shortener service — destination is hidden.';
            }
        }

        return [$score, $reasons];
    }

    // ── Phone analysis ────────────────────────────────────────────────────

    private function analysePhone(string $phone): array
    {
        $score   = 0;
        $reasons = [];
        $clean   = preg_replace('/\s/', ' ', $phone);

        foreach (self::HIGH_RISK_PHONE_PREFIXES as $prefix) {
            if (str_starts_with($clean, $prefix)) {
                $score += 40;
                $reasons[] = "Phone prefix \"{$prefix}\" associated with one-ring or premium-rate scams.";
            }
        }

        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) > 15 || strlen($digits) < 7) {
            $score += 20;
            $reasons[] = 'Phone number has an unusual length for a valid number.';
        }

        return [$score, $reasons];
    }

    // ── Account/wallet analysis ───────────────────────────────────────────

    private function analyseAccount(string $account): array
    {
        $score   = 0;
        $reasons = [];

        if (preg_match('/^(0x[a-fA-F0-9]{40}|[13][a-zA-Z0-9]{25,34}|bc1[a-z0-9]{39,59})$/', $account)) {
            $score += 25;
            $reasons[] = 'Input matches a cryptocurrency wallet address — verify recipient carefully.';
        }

        return [$score, $reasons];
    }
}
