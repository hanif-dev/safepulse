<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\KnowledgeDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SafePulse Incident Reporter
 *
 * After a report is submitted, returns:
 *  - Personalised advice per threat category
 *  - Local Indonesian resources (BNPT, OJK 157, Bareskrim, BSSN, PPATK, BPJS)
 *  - Action checklist
 *  - Knowledge-base references (developer-curated)
 *  - Optional Mistral AI contextual response
 *
 * Privacy: only category/country/age/description stored. NO identity collected.
 * Framework: GDPR-inspired data minimisation + INTERPOL I-GRIP taxonomy.
 */
class IncidentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category'                 => 'required|string|max:60',
            'country'                  => 'required|string|max:60',
            'age_group'                => 'nullable|string|max:20',
            'description'              => 'required|string|min:20|max:5000',
            'health_impact_level'      => 'required|in:low,medium,high',
            'financial_loss_estimate'  => 'nullable|numeric|min:0|max:9999999999',
        ]);

        // Persist anonymous report
        $incident = Incident::create($data);

        // Generate intelligent advice response
        $advice    = $this->generateAdvice($data['category'], $data['country'], $data['description']);
        $resources = $this->getLocalResources($data['category'], $data['country']);
        $checklist = $this->getActionChecklist($data['category']);
        $knowledge = $this->getKnowledgeReferences($data['category']);

        // Optional Mistral AI contextual analysis
        $aiAnalysis = $this->callMistralForAdvice($data);

        return response()->json([
            'success'      => true,
            'incident_id'  => $incident->id,
            'message'      => 'Your anonymous report has been recorded. Thank you for contributing to community safety.',

            // Smart response payload
            'analysis'     => [
                'category_label'    => $this->categoryLabel($data['category']),
                'severity'          => $data['health_impact_level'],
                'advice'            => $advice,
                'resources'         => $resources,
                'action_checklist'  => $checklist,
                'knowledge_refs'    => $knowledge,
                'ai_analysis'       => $aiAnalysis,
                'powered_by'        => $aiAnalysis !== null
                    ? 'SafePulse Advisor + Mistral AI (🇫🇷 Paris)'
                    : 'SafePulse Advisor',
            ],

            'privacy_note' => 'Only category, country, age group (optional), and description are stored. Your identity, IP, and any personal data are NEVER collected.',
        ], 201);
    }

    // ════════════════════════════════════════════════════════════════════════
    // ADVICE BY CATEGORY
    // ════════════════════════════════════════════════════════════════════════

    private function generateAdvice(string $category, string $country, string $description): array
    {
        $advice = [
            'phishing' => [
                'headline' => 'Suspected phishing — your immediate priorities',
                'steps'    => [
                    'Do NOT click any link in the message — even if it appears official',
                    'If you have already clicked: disconnect from internet and run device antivirus scan',
                    'If you entered credentials: change passwords IMMEDIATELY on the affected service and any account using the same password',
                    'Contact your bank or service provider through their official phone number (NOT the one in the suspicious message)',
                    'Enable two-factor authentication on all sensitive accounts',
                    'Take screenshots before deleting the message — useful evidence for law enforcement',
                ],
            ],
            'investment' => [
                'headline' => 'Suspected investment fraud — protect remaining assets',
                'steps'    => [
                    'STOP all further transfers immediately, even if the platform asks for "tax" or "release fees"',
                    'Verify the platform with national regulator: OJK (ojk.go.id) in Indonesia, SC in Malaysia, SEC in Philippines',
                    'Document all transactions: dates, amounts, recipient accounts, screenshots',
                    'Contact your bank to flag/reverse recent transfers (small chance, but try within 24 hours)',
                    'File report at lapor.go.id and patrolisiber.id',
                    'Join survivor communities (GASO — Global Anti-Scam Org) for emotional support',
                    'DO NOT contact "recovery agents" claiming they can get your money back — they are a second scam targeting victims',
                ],
            ],
            'romance' => [
                'headline' => 'Romance scam — disengage and seek emotional support',
                'steps'    => [
                    'Cut ALL contact: block on every platform. Even one reply restarts manipulation',
                    'Secure your financial accounts: change passwords, review recent transactions',
                    'Tell ONE trusted person — isolation prolongs harm',
                    'Save evidence (screenshots, transfer receipts) before blocking',
                    'Recognise: you were targeted by a professional criminal organisation. The shame is not yours',
                    'Consider trauma-informed counselling — romance scam recovery requires real therapy',
                    'Report to your national cybercrime unit (Bareskrim Polri in Indonesia)',
                ],
            ],
            'radicalization' => [
                'headline' => 'Radicalization concern — handle with care',
                'steps'    => [
                    'Do NOT confront directly — it pushes the person deeper into the group',
                    'Maintain open, non-judgmental communication',
                    'Engage trusted figures the person respects (religious leader, teacher, family elder)',
                    'Contact BNPT (Badan Nasional Penanggulangan Terorisme) at bnpt.go.id — they offer confidential family consultation',
                    'Document observed changes (without surveillance): new vocabulary, withdrawn behaviour, online groups joined',
                    'Preserve relationships: isolation accelerates radicalization',
                    'For minors: contact Komnas Perlindungan Anak (021-31901369)',
                ],
            ],
            'money_laundering' => [
                'headline' => 'Money laundering involvement — legal urgency',
                'steps'    => [
                    'STOP all account activity immediately — do not transfer any further funds',
                    'Do NOT speak to police WITHOUT consulting a lawyer first',
                    'Voluntary disclosure often results in lighter treatment than being caught through investigation',
                    'Preserve ALL records: bank statements, messages, identification of the recruiter',
                    'Contact PPATK (Pusat Pelaporan dan Analisis Transaksi Keuangan) at ppatk.go.id',
                    'Indonesia UU No. 8/2010: penalties up to 20 years prison + Rp 10 billion fine — "I didn\'t know" is rarely a full defence',
                    'If you were recruited through fake job ads: keep evidence of the job posting and conversations',
                ],
            ],
            'other' => [
                'headline' => 'Threat reported — general safety guidance',
                'steps'    => [
                    'Document everything: screenshots, dates, contact details of suspicious parties',
                    'Do not engage further with the suspect contact',
                    'Report to your national cybercrime portal (patrolisiber.id for Indonesia)',
                    'Tell a trusted family member or friend',
                    'If financial loss occurred: contact your bank within 24 hours',
                    'Browse SafePulse Insights library for similar incident patterns',
                ],
            ],
        ];

        return $advice[$category] ?? $advice['other'];
    }

    // ════════════════════════════════════════════════════════════════════════
    // LOCAL RESOURCES BY COUNTRY + CATEGORY
    // ════════════════════════════════════════════════════════════════════════

    private function getLocalResources(string $category, string $country): array
    {
        $idResources = [
            'phishing'         => [
                ['name' => 'Patroli Siber Polri',        'contact' => 'patrolisiber.id',           'type' => 'Online report'],
                ['name' => 'OJK Konsumen',                'contact' => '157',                       'type' => 'Phone hotline'],
                ['name' => 'BSSN — Cyber Incident Resp.', 'contact' => 'bssn.go.id',                'type' => 'Government agency'],
                ['name' => 'Kominfo Aduan Konten',         'contact' => 'aduankonten.id',            'type' => 'Online report'],
            ],
            'investment'       => [
                ['name' => 'OJK Investasi Bodong',         'contact' => '157 / konsumen@ojk.go.id',  'type' => 'Regulator hotline'],
                ['name' => 'Bappebti (crypto/forex)',      'contact' => 'bappebti.go.id',            'type' => 'Commodity regulator'],
                ['name' => 'Satgas Waspada Investasi',     'contact' => 'sikapiuangmu.ojk.go.id',    'type' => 'Anti-fraud task force'],
                ['name' => 'Bareskrim Polri',              'contact' => 'patrolisiber.id',           'type' => 'Police cyber unit'],
            ],
            'romance'          => [
                ['name' => 'Into The Light Indonesia',     'contact' => 'intothelightid.org',        'type' => 'Mental health'],
                ['name' => 'GASO Indonesia',                'contact' => 'globalantiscam.org',        'type' => 'Survivor support'],
                ['name' => 'Patroli Siber Polri',           'contact' => 'patrolisiber.id',           'type' => 'Online report'],
                ['name' => 'Lapor!',                        'contact' => 'lapor.go.id',               'type' => 'Public services portal'],
            ],
            'radicalization'   => [
                ['name' => 'BNPT (Counter-Terrorism)',     'contact' => 'bnpt.go.id  ·  (021) 7972931', 'type' => 'Government agency'],
                ['name' => 'Komnas Perlindungan Anak',     'contact' => '021-31901369',              'type' => 'Child protection'],
                ['name' => 'YPP (Yayasan Prasasti)',       'contact' => 'prasastiperdamaian.org',    'type' => 'PCVE NGO'],
                ['name' => 'AIDA — Aliansi Indonesia Damai','contact' => 'aida.or.id',                'type' => 'Survivor & community'],
            ],
            'money_laundering' => [
                ['name' => 'PPATK',                         'contact' => 'ppatk.go.id  ·  021-3850455','type' => 'Financial intelligence'],
                ['name' => 'Bareskrim Polri',               'contact' => 'patrolisiber.id',           'type' => 'Police cyber unit'],
                ['name' => 'LBH Jakarta (legal aid)',       'contact' => 'bantuanhukum.or.id',        'type' => 'Free legal counsel'],
                ['name' => 'OJK Konsumen',                  'contact' => '157',                       'type' => 'Banking concerns'],
            ],
            'other'            => [
                ['name' => 'Lapor!',                        'contact' => 'lapor.go.id',               'type' => 'Public services'],
                ['name' => 'Patroli Siber Polri',           'contact' => 'patrolisiber.id',           'type' => 'Cyber report'],
                ['name' => 'Komnas HAM',                    'contact' => 'komnasham.go.id',           'type' => 'Human rights'],
            ],
        ];

        $defaultResources = [
            ['name' => 'INTERPOL Cyber Tips',           'contact' => 'interpol.int',              'type' => 'International'],
            ['name' => 'Your national CERT',             'contact' => 'check national portal',      'type' => 'Cybersecurity team'],
            ['name' => 'Local police cyber unit',        'contact' => 'national emergency line',    'type' => 'Law enforcement'],
        ];

        return $country === 'Indonesia'
            ? ($idResources[$category] ?? $idResources['other'])
            : $defaultResources;
    }

    // ════════════════════════════════════════════════════════════════════════
    // ACTION CHECKLIST
    // ════════════════════════════════════════════════════════════════════════

    private function getActionChecklist(string $category): array
    {
        $base = [
            'Take screenshots of all evidence',
            'Do not engage further with the suspect contact',
            'Tell one trusted person about what happened',
            'Change passwords on potentially exposed accounts',
        ];

        $category_specific = match ($category) {
            'phishing'         => ['Run antivirus scan on your device', 'Enable 2FA on all critical accounts'],
            'investment'       => ['Contact your bank within 24 hours', 'Verify platform with national regulator (OJK)'],
            'romance'          => ['Block on every platform', 'Consider trauma-informed counselling'],
            'radicalization'   => ['Contact BNPT for confidential consultation', 'Maintain non-confrontational dialogue'],
            'money_laundering' => ['Consult a lawyer BEFORE speaking to police', 'Stop all account activity'],
            default            => ['Browse SafePulse Insights for similar cases'],
        };

        return array_merge($base, $category_specific);
    }

    // ════════════════════════════════════════════════════════════════════════
    // KNOWLEDGE BASE REFERENCES
    // ════════════════════════════════════════════════════════════════════════

    private function getKnowledgeReferences(string $category): array
    {
        try {
            $docs = KnowledgeDocument::where('is_active', true)
                ->where('topic', $category)
                ->orderByDesc('year')
                ->limit(3)
                ->get();

            return $docs->map(fn ($d) => [
                'title'  => $d->title,
                'source' => $d->source,
                'org'    => $d->organization,
                'year'   => $d->year,
                'url'    => $d->source_url,
                'region' => $d->region,
            ])->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    // ════════════════════════════════════════════════════════════════════════
    // MISTRAL AI — Contextual advice layer
    // ════════════════════════════════════════════════════════════════════════

    private function callMistralForAdvice(array $data): ?string
    {
        $apiKey = config('services.mistral.key', env('MISTRAL_API_KEY'));
        if (blank($apiKey)) return null;

        $prompt = <<<PROMPT
You are a trauma-informed digital safety advisor for Southeast Asia.
Based on the following anonymous incident, write 2-3 sentences of empathetic,
practical guidance. Do NOT give legal advice. Do NOT name medications.
Do NOT minimise the user's experience. Address them in second person ("you").

Category: {$data['category']}
Country: {$data['country']}
Severity: {$data['health_impact_level']}
Description: {$data['description']}

Respond with 2-3 plain sentences only. No headers, no markdown, no lists.
Start with: "🤖 Mistral notes:"
PROMPT;

        try {
            $response = Http::withToken($apiKey)
                ->timeout(8)
                ->post('https://api.mistral.ai/v1/chat/completions', [
                    'model'       => 'mistral-small-latest',
                    'messages'    => [['role' => 'user', 'content' => $prompt]],
                    'max_tokens'  => 200,
                    'temperature' => 0.3,
                ]);

            if ($response->failed()) {
                Log::warning('Mistral advice non-200', ['status' => $response->status()]);
                return null;
            }

            return trim($response->json('choices.0.message.content', ''));
        } catch (\Throwable $e) {
            Log::warning('Mistral advice failed', ['err' => $e->getMessage()]);
            return null;
        }
    }

    // ════════════════════════════════════════════════════════════════════════
    // HELPERS
    // ════════════════════════════════════════════════════════════════════════

    private function categoryLabel(string $key): string
    {
        return match ($key) {
            'phishing'         => 'Phishing & Account Takeover',
            'investment'       => 'Investment Fraud',
            'romance'          => 'Romance Scam',
            'radicalization'   => 'Online Radicalization',
            'money_laundering' => 'Money Laundering',
            default            => 'Other Digital Threat',
        };
    }
}
