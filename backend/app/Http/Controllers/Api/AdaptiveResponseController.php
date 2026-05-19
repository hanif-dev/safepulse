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
        // Profile-differentiated action sets
        $actionMatrix = [
            'victim' => [
                'phishing'     => ['change_password', 'enable_2fa', 'report_patrolisiber'],
                'romance_scam' => ['block_perpetrator', 'preserve_evidence', 'contact_bank'],
                'tppo'         => ['contact_kbri', 'preserve_passport_copy', 'reach_safehouse'],
                'radicalization' => ['contact_bnpt_family_channel', 'document_changes_safely'],
            ],
            'family' => [
                'romance_scam' => ['approach_without_judgment', 'avoid_confiscating_phone'],
                'radicalization' => ['non_confrontational_dialogue', 'engage_trusted_figure'],
                'tppo'         => ['contact_kemlu_safe_travel', 'avoid_alerting_recruiter'],
            ],
            'professional' => [
                'phishing'     => ['case_referral_template', 'data_export_anonymized'],
                'tppo'         => ['ijm_referral', 'iom_coordination'],
            ],
            'researcher' => [
                '_default'     => ['request_data_access', 'review_methodology'],
            ],
        ];

        $actionKeys = $actionMatrix[$role][$domain]
            ?? $actionMatrix[$role]['_default']
            ?? $actionMatrix['victim'][$domain]
            ?? ['contact_emergency', 'preserve_evidence', 'reach_trusted_person'];

        return collect($actionKeys)->map(fn ($key) => [
            'key'         => $key,
            'title'       => __("adaptive.actions.$key.title", [], $locale),
            'description' => __("adaptive.actions.$key.description", [], $locale),
        ])->toArray();
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
