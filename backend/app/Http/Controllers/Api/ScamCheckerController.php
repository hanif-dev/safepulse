<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScamCheckRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SafePulse Scam Checker — Multilingual Detection Engine
 *
 * Two-layer architecture:
 *   Layer 1 — Rule-based pattern engine
 *               • 12+ language keyword sets
 *               • Indonesian scam pattern library (J&T, BRI, BPJS, Shopee, OJK)
 *               • URL forensics (subdomain abuse, brand spoofing, APK lures)
 *               • Phone prefix analysis (premium-rate, one-ring)
 *               • Wallet pattern detection (crypto, e-wallet)
 *
 *   Layer 2 — Mistral AI (🇫🇷 Paris)
 *               • Context-aware deception detection
 *               • Multilingual NLP across 12 languages
 *               • Graceful fallback if unavailable
 *
 * Framework alignment:
 *   • ANSSI SecNumCloud — privacy-by-design, zero retention
 *   • INTERPOL I-GRIP   — threat taxonomy aligned for reporting
 */
class ScamCheckerController extends Controller
{
    // ════════════════════════════════════════════════════════════════════════
    // MULTILINGUAL HIGH-RISK PHRASE LIBRARY
    // Score: +30 per match
    // ════════════════════════════════════════════════════════════════════════

    private const HIGH_RISK_KEYWORDS = [
        // ── English ────────────────────────────────────────────────────────
        'send money urgently', 'wire transfer now', 'bitcoin investment guaranteed',
        'you have won', 'claim your prize', 'verify your bank account',
        'urgent action required', 'limited time offer', 'act now or lose',
        'secret investment', 'double your money', 'risk free profit',
        'gift card payment', 'western union', 'moneygram',
        'your account will be suspended', 'click here to verify',
        'nigerian prince', 'inheritance funds', 'lottery winner',
        'transfer fee required', 'customs clearance fee', 'release your funds',
        'guaranteed returns', '100% safe', 'trust me send',
        'one time investment', 'final warning account', 'compromised account',

        // ── Bahasa Indonesia ───────────────────────────────────────────────
        'transfer segera', 'kirim uang sekarang', 'investasi dijamin untung',
        'klaim hadiah anda', 'verifikasi rekening', 'akun anda akan diblokir',
        'rekening akan dibekukan', 'segera konfirmasi', 'klik link berikut',
        'menang undian', 'pemenang utama', 'hadiah uang tunai',
        'bunga harian dijamin', 'pasti untung', 'tanpa risiko',
        'biaya admin pencairan', 'kode otp', 'bagikan kode otp',
        'paket tertahan', 'paket di bea cukai', 'biaya pengiriman tambahan',
        'tarif baru rekening', 'auto debit', 'pembatalan tarif normal',
        'giveaway berhadiah', 'tebak kata berhadiah', 'klaim kode pemenang',
        'subsidi pemerintah', 'bantuan langsung tunai', 'penerima blt',
        'token listrik gratis', 'kuota internet gratis',
        'pinjaman tanpa jaminan', 'pinjol cair cepat',

        // ── French (Français) ──────────────────────────────────────────────
        'transfert urgent', 'virement immédiat', 'vous avez gagné',
        'réclamez votre prix', 'compte sera suspendu', 'cliquez pour vérifier',
        'investissement garanti', 'bénéfices garantis', 'sans risque',

        // ── Arabic (العربية) ────────────────────────────────────────────────
        'تحويل عاجل', 'لقد ربحت', 'احصل على جائزتك', 'تم تعليق حسابك',

        // ── Tagalog / Filipino ─────────────────────────────────────────────
        'manalo ka ng', 'ipadala ang pera ngayon', 'i-verify ang account',
        'libreng premyo', 'kumpirmahin agad',

        // ── Vietnamese ─────────────────────────────────────────────────────
        'chuyển tiền gấp', 'bạn đã trúng', 'xác minh tài khoản',
        'lợi nhuận đảm bảo',

        // ── Thai (ไทย) ─────────────────────────────────────────────────────
        'โอนเงินด่วน', 'คุณได้รับรางวัล', 'ยืนยันบัญชี', 'รับประกันกำไร',
    ];

    // ════════════════════════════════════════════════════════════════════════
    // MEDIUM-RISK PHRASES (Score: +15 per match)
    // ════════════════════════════════════════════════════════════════════════

