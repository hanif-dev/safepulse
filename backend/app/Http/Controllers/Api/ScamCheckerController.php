<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScamCheckRequest;
use Illuminate\Http\JsonResponse;

class ScamCheckerController extends Controller
{
    // ── Suspicious keyword lists ───────────────────────────────────────────────
    private const HIGH_RISK_KEYWORDS = [
        'send money urgently', 'wire transfer now', 'bitcoin investment guaranteed',
        'you have won', 'claim your prize', 'verify your bank account',
        'urgent action required', 'limited time offer', 'act now or lose',
        'secret investment', 'double your money', 'risk free profit',
        'gift card payment', 'western union', 'moneygram',
        'your account will be suspended', 'click here to verify',
        'nigerian prince', 'inheritance funds', 'lottery winner',
    ];

    private const MEDIUM_RISK_KEYWORDS = [
        'investment opportunity', 'earn from home', 'passive income',
        'work from home', 'make money fast', 'get rich quick',
        'exclusive deal', 'limited slots', 'join now',
        'referral bonus', 'recruit others', 'downline',
        'unsubscribe here', 'update your information',
    ];

    private const SUSPICIOUS_DOMAINS = [
        // typosquatting common banks / payment platforms
        'paypa1.com', 'paypai.com', 'go0gle.com', 'arnazon.com',
        'faceb00k.com', 'netfliix.com', 'steamcommunlty.com',
        'micros0ft.com', 'app1e.com', 'lnstagram.com',
    ];

    private const SUSPICIOUS_TLDS = ['.xyz', '.top', '.click', '.gq', '.tk', '.cf', '.ml'];

    private const HIGH_RISK_PHONE_PREFIXES = [
        // Common scam call prefixes (illustrative)
        '+44 70', '+44 71', // UK premium rate spoofed
        '+1 900',            // US premium
        '+268',              // Swaziland one-ring
        '+269',              // Comoros one-ring
    ];

    // ── Entry point ──────────────────────────────────────────────────────────
    public function check(ScamCheckRequest $request): JsonResponse
    {
        $reasons = [];
        $score   = 0;

        if ($text = $request->input('message_text')) {
            [$s, $r] = $this->analyseText($text);
            $score += $s;
            $reasons = array_merge($reasons, $r);
        }

        if ($url = $request->input('url')) {
            [$s, $r] = $this->analyseUrl($url);
            $score += $s;
            $reasons = array_merge($reasons, $r);
        }

        if ($phone = $request->input('phone_number')) {
            [$s, $r] = $this->analysePhone($phone);
            $score += $s;
            $reasons = array_merge($reasons, $r);
        }

        if ($account = $request->input('bank_account')) {
            [$s, $r] = $this->analyseAccount($account);
            $score += $s;
            $reasons = array_merge($reasons, $r);
        }

        // Cap at 100
        $score = min(100, $score);

        $level = match(true) {
            $score >= 65 => 'High',
            $score >= 35 => 'Medium',
            default      => 'Low',
        };

        if (empty($reasons)) {
            $reasons[] = 'No obvious red flags detected – always stay cautious.';
        }

        return response()->json([
            'score'   => $score,
            'level'   => $level,
            'reasons' => array_values(array_unique($reasons)),
        ]);
    }

    // ── Text / message analysis ───────────────────────────────────────────────
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
            $reasons[] = 'Excessive capitalisation – common in scam messages.';
        }

        // Currency + urgency combo
        if (preg_match('/\$[\d,]+/', $text) && preg_match('/urgent|immediately|right now/i', $text)) {
            $score   += 20;
            $reasons[] = 'Monetary amount combined with urgency language.';
        }

        return [$score, $reasons];
    }

    // ── URL analysis ─────────────────────────────────────────────────────────
    private function analyseUrl(string $url): array
    {
        $score   = 0;
        $reasons = [];
        $lower   = strtolower($url);

        // No HTTPS
        if (str_starts_with($lower, 'http://')) {
            $score   += 15;
            $reasons[] = 'URL uses insecure HTTP instead of HTTPS.';
        }

        // Suspicious domain
        foreach (self::SUSPICIOUS_DOMAINS as $domain) {
            if (str_contains($lower, $domain)) {
                $score   += 50;
                $reasons[] = "Domain matches known phishing pattern: {$domain}";
            }
        }

        // Suspicious TLD
        foreach (self::SUSPICIOUS_TLDS as $tld) {
            if (str_ends_with(parse_url($lower, PHP_URL_HOST) ?? '', $tld)) {
                $score   += 20;
                $reasons[] = "Top-level domain \"{$tld}\" is frequently used in scam sites.";
            }
        }

        // IP address instead of domain name
        if (preg_match('/https?:\/\/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $lower)) {
            $score   += 35;
            $reasons[] = 'URL uses a raw IP address instead of a domain name.';
        }

        // Excessive sub-domains (e.g. paypal.secure.login.evilsite.com)
        $host = parse_url($url, PHP_URL_HOST) ?? '';
        if (substr_count($host, '.') >= 4) {
            $score   += 15;
            $reasons[] = 'URL has an unusual number of sub-domain levels.';
        }

        // URL shorteners
        $shorteners = ['bit.ly', 'tinyurl.com', 't.co', 'goo.gl', 'ow.ly', 'rb.gy'];
        foreach ($shorteners as $s) {
            if (str_contains($lower, $s)) {
                $score   += 10;
                $reasons[] = 'URL uses a shortener service – destination is hidden.';
            }
        }

        return [$score, $reasons];
    }

    // ── Phone number analysis ─────────────────────────────────────────────────
    private function analysePhone(string $phone): array
    {
        $score   = 0;
        $reasons = [];
        $clean   = preg_replace('/\s/', ' ', $phone);

        foreach (self::HIGH_RISK_PHONE_PREFIXES as $prefix) {
            if (str_starts_with($clean, $prefix)) {
                $score   += 40;
                $reasons[] = "Phone prefix \"{$prefix}\" is associated with one-ring or premium-rate scams.";
            }
        }

        // Unusually long number
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) > 15 || strlen($digits) < 7) {
            $score   += 20;
            $reasons[] = 'Phone number has an unusual length for a valid number.';
        }

        return [$score, $reasons];
    }

    // ── Bank / wallet account analysis ────────────────────────────────────────
    private function analyseAccount(string $account): array
    {
        $score   = 0;
        $reasons = [];

        // Crypto wallet patterns
        if (preg_match('/^(0x[a-fA-F0-9]{40}|[13][a-zA-Z0-9]{25,34}|bc1[a-z0-9]{39,59})$/', $account)) {
            $score   += 25;
            $reasons[] = 'Input matches a cryptocurrency wallet address – verify recipient carefully.';
        }

        return [$score, $reasons];
    }
}
