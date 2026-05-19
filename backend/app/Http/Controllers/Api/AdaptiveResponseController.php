<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DeepAssessment;
use App\Models\Hotline;
use App\Models\RecoveryPathway;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Adaptive Response — Quick Mode and Deep Mode.
 *
 * Quick Mode: 3 questions, returns immediate top-3 actions + hotlines.
 * Deep Mode: stepped, branching, resumable via anonymous session_token.
 *
 * Profile-differentiated output (the feminist-cybersecurity move):
 *   victim       → personal recovery plan
 *   family       → "approach without confrontation" scripts
 *   professional → referral matrix + anonymized cohort data
 *   researcher   → request access to aggregated dataset
 */
class AdaptiveResponseController extends Controller
{
    private const VALID_ROLES   = ['victim', 'family', 'professional', 'researcher'];
    private const VALID_DOMAINS = [
        'phishing', 'romance_scam', 'investment_fraud', 'tppo',
        'money_laundering', 'csam', 'cyberbullying', 'gang_recruitment',
        'migrant_worker', 'civic_conflict', 'radicalization',
    ];

    // ──────────────────────────────────────────────────────────────────────
    // QUICK MODE
    // ──────────────────────────────────────────────────────────────────────

    public function quick(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role'    => 'required|in:' . implode(',', self::VALID_ROLES),
            'domain'  => 'required|in:' . implode(',', self::VALID_DOMAINS),
            'country' => 'required|size:2',
            'locale'  => 'nullable|string|max:8',
        ]);

        $locale = $data['locale'] ?? 'id';

        AuditLog::record('adaptive.quick', 'anonymous', [
            'role'   => $data['role'],
            'domain' => $data['domain'],
            'country'=> $data['country'],
        ]);

        return response()->json([
            'mode'             => 'quick',
            'profile_role'     => $data['role'],
            'crime_domain'     => $data['domain'],
            'top_actions'      => $this->topActionsFor($data['role'], $data['domain'], $locale),
            'emergency_hotlines' => $this->emergencyHotlines($data['country'], $data['domain']),
            'upgrade_invitation' => $this->upgradeInvitation($locale),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // DEEP MODE
    // ──────────────────────────────────────────────────────────────────────

    public function deepStart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role'    => 'required|in:' . implode(',', self::VALID_ROLES),
            'country' => 'required|size:2',
            'locale'  => 'nullable|string|max:8',
            'consent' => 'required|array',
            'consent.pfa_disclaimer'  => 'required|accepted',
            'consent.data_use'        => 'required|accepted',
        ]);

        $profile = UserProfile::create([
            'session_token' => bin2hex(random_bytes(32)),
            'role'          => $data['role'],
            'country_iso'   => $data['country'],
            'locale'        => $data['locale'] ?? 'id',
            'consent_flags' => $data['consent'],
            'expires_at'    => now()->addDays(30),
        ]);

        AuditLog::record('adaptive.deep_start', 'anonymous', [
            'role'    => $profile->role,
            'country' => $profile->country_iso,
        ]);

        return response()->json([
            'token'          => $profile->session_token,
            'expires_at'     => $profile->expires_at,
            'first_question' => $this->firstQuestion($profile),
            'safety_message' => $this->safetyGate($profile->locale),
        ]);
    }

    public function deepAnswer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token'       => 'required|string|size:64',
            'domain'      => 'required|in:' . implode(',', self::VALID_DOMAINS),
            'question_id' => 'required|string',
            'answer'      => 'required',
        ]);

        $profile = UserProfile::active()
            ->where('session_token', $data['token'])
            ->firstOrFail();

        $assessment = DeepAssessment::firstOrCreate(
            ['user_profile_id' => $profile->id, 'crime_domain' => $data['domain']],
            ['mode' => 'deep', 'answers' => [], 'risk_signals' => []]
        );

        $answers = $assessment->answers;
        $answers[$data['question_id']] = $data['answer'];
        $assessment->answers = $answers;
        $assessment->risk_signals = $this->scoreSignals($answers, $data['domain']);
        $assessment->completion_pct = $this->computeCompletion($answers, $data['domain']);
        $assessment->save();

        $next = $this->nextQuestion($profile, $assessment);

        return response()->json([
            'completion_pct' => $assessment->completion_pct,
            'next_question'  => $next,
            'risk_signals'   => $assessment->risk_signals,
            'is_complete'    => $next === null,
        ]);
    }

    public function deepResolve(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token'  => 'required|string|size:64',
            'domain' => 'required|in:' . implode(',', self::VALID_DOMAINS),
        ]);

        $profile = UserProfile::active()
            ->where('session_token', $data['token'])
            ->firstOrFail();

        $assessment = DeepAssessment::where('user_profile_id', $profile->id)
            ->where('crime_domain', $data['domain'])
            ->firstOrFail();

        $plan = $this->buildPersonalizedPlan($profile, $assessment);

        AuditLog::record('adaptive.deep_resolve', 'anonymous', [
            'role'           => $profile->role,
            'domain'         => $data['domain'],
            'completion_pct' => $assessment->completion_pct,
        ]);

        return response()->json($plan);
    }

    // ──────────────────────────────────────────────────────────────────────
    // INTERNAL — assessment logic
    // ──────────────────────────────────────────────────────────────────────

    private function topActionsFor(string $role, string $domain, string $locale): array
    {
        $actions = [
            'victim' => [
                'romance_scam' => [
                    ['key'=>'block_perpetrator',  'title'=>'Blokir pelaku sekarang',            'description'=>'Blokir akun pelaku di semua platform. Jangan hapus percakapan — simpan sebagai bukti.'],
                    ['key'=>'preserve_evidence',  'title'=>'Simpan semua bukti',                'description'=>'Screenshot percakapan, nomor rekening, dan bukti transaksi sebelum apapun dihapus.'],
                    ['key'=>'contact_bank',       'title'=>'Hubungi bank dalam 24 jam',        'description'=>'Laporkan ke bank untuk pemblokiran rekening tujuan transfer. Semakin cepat semakin baik.'],
                    ['key'=>'report_ojk',         'title'=>'Lapor ke OJK 157',                 'description'=>'Hubungi OJK via telepon 157, WhatsApp 081157157157, atau kontak157.ojk.go.id.'],
                    ['key'=>'patrolisiber',       'title'=>'Lapor ke Patrolisiber',             'description'=>'Buat laporan di patrolisiber.id/submit-report dengan menyertakan semua bukti.'],
                ],
                'phishing' => [
                    ['key'=>'change_password',    'title'=>'Ganti semua kata sandi sekarang',  'description'=>'Prioritaskan: email utama, mobile banking, dan media sosial. Gunakan kata sandi unik per akun.'],
                    ['key'=>'enable_2fa',         'title'=>'Aktifkan autentikasi dua faktor',  'description'=>'Aktifkan 2FA di semua akun penting menggunakan aplikasi authenticator.'],
                    ['key'=>'contact_bank',       'title'=>'Hubungi bank segera',              'description'=>'Jika data perbankan bocor, hubungi call center bank untuk blokir kartu dan amankan rekening.'],
                    ['key'=>'report_patrolisiber','title'=>'Lapor ke Patrolisiber',             'description'=>'Buat laporan resmi di patrolisiber.id/submit-report dengan bukti link phishing.'],
                ],
                'investment_fraud' => [
                    ['key'=>'stop_transfer',      'title'=>'Hentikan semua transfer sekarang', 'description'=>'Jangan kirim uang lagi meski diminta pajak penarikan atau biaya admin — itu bagian penipuan.'],
                    ['key'=>'report_ojk',         'title'=>'Lapor ke OJK 157',                 'description'=>'Hubungi OJK 157 atau sikapiuangmu.ojk.go.id untuk melaporkan investasi bodong.'],
                    ['key'=>'preserve_evidence',  'title'=>'Dokumentasikan semua transaksi',   'description'=>'Simpan bukti transfer, screenshot platform, dan semua komunikasi dengan pelaku.'],
                    ['key'=>'no_recovery_agent',  'title'=>'Waspada agen pemulihan dana palsu','description'=>'Yang menawarkan memulihkan dana hampir selalu penipuan kedua. Abaikan dan laporkan.'],
                ],
                'tppo' => [
                    ['key'=>'contact_kbri',       'title'=>'Hubungi KBRI segera',              'description'=>'Jika di luar negeri, hubungi Kedutaan Besar Indonesia. KBRI Phnom Penh darurat: +855-12-810-005.'],
                    ['key'=>'safe_travel',        'title'=>'Gunakan Portal Peduli WNI',        'description'=>'Akses peduliwni.kemlu.go.id atau aplikasi Safe Travel — ada tombol panic untuk darurat.'],
                    ['key'=>'bp2mi',              'title'=>'Hubungi BP2MI',                    'description'=>'Hubungi BP2MI di bp2mi.go.id atau telepon +62-21-2924-4800 untuk bantuan pekerja migran.'],
                ],
                'radicalization' => [
                    ['key'=>'bnpt_family',        'title'=>'Hubungi kanal keluarga BNPT',     'description'=>'BNPT menyediakan konsultasi rahasia untuk keluarga yang khawatir melalui bnpt.go.id.'],
                    ['key'=>'document_safely',    'title'=>'Catat perubahan perilaku',         'description'=>'Catat perubahan bahasa, pergaulan, atau kebiasaan — membantu konsultan memberi saran tepat.'],
                    ['key'=>'no_confrontation',   'title'=>'Jangan konfrontasi langsung',      'description'=>'Konfrontasi mempercepat penarikan diri. Pertahankan komunikasi terbuka dan non-judgemental.'],
                ],
                'cyberbullying' => [
                    ['key'=>'preserve_evidence',  'title'=>'Screenshot semua bukti',           'description'=>'Tangkap layar semua konten bullying sebelum dihapus. Catat tanggal, waktu, dan platform.'],
                    ['key'=>'report_platform',    'title'=>'Laporkan ke platform',             'description'=>'Gunakan fitur report di media sosial. Ini mempercepat penghapusan konten berbahaya.'],
                    ['key'=>'aduankonten',        'title'=>'Lapor ke AduanKonten Komdigi',    'description'=>'Laporkan di aduankonten.id atau WhatsApp 08119224545 untuk konten berbahaya online.'],
                ],
                '_default' => [
                    ['key'=>'preserve_evidence',  'title'=>'Simpan semua bukti',               'description'=>'Screenshot, rekam, dan simpan semua bukti sebelum apapun dihapus atau diblokir.'],
                    ['key'=>'report_patrolisiber','title'=>'Lapor ke Patrolisiber',             'description'=>'Buat laporan di patrolisiber.id/submit-report dengan menyertakan semua bukti.'],
                    ['key'=>'trusted_person',     'title'=>'Ceritakan ke orang terpercaya',    'description'=>'Jangan hadapi ini sendirian. Ceritakan kepada anggota keluarga atau teman yang dipercaya.'],
                ],
            ],
            'family' => [
                'romance_scam' => [
                    ['key'=>'no_judgment',        'title'=>'Dekati tanpa menghakimi',          'description'=>'Korban romance scam sering merasa malu. Mulailah dengan empati, bukan kritik atau teguran.'],
                    ['key'=>'no_phone_confiscate','title'=>'Jangan sita HP',                   'description'=>'Menyita HP dapat memperparah isolasi dan menghapus bukti. Tawarkan bantuan, bukan kontrol.'],
                    ['key'=>'suggest_report',     'title'=>'Bantu laporkan bersama',           'description'=>'Tawarkan untuk menemani melapor ke Patrolisiber atau OJK — dukungan sosial sangat penting.'],
                ],
                'radicalization' => [
                    ['key'=>'no_confrontation',   'title'=>'Hindari konfrontasi ideologi',     'description'=>'Perdebatan tentang ideologi hampir selalu kontraproduktif. Fokus pada hubungan, bukan argumen.'],
                    ['key'=>'trusted_figure',     'title'=>'Libatkan tokoh terpercaya',        'description'=>'Kyai, ustaz moderat, atau tokoh komunitas yang dihormati dapat membuka dialog yang tidak bisa dilakukan keluarga.'],
                    ['key'=>'bnpt_family',        'title'=>'Konsultasi rahasia dengan BNPT',  'description'=>'BNPT menyediakan layanan konsultasi keluarga yang bersifat rahasia melalui bnpt.go.id.'],
                ],
                'tppo' => [
                    ['key'=>'kemlu_safe_travel',  'title'=>'Hubungi Kemlu Safe Travel',       'description'=>'Akses safetravel.kemlu.go.id atau hubungi hotline Kemlu 1500-454 untuk WNI di luar negeri.'],
                    ['key'=>'no_alert_recruiter', 'title'=>'Jangan beritahu perekrut',        'description'=>'Menghubungi perekrut atau majikan dapat meningkatkan risiko bagi orang yang terdampak.'],
                ],
                '_default' => [
                    ['key'=>'listen_first',       'title'=>'Dengarkan dulu',                   'description'=>'Berikan ruang bagi orang yang terdampak untuk bercerita tanpa interupsi atau penilaian.'],
                    ['key'=>'practical_help',     'title'=>'Tawarkan bantuan praktis',         'description'=>'Tawarkan untuk menemani melapor, membantu mengumpulkan bukti, atau menghubungi lembaga terkait.'],
                ],
            ],
            'professional' => [
                '_default' => [
                    ['key'=>'case_template',      'title'=>'Gunakan template laporan kasus',  'description'=>'SafePulse menyediakan template laporan selaras taksonomi INTERPOL I-GRIP untuk dokumentasi profesional.'],
                    ['key'=>'referral_matrix',    'title'=>'Rujuk ke lembaga yang tepat',     'description'=>'TPPO: IJM/IOM. Finansial: OJK/PPATK. Kesehatan jiwa: Into The Light/SEJIWA 119 ext 8. Hukum: YLBHI.'],
                ],
            ],
            'researcher' => [
                '_default' => [
                    ['key'=>'data_request',       'title'=>'Akses data agregat anonim',       'description'=>'Peneliti dapat mengajukan permohonan akses dataset anonim SafePulse untuk keperluan riset akademik.'],
                    ['key'=>'methodology',        'title'=>'Lihat metodologi SafePulse',      'description'=>'Dokumentasi framework SafePulse tersedia di halaman Evidence dan SEO & GEO.'],
                ],
            ],
        ];

        $roleActions   = $actions[$role]           ?? $actions['victim'];
        $domainActions = $roleActions[$domain]     ?? $roleActions['_default'] ?? $actions['victim']['_default'];

        return $domainActions;
    }

    private function emergencyHotlines(string $country, string $domain): array
    {
        return Hotline::verified()
            ->forCountry($country)
            ->get()
            ->filter(fn ($h) => in_array($domain, $h->domains_served) || in_array('all', $h->domains_served))
            ->take(3)
            ->map(fn ($h) => [
                'slug'             => $h->slug,
                'name'             => $h->name,
                'contact_channels' => $h->contact_channels,
                'availability'     => $h->availability,
            ])
            ->values()
            ->toArray();
    }

    private function upgradeInvitation(string $locale): array
    {
        $messages = [
            'en' => [
                'title'   => 'Need more detailed guidance?',
                'message' => 'Try Deep Mode for personalized recovery steps based on your specific situation.',
                'cta'     => 'Start Deep Mode',
            ],
            'id' => [
                'title'   => 'Butuh panduan lebih mendalam?',
                'message' => 'Coba Mode Mendalam untuk langkah pemulihan personal berdasarkan situasi spesifik Anda.',
                'cta'     => 'Mulai Mode Mendalam',
            ],
        ];
        return $messages[$locale] ?? $messages['en'];
    }

    private function safetyGate(string $locale): string
    {
        $messages = [
            'en' => 'Before we continue, please make sure you are in a safe and private space. You can exit at any time.',
            'id' => 'Sebelum melanjutkan, pastikan Anda berada di tempat yang aman dan pribadi. Anda dapat keluar kapan saja.',
            'fr' => 'Avant de continuer, assurez-vous d\'être dans un espace sûr et privé. Vous pouvez quitter à tout moment.',
        ];
        return $messages[$locale] ?? $messages['en'];
    }

    private function firstQuestion(UserProfile $profile): array
    {
        return [
            'id'       => 'crime_domain',
            'type'     => 'single_select',
            'question' => __('adaptive.questions.crime_domain', [], $profile->locale),
            'options'  => collect(self::VALID_DOMAINS)->map(fn ($d) => [
                'value' => $d,
                'label' => __("adaptive.domains.$d", [], $profile->locale),
            ])->toArray(),
            'allow_skip' => true,
        ];
    }

    private function nextQuestion(UserProfile $profile, DeepAssessment $assessment): ?array
    {
        $answered = array_keys($assessment->answers);
        $sequence = $this->questionSequence($assessment->crime_domain, $profile->role);

        foreach ($sequence as $q) {
            if (! in_array($q['id'], $answered)) {
                return array_merge($q, [
                    'question' => __($q['key'], [], $profile->locale),
                ]);
            }
        }
        return null;
    }

    private function questionSequence(string $domain, string $role): array
    {
        // Universal questions (Crisis Text Line risk-laddering)
        $universal = [
            ['id' => 'safety_now',    'key' => 'adaptive.q.safety_now',    'type' => 'yes_no'],
            ['id' => 'first_time',    'key' => 'adaptive.q.first_time',    'type' => 'yes_no'],
            ['id' => 'trusted_person','key' => 'adaptive.q.trusted_person','type' => 'yes_no'],
        ];

        // Domain-specific branches
        $domainSpecific = match ($domain) {
            'romance_scam' => [
                ['id' => 'duration',   'key' => 'adaptive.q.romance.duration',   'type' => 'select'],
                ['id' => 'transferred','key' => 'adaptive.q.romance.transferred','type' => 'number'],
                ['id' => 'images_shared','key' => 'adaptive.q.romance.images', 'type' => 'yes_no'],
            ],
            'tppo' => [
                ['id' => 'passport',     'key' => 'adaptive.q.tppo.passport',  'type' => 'yes_no'],
                ['id' => 'location_now', 'key' => 'adaptive.q.tppo.location',  'type' => 'text'],
                ['id' => 'can_communicate','key' => 'adaptive.q.tppo.communicate','type' => 'yes_no'],
            ],
            'radicalization' => [
                ['id' => 'language_change','key' => 'adaptive.q.radical.language','type' => 'yes_no'],
                ['id' => 'isolation',     'key' => 'adaptive.q.radical.isolation','type' => 'yes_no'],
                ['id' => 'secret_groups', 'key' => 'adaptive.q.radical.groups','type' => 'yes_no'],
                ['id' => 'travel_plans',  'key' => 'adaptive.q.radical.travel','type' => 'yes_no'],
            ],
            default => [],
        };

        return array_merge($universal, $domainSpecific);
    }

    private function scoreSignals(array $answers, string $domain): array
    {
        $signals = [];

        if (($answers['safety_now'] ?? null) === 'no') {
            $signals[] = ['type' => 'immediate_safety_risk', 'severity' => 'high'];
        }
        if (($answers['trusted_person'] ?? null) === 'no') {
            $signals[] = ['type' => 'social_isolation', 'severity' => 'medium'];
        }

        if ($domain === 'tppo' && ($answers['passport'] ?? null) === 'yes') {
            $signals[] = ['type' => 'document_captivity', 'severity' => 'critical'];
        }
        if ($domain === 'radicalization' && (
            ($answers['secret_groups'] ?? null) === 'yes' ||
            ($answers['travel_plans'] ?? null) === 'yes'
        )) {
            $signals[] = ['type' => 'escalation_indicators', 'severity' => 'high'];
        }

        return $signals;
    }

    private function computeCompletion(array $answers, string $domain): int
    {
        $expected = count($this->questionSequence($domain, 'victim'));
        if ($expected === 0) {
            return 0;
        }
        return (int) min(100, (count($answers) / $expected) * 100);
    }

    private function buildPersonalizedPlan(UserProfile $profile, DeepAssessment $assessment): array
    {
        // Find matching recovery pathway
        $pathway = RecoveryPathway::published()
            ->where('crime_domain', $assessment->crime_domain)
            ->first();

        // Profile-differentiated guidance
        $roleGuidance = match ($profile->role) {
            'victim'       => 'recovery_focused',
            'family'       => 'approach_focused',
            'professional' => 'referral_focused',
            'researcher'   => 'data_access_focused',
        };

        return [
            'profile_role'     => $profile->role,
            'crime_domain'     => $assessment->crime_domain,
            'risk_signals'     => $assessment->risk_signals,
            'guidance_focus'   => $roleGuidance,
            'recovery_pathway' => $pathway ? [
                'slug'  => $pathway->slug,
                'title' => $pathway->localized('title', $profile->locale),
                'url'   => "/recovery/{$pathway->slug}?lang={$profile->locale}",
            ] : null,
            'next_steps'       => $this->topActionsFor($profile->role, $assessment->crime_domain, $profile->locale),
            'emergency_hotlines' => $this->emergencyHotlines($profile->country_iso, $assessment->crime_domain),
        ];
    }
}