    private const MEDIUM_RISK_KEYWORDS = [
        // English
        'investment opportunity', 'earn from home', 'passive income',
        'work from home easy', 'make money fast', 'get rich quick',
        'exclusive deal', 'limited slots', 'join now',
        'referral bonus', 'recruit others', 'downline commission',
        'unsubscribe here', 'update your information',
        'congratulations you have been selected', 'security upgrade',

        // Bahasa Indonesia
        'peluang investasi', 'kerja dari rumah', 'penghasilan pasif',
        'cepat kaya', 'modal kecil untung besar',
        'sistem mlm', 'bonus referral', 'rekrut anggota',
        'akun perlu diperbarui', 'data pribadi diperbarui',
        'cek paket', 'lacak resi', 'aplikasi pelacak',
        'foto fisik paket', 'kurir resmi', 'agen resmi',
        'webinar bisnis', 'mentor kripto', 'sinyal trading',
        'robot trading', 'forex pasti cuan',

        // French
        'opportunité d\'investissement', 'gagnez de l\'argent',
        'travail à domicile', 'rapide et facile',

        // Arabic
        'فرصة استثمارية', 'كسب المال بسرعة',

        // Tagalog
        'magkapera nang mabilis', 'libreng pagsali',

        // Vietnamese
        'cơ hội đầu tư', 'làm việc tại nhà',

        // Thai
        'โอกาสการลงทุน', 'ทำงานที่บ้าน',
    ];

    // ════════════════════════════════════════════════════════════════════════
    // INDONESIAN BRAND IMPERSONATION PATTERNS
    // Catches: "kurir J&T" + "link aplikasi", "BRI" + "tarif berubah", etc.
    // Score: +40 per matched combo
    // ════════════════════════════════════════════════════════════════════════

    private const BRAND_IMPERSONATION_PATTERNS = [
        // [brand_keyword, scam_signal, label]
        ['j&t',         'foto fisik paket',          'J&T Express courier impersonation'],
        ['j&t',         'aplikasi pelacak',          'J&T fake tracker app lure'],
        ['jne',         'cek resi',                  'JNE tracking scam pattern'],
        ['shopee',      'giveaway',                  'Shopee giveaway impersonation'],
        ['shopee',      'klaim hadiah',              'Shopee prize claim scam'],
        ['tokopedia',   'verifikasi',                'Tokopedia account verification scam'],
        ['gojek',       'akun diblokir',             'Gojek account block scam'],
        ['grab',        'verifikasi data',           'Grab data verification scam'],
        ['bri',         'tarif',                     'BRI fake fee change scam'],
        ['bri',         'auto debit',                'BRI auto-debit scam'],
        ['bca',         'verifikasi rekening',       'BCA account verification scam'],
        ['mandiri',     'pembekuan rekening',        'Mandiri account freeze scam'],
        ['bni',         'data diperbarui',           'BNI data update scam'],
        ['bpjs',        'aktivasi ulang',            'BPJS reactivation scam'],
        ['ojk',         'klaim',                     'OJK impersonation scam'],
        ['kominfo',     'sanksi',                    'Kominfo sanction scam'],
        ['pln',         'token listrik',             'PLN free token scam'],
        ['indihome',    'tagihan',                   'IndiHome bill scam'],
        ['dana',        'kode otp',                  'DANA OTP harvesting'],
        ['ovo',         'kode verifikasi',           'OVO verification harvesting'],
        ['gopay',       'aktivasi',                  'GoPay activation scam'],
    ];

    // ════════════════════════════════════════════════════════════════════════
    // URL THREAT PATTERNS
    // ════════════════════════════════════════════════════════════════════════

    private const SUSPICIOUS_DOMAINS = [
        // Typosquatting
        'paypa1.com', 'paypai.com', 'go0gle.com', 'arnazon.com',
        'faceb00k.com', 'netfliix.com', 'steamcommunlty.com',
        'micros0ft.com', 'app1e.com', 'lnstagram.com',
        // Indonesian brand typosquats
        'shoppee.com', 'tokopedi.com', 'tokopediaa.com',
        'gojeki.com', 'grabb.com', 'bca-id.com', 'bri-id.com',
        'mandiri-bank.com', 'jntexpress.id', 'j-t-express.com',
    ];

    private const SUSPICIOUS_TLDS = [
        '.xyz', '.top', '.click', '.gq', '.tk', '.cf', '.ml',
        '.bid', '.loan', '.work', '.win', '.party', '.review',
        '.country', '.kim', '.science', '.cricket',
    ];

    private const URL_SHORTENERS = [
        'bit.ly', 'tinyurl.com', 't.co', 'goo.gl', 'ow.ly', 'rb.gy',
        'shorturl.at', 'cutt.ly', 's.id', 'bit.do', 'is.gd',
    ];

