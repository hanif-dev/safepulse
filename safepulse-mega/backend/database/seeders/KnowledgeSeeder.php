<?php

namespace Database\Seeders;

use App\Models\KnowledgeDocument;
use Illuminate\Database\Seeder;

/**
 * Seeds the knowledge base with trusted-source references.
 * Run: php artisan db:seed --class=KnowledgeSeeder
 */
class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            // ── Counter-Radicalization / PCVE ──────────────────────────────
            [
                'title'        => 'Recent Developments of Anti-Government Threats',
                'source'       => 'ICCT',
                'organization' => 'International Centre for Counter-Terrorism (NGO)',
                'topic'        => 'radicalization',
                'region'       => 'Europe',
                'language'     => 'en',
                'year'         => 2026,
                'source_url'   => 'https://icct.nl/sites/default/files/2026-03/2026%2003%2009%20Rekawek%20et%20al%20-%20Recent%20Developments%20of%20Anti-Government%20Threats%20%281%29_0.pdf',
                'description'  => 'ICCT analysis of contemporary anti-government extremist trends and recruitment pathways.',
            ],
            [
                'title'        => 'BNPT Strategy on Counter-Extremism (Indonesia)',
                'source'       => 'BNPT',
                'organization' => 'Government — Indonesia',
                'topic'        => 'radicalization',
                'region'       => 'Indonesia',
                'language'     => 'id',
                'year'         => 2024,
                'source_url'   => 'https://bnpt.go.id',
                'description'  => 'National counter-terrorism agency framework for prevention and community engagement.',
            ],
            [
                'title'        => 'SIT Journal: Extremism Studies',
                'source'       => 'SIT Journal',
                'organization' => 'Academic — Indonesia',
                'topic'        => 'radicalization',
                'region'       => 'Indonesia',
                'language'     => 'id',
                'year'         => 2023,
                'source_url'   => 'https://sitjournal.com/sitj/article/view/66/50',
                'description'  => 'Peer-reviewed analysis of online extremism vectors in Indonesian communities.',
            ],
            [
                'title'        => 'The Weaponization of AI: Terrorism & Warfare',
                'source'       => 'Turkish Military Academy (TMMM)',
                'organization' => 'Government — Turkey',
                'topic'        => 'radicalization',
                'region'       => 'Global',
                'language'     => 'en',
                'year'         => 2023,
                'source_url'   => 'https://www.tmmm.tsk.tr/publication/researches/21-TheWeaponizationofAI-TheNextStageofTerrorismandWarfare.pdf',
                'description'  => 'Government research on AI-enabled radicalisation and operational threats.',
            ],

            // ── Phishing & Account Takeover ────────────────────────────────
            [
                'title'        => 'INTERPOL Online Scam Trends Report',
                'source'       => 'INTERPOL',
                'organization' => 'INTERPOL (Lyon, France)',
                'topic'        => 'phishing',
                'region'       => 'Global',
                'language'     => 'en',
                'year'         => 2024,
                'source_url'   => 'https://www.interpol.int/Crimes/Financial-crime/Online-scams',
                'description'  => 'Global trends in phishing, smishing, and credential harvesting across regions.',
            ],
            [
                'title'        => 'BSSN Indonesia: Cyber Threat Landscape',
                'source'       => 'BSSN',
                'organization' => 'Government — Indonesia',
                'topic'        => 'phishing',
                'region'       => 'Indonesia',
                'language'     => 'id',
                'year'         => 2024,
                'source_url'   => 'https://bssn.go.id',
                'description'  => 'National cyber agency annual report on Indonesian phishing & credential-theft incidents.',
            ],
            [
                'title'        => 'ANSSI SecNumCloud Reference Framework',
                'source'       => 'ANSSI',
                'organization' => 'Government — France',
                'topic'        => 'phishing',
                'region'       => 'Europe',
                'language'     => 'fr',
                'year'         => 2023,
                'source_url'   => 'https://www.ssi.gouv.fr/administration/qualifications/prestataires-de-services-de-confiance-qualifies/secnumcloud/',
                'description'  => 'French national cybersecurity agency standards for citizen-grade digital safety.',
            ],

            // ── Investment Fraud ───────────────────────────────────────────
            [
                'title'        => 'OJK Satgas Waspada Investasi Annual Report',
                'source'       => 'OJK',
                'organization' => 'Government — Indonesia',
                'topic'        => 'investment',
                'region'       => 'Indonesia',
                'language'     => 'id',
                'year'         => 2024,
                'source_url'   => 'https://sikapiuangmu.ojk.go.id',
                'description'  => 'Annual data on illegal investment schemes (robot trading, binary options, pyramid).',
            ],
            [
                'title'        => 'UNODC: Casino Scam Compounds in Southeast Asia',
                'source'       => 'UNODC',
                'organization' => 'United Nations',
                'topic'        => 'investment',
                'region'       => 'ASEAN',
                'language'     => 'en',
                'year'         => 2023,
                'source_url'   => 'https://www.unodc.org',
                'description'  => 'Investigation of pig-butchering operations in Myanmar, Cambodia, Laos border regions.',
            ],

            // ── Money Laundering ───────────────────────────────────────────
            [
                'title'        => 'PPATK Annual Report: Money Mule Patterns',
                'source'       => 'PPATK',
                'organization' => 'Government — Indonesia',
                'topic'        => 'money_laundering',
                'region'       => 'Indonesia',
                'language'     => 'id',
                'year'         => 2023,
                'source_url'   => 'https://ppatk.go.id',
                'description'  => 'Indonesian financial intelligence unit analysis of mule recruitment & layered laundering.',
            ],
            [
                'title'        => 'FATF Mutual Evaluation: Indonesia 2023',
                'source'       => 'FATF',
                'organization' => 'Financial Action Task Force',
                'topic'        => 'money_laundering',
                'region'       => 'Indonesia',
                'language'     => 'en',
                'year'         => 2023,
                'source_url'   => 'https://www.fatf-gafi.org',
                'description'  => 'International AML compliance evaluation with Indonesian system findings.',
            ],

            // ── Romance Scam ───────────────────────────────────────────────
            [
                'title'        => 'GASO Survivor Research: Romance Scam Psychology',
                'source'       => 'GASO',
                'organization' => 'Global Anti-Scam Organisation (NGO)',
                'topic'        => 'romance',
                'region'       => 'Global',
                'language'     => 'en',
                'year'         => 2024,
                'source_url'   => 'https://www.globalantiscam.org',
                'description'  => 'Survivor-led research on pig-butchering and romance fraud trauma patterns.',
            ],
            [
                'title'        => 'Into The Light Indonesia: Cyber-Trauma Support',
                'source'       => 'Into The Light',
                'organization' => 'NGO — Indonesia',
                'topic'        => 'romance',
                'region'       => 'Indonesia',
                'language'     => 'id',
                'year'         => 2024,
                'source_url'   => 'https://intothelightid.org',
                'description'  => 'Mental health support framework for digital crime survivors.',
            ],

            // ── Trauma-informed curriculum (own research) ──────────────────
            [
                'title'        => 'Trauma-Informed Curriculum Model for Digital Resilience & PCVE',
                'source'       => 'SafePulse Research',
                'organization' => 'Academic — Indonesia',
                'topic'        => 'other',
                'region'       => 'ASEAN',
                'language'     => 'en',
                'year'         => 2026,
                'source_url'   => null,
                'description'  => 'Four-track curriculum covering ten crime domains (phishing, romance scam, trafficking, money laundering, CSAM, cyberbullying, gang recruitment, migrant worker protection, civic conflict). Three-tier public-health prevention framework: primary (workshops), secondary (BNPT referral), tertiary (survivor support).',
            ],
        ];

        foreach ($docs as $doc) {
            KnowledgeDocument::updateOrCreate(
                ['title' => $doc['title']],
                array_merge(['is_active' => true], $doc)
            );
        }

        $this->command->info('✓ Seeded ' . count($docs) . ' knowledge documents.');
    }
}