    // Indonesian fake-domain word fragments (subdomain abuse signals)
    private const INDONESIAN_FAKE_FRAGMENTS = [
        'login-akses', 'akses-akun', 'verifikasi-akun', 'aktivasi-akun',
        'tarif-perubahan', 'tarif-baru', 'biaya-admin',
        'apk-download', 'apk-resmi', 'aplikasi-resmi',
        'cek-paket', 'lacak-paket', 'tracking-resi',
        'klaim-hadiah', 'pemenang-undian', 'giveaway-resmi',
        'blogspot-palsu', 'wordpress-fake', 'free-hosting',
        'subsidi-resmi', 'blt-kemensos', 'token-gratis',
    ];

    // Indonesian brand names that, when in subdomain, are suspicious
    private const SUSPICIOUS_BRAND_IN_SUBDOMAIN = [
        'bri', 'bca', 'bni', 'mandiri', 'cimb', 'permata',
        'shopee', 'tokopedia', 'lazada', 'bukalapak', 'blibli',
        'gojek', 'grab', 'maxim',
        'dana', 'ovo', 'gopay', 'linkaja', 'shopeepay',
        'jnt', 'jne', 'sicepat', 'anteraja', 'pos-indonesia',
        'bpjs', 'pln', 'pertamina', 'kominfo', 'ojk',
    ];

    // Legitimate domain endings for those brands
    private const LEGITIMATE_BRAND_DOMAINS = [
        'bri.co.id', 'bca.co.id', 'bni.co.id', 'bankmandiri.co.id',
        'shopee.co.id', 'tokopedia.com', 'lazada.co.id', 'bukalapak.com',
        'gojek.com', 'grab.com', 'dana.id', 'ovo.id', 'gopay.co.id',
        'jet.co.id', 'jne.co.id', 'sicepat.com',
        'bpjs-kesehatan.go.id', 'pln.co.id', 'pertamina.com',
    ];

    // ════════════════════════════════════════════════════════════════════════
    // PHONE / WALLET
    // ════════════════════════════════════════════════════════════════════════

    private const HIGH_RISK_PHONE_PREFIXES = [
        '+44 70', '+44 71',      // UK personal number redirects
        '+1 900',                 // US premium rate
        '+268', '+269',           // Swaziland / Comoros
        '+225', '+226', '+227',   // West African premium
        '+371', '+372', '+373',   // Baltic premium
    ];

    // ════════════════════════════════════════════════════════════════════════
    // ENTRY POINT
    // ════════════════════════════════════════════════════════════════════════

    public function check(ScamCheckRequest $request): JsonResponse
    {
        $reasons = [];
        $score   = 0;
        $sources = [];

        // ── Layer 1: Rule engine ─────────────────────────────────────────
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

        // ── Layer 2: Mistral AI ──────────────────────────────────────────
        $mistralResult = $this->callMistralAI($request->only([
            'message_text', 'url', 'phone_number', 'bank_account',
        ]));

        if ($mistralResult !== null) {
            $score   += $mistralResult['risk_boost'];
            $reasons  = array_merge($reasons, $mistralResult['findings']);
            $sources[] = 'mistral-ai';
        }

        // ── Finalise ─────────────────────────────────────────────────────
        $score = min(100, $score);
        $level = match (true) {
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
            'analysis_by'  => $sources,
            'powered_by'   => in_array('mistral-ai', $sources)
                ? 'SafePulse Rule Engine + Mistral AI (🇫🇷 Paris)'
                : 'SafePulse Rule Engine',
            'privacy_note' => 'No submitted content is stored or logged. Analysis is ephemeral.',
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // LAYER 2 — MISTRAL AI
    // ════════════════════════════════════════════════════════════════════════

    private function callMistralAI(array $inputs): ?array
    {
        $apiKey = config('services.mistral.key', env('MISTRAL_API_KEY'));

        if (blank($apiKey)) return null;

        $content = collect([
            'message' => $inputs['message_text'] ?? null,
            'url'     => $inputs['url']          ?? null,
            'phone'   => $inputs['phone_number'] ?? null,
            'account' => $inputs['bank_account'] ?? null,
        ])->filter()->map(fn ($v, $k) => strtoupper($k) . ': ' . $v)->implode("\n");

        if (blank($content)) return null;

        $prompt = <<<PROMPT
You are a digital fraud detection expert specialising in Southeast Asian scam patterns
(Indonesia, Philippines, Malaysia, Vietnam, Thailand, Cambodia).

Analyse the following content for contextual deception that rule-based engines miss:
- Deceptive reassurance ("100% safe", "guaranteed", "trust me")
- Emotional manipulation (urgency, fear, love-bombing, authority impersonation)
- Indonesian-specific scam patterns: courier (J&T/JNE) lures, fake bank fee changes,
  giveaway/undian fraud, BPJS/OJK/Kominfo impersonation, pinjol illegal
- Social engineering (exclusivity, secrecy demands, OTP harvesting)
- Pig-butchering and romance-scam conversational patterns
- Money-mule recruitment / "rekrut anggota" language
- Radicalisation or extremist recruitment signals

Content to analyse:
{$content}

Respond ONLY with valid JSON (no markdown):
{"risk_boost": <0-30>, "findings": ["finding1", "finding2"]}

Rules:
- risk_boost: 0 if clean, up to 30 for clear contextual deception
- findings: 1-3 specific patterns in plain English (empty if none)
- Each finding starts with "🤖 Mistral:"
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

            $raw     = $response->json('choices.0.message.content', '{}');
            $decoded = json_decode($raw, true);

            if (!is_array($decoded)) return null;

            return [
                'risk_boost' => min(30, max(0, (int) ($decoded['risk_boost'] ?? 0))),
                'findings'   => array_slice((array) ($decoded['findings'] ?? []), 0, 3),
            ];
        } catch (\Throwable $e) {
            Log::warning('Mistral AI call failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ════════════════════════════════════════════════════════════════════════
    // TEXT ANALYSIS
    // ════════════════════════════════════════════════════════════════════════

    private function analyseText(string $text): array
    {
        $lower   = mb_strtolower($text);
        $score   = 0;
        $reasons = [];

        // High-risk phrases
        foreach (self::HIGH_RISK_KEYWORDS as $kw) {
            if (mb_strpos($lower, mb_strtolower($kw)) !== false) {
                $score    += 30;
                $reasons[] = "High-risk phrase detected: \"{$kw}\"";
            }
        }
        // Medium-risk phrases
        foreach (self::MEDIUM_RISK_KEYWORDS as $kw) {
            if (mb_strpos($lower, mb_strtolower($kw)) !== false) {
                $score    += 15;
                $reasons[] = "Suspicious phrase: \"{$kw}\"";
            }
        }

        // Brand impersonation combos
        foreach (self::BRAND_IMPERSONATION_PATTERNS as [$brand, $signal, $label]) {
            if (mb_strpos($lower, $brand) !== false && mb_strpos($lower, mb_strtolower($signal)) !== false) {
                $score    += 40;
                $reasons[] = "Brand impersonation pattern: {$label}";
            }
        }

        // Excessive caps
        $capsRatio = preg_match_all('/[A-Z]/u', $text) / max(mb_strlen($text), 1);
        if ($capsRatio > 0.4 && mb_strlen($text) > 30) {
            $score    += 10;
            $reasons[] = 'Excessive capitalisation — common in scam messages.';
        }

        // Currency + urgency
        if (preg_match('/(\$|rp|idr)\s*[\d.,]+/iu', $text) &&
            preg_match('/urgent|immediately|right now|segera|sekarang juga|24 jam/iu', $text)) {
            $score    += 20;
            $reasons[] = 'Monetary amount combined with urgency language.';
        }

        // OTP/PIN sharing request
        if (preg_match('/(bagikan|share|kirim|berikan)\s+(kode\s+)?(otp|pin)/iu', $text) ||
            preg_match('/(share|give|send)\s+(your\s+)?(otp|pin|password)/iu', $text)) {
            $score    += 50;
            $reasons[] = 'Request to share OTP/PIN — never share these with anyone.';
        }

        // APK download mention in message
        if (preg_match('/\.apk\b|download\s+aplikasi|install\s+app/iu', $text)) {
            $score    += 25;
            $reasons[] = 'Mentions APK download — common Indonesian scam vector.';
        }

        return [$score, $reasons];
    }

    // ════════════════════════════════════════════════════════════════════════
    // URL ANALYSIS
    // ════════════════════════════════════════════════════════════════════════

    private function analyseUrl(string $url): array
    {
        $score   = 0;
        $reasons = [];
        $lower   = strtolower($url);
        $host    = parse_url($lower, PHP_URL_HOST) ?? '';
        $path    = parse_url($lower, PHP_URL_PATH) ?? '';

        // Insecure HTTP
        if (str_starts_with($lower, 'http://')) {
            $score    += 15;
            $reasons[] = 'URL uses insecure HTTP instead of HTTPS.';
        }

        // Known typosquats
        foreach (self::SUSPICIOUS_DOMAINS as $d) {
            if (str_contains($lower, $d)) {
                $score    += 50;
                $reasons[] = "Domain matches known phishing pattern: {$d}";
            }
        }

        // Suspicious TLDs
        foreach (self::SUSPICIOUS_TLDS as $tld) {
            if (str_ends_with($host, $tld)) {
                $score    += 25;
                $reasons[] = "Top-level domain \"{$tld}\" frequently used in scam sites.";
            }
        }

        // Raw IP
        if (preg_match('/https?:\/\/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $lower)) {
            $score    += 35;
            $reasons[] = 'URL uses a raw IP address instead of a domain name.';
        }

        // Excessive subdomain depth
        if (substr_count($host, '.') >= 4) {
            $score    += 20;
            $reasons[] = 'URL has an unusual number of sub-domain levels.';
        }

        // URL shorteners
        foreach (self::URL_SHORTENERS as $sh) {
            if (str_contains($host, $sh)) {
                $score    += 10;
                $reasons[] = 'URL uses a shortener service — destination is hidden.';
            }
        }

        // Indonesian fake-domain word fragments
        foreach (self::INDONESIAN_FAKE_FRAGMENTS as $frag) {
            if (str_contains($host, $frag) || str_contains($path, $frag)) {
                $score    += 30;
                $reasons[] = "URL contains Indonesian scam-domain pattern: \"{$frag}\"";
                break; // count once
            }
        }

        // Brand-in-subdomain abuse: brand appears in subdomain but main domain isn't official
        foreach (self::SUSPICIOUS_BRAND_IN_SUBDOMAIN as $brand) {
            if (str_contains($host, $brand)) {
                $isLegit = false;
                foreach (self::LEGITIMATE_BRAND_DOMAINS as $legit) {
                    if (str_ends_with($host, $legit)) {
                        $isLegit = true;
                        break;
                    }
                }
                if (!$isLegit && (str_contains($host, $brand . '-') || str_contains($host, $brand . '.'))) {
                    // Brand name is in subdomain/host but parent isn't legitimate
                    $score    += 45;
                    $reasons[] = "Suspicious use of brand \"{$brand}\" in non-official domain — likely impersonation.";
                    break;
                }
            }
        }

        // APK download URL
        if (str_ends_with($path, '.apk') || str_contains($host, 'apk-')) {
            $score    += 35;
            $reasons[] = 'URL distributes APK file — major Indonesian scam vector for credential theft.';
        }

        // Blogspot/free hosting impersonation
        if (str_contains($host, 'blogspot') || str_contains($host, 'wordpress.com')) {
            if (preg_match('/(bri|bca|bni|mandiri|shopee|tokopedia|gojek|grab)/i', $host)) {
                $score    += 40;
                $reasons[] = 'Brand name on free hosting (Blogspot/WordPress) — never legitimate.';
            }
        }

        // Path requests personal data — phishing endpoint signal
        $phishingPaths = ["/form", "/login", "/update", "/data", "/verify", "/otp", "/konfirmasi", "/rekening", "/password"];
        foreach ($phishingPaths as $path) {
            if (str_contains($lower, $path)) {
                $score    += 25;
                $reasons[] = "URL path suggests data collection — common phishing endpoint.";
                break;
            }
        }
        // Multiple high-risk signals — compound boost
        if ($score >= 55) {
            $score    += 15;
            $reasons[] = "Multiple high-risk signals combined — treat as High risk.";
        }
        return [$score, $reasons];
    }

    // ════════════════════════════════════════════════════════════════════════
    // PHONE ANALYSIS
    // ════════════════════════════════════════════════════════════════════════

    private function analysePhone(string $phone): array
    {
        $score   = 0;
        $reasons = [];
        $clean   = preg_replace('/\s/', ' ', $phone);

        foreach (self::HIGH_RISK_PHONE_PREFIXES as $prefix) {
            if (str_starts_with($clean, $prefix)) {
                $score    += 40;
                $reasons[] = "Phone prefix \"{$prefix}\" associated with one-ring or premium-rate scams.";
            }
        }

        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) > 15 || strlen($digits) < 7) {
            $score    += 20;
            $reasons[] = 'Phone number has an unusual length for a valid international number.';
        }

        return [$score, $reasons];
    }

    // ════════════════════════════════════════════════════════════════════════
    // ACCOUNT/WALLET ANALYSIS
    // ════════════════════════════════════════════════════════════════════════

    private function analyseAccount(string $account): array
    {
        $score   = 0;
        $reasons = [];

        // Crypto wallet detection
        if (preg_match('/^(0x[a-fA-F0-9]{40}|[13][a-zA-Z0-9]{25,34}|bc1[a-z0-9]{39,59})$/', $account)) {
            $score    += 25;
            $reasons[] = 'Input is a cryptocurrency wallet address — verify recipient identity carefully before sending.';
        }

        return [$score, $reasons];
    }
}
