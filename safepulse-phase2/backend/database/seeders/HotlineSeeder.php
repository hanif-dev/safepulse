<?php

namespace Database\Seeders;

use App\Models\Hotline;
use Illuminate\Database\Seeder;

class HotlineSeeder extends Seeder
{
    public function run(): void
    {
        $hotlines = [
            // ── INDONESIA ──────────────────────────────────────────────────
            [
                'slug' => 'patrolisiber',
                'name' => 'Bareskrim Patrolisiber (Cyber Crime)',
                'country_iso' => 'ID',
                'contact_channels' => ['url' => 'https://patrolisiber.id/submit-report/'],
                'languages_supported' => ['id', 'en'],
                'availability' => '24_7',
                'domains_served' => ['phishing', 'romance_scam', 'investment_fraud', 'cyberbullying', 'all'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'ojk_157',
                'name' => 'OJK Kontak 157 (Financial Consumer)',
                'country_iso' => 'ID',
                'contact_channels' => [
                    'tel' => '157',
                    'whatsapp' => '+62811-157-157-157',
                    'url' => 'https://kontak157.ojk.go.id',
                    'email' => 'konsumen@ojk.go.id',
                ],
                'languages_supported' => ['id'],
                'availability' => '24_7',
                'availability_note' => 'Phone 24h since 10 Oct 2025',
                'domains_served' => ['investment_fraud', 'phishing', 'money_laundering'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'aduankonten',
                'name' => 'Komdigi AduanKonten',
                'country_iso' => 'ID',
                'contact_channels' => [
                    'url' => 'https://aduankonten.id',
                    'whatsapp' => '+62811-9224-545',
                ],
                'languages_supported' => ['id', 'en'],
                'availability' => 'business_hours',
                'domains_served' => ['phishing', 'investment_fraud', 'cyberbullying', 'csam', 'all'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'sapa_129',
                'name' => 'SAPA 129 (Women & Children Violence)',
                'country_iso' => 'ID',
                'contact_channels' => [
                    'tel' => '129',
                    'whatsapp' => '+62811-129-129',
                ],
                'languages_supported' => ['id'],
                'availability' => '24_7',
                'domains_served' => ['romance_scam', 'csam', 'cyberbullying', 'tppo'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'kemlu_safe_travel',
                'name' => 'Kemlu Safe Travel / Portal Peduli WNI',
                'country_iso' => 'ID',
                'contact_channels' => [
                    'url' => 'https://safetravel.kemlu.go.id',
                    'url_peduli' => 'https://peduliwni.kemlu.go.id',
                ],
                'languages_supported' => ['id', 'en'],
                'availability' => '24_7',
                'availability_note' => 'Includes panic button for citizens abroad',
                'domains_served' => ['tppo', 'migrant_worker'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'sejiwa_119',
                'name' => 'SEJIWA 119 ext 8 (Mental Health)',
                'country_iso' => 'ID',
                'contact_channels' => ['tel' => '119', 'ext' => '8'],
                'languages_supported' => ['id'],
                'availability' => '24_7',
                'domains_served' => ['mental_health'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'lisa_helpline',
                'name' => 'LISA Suicide Prevention Helpline (Bali Bersama Bisa)',
                'country_iso' => 'ID',
                'contact_channels' => ['tel' => '+62-811-3855-472'],
                'languages_supported' => ['id', 'en'],
                'availability' => '24_7',
                'domains_served' => ['mental_health'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'bp2mi_hotline',
                'name' => 'BP2MI (Indonesian Migrant Worker Protection)',
                'country_iso' => 'ID',
                'contact_channels' => [
                    'tel' => '+62-21-2924-4800',
                    'url' => 'https://bp2mi.go.id',
                ],
                'languages_supported' => ['id'],
                'availability' => 'business_hours',
                'domains_served' => ['tppo', 'migrant_worker'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
            [
                'slug' => 'ppatk',
                'name' => 'PPATK (Financial Intelligence Unit)',
                'country_iso' => 'ID',
                'contact_channels' => [
                    'url' => 'https://ppatk.go.id',
                    'tel' => '+62-21-3850-455',
                ],
                'languages_supported' => ['id', 'en'],
                'availability' => 'business_hours',
                'availability_note' => 'STR via bank within 3 business days per UU 8/2010',
                'domains_served' => ['money_laundering'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],

            // ── MALAYSIA ──────────────────────────────────────────────────
            [
                'slug' => 'nsrc_997',
                'name' => 'National Scam Response Centre (NSRC 997)',
                'country_iso' => 'MY',
                'contact_channels' => ['tel' => '997'],
                'languages_supported' => ['ms', 'en'],
                'availability' => '24_7',
                'availability_note' => '24/7 since 3 March 2026',
                'domains_served' => ['phishing', 'investment_fraud', 'romance_scam'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],

            // ── SINGAPORE ─────────────────────────────────────────────────
            [
                'slug' => 'scam_alert_sg',
                'name' => 'ScamShield Singapore',
                'country_iso' => 'SG',
                'contact_channels' => [
                    'tel' => '1799',
                    'url' => 'https://www.scamalert.sg',
                ],
                'languages_supported' => ['en', 'zh', 'ms', 'ta'],
                'availability' => '24_7',
                'domains_served' => ['phishing', 'investment_fraud', 'romance_scam'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],

            // ── THAILAND ──────────────────────────────────────────────────
            [
                'slug' => 'ccib_1441',
                'name' => 'Cybercrime Investigation Bureau Thailand (1441)',
                'country_iso' => 'TH',
                'contact_channels' => ['tel' => '1441'],
                'languages_supported' => ['th', 'en'],
                'availability' => '24_7',
                'domains_served' => ['phishing', 'investment_fraud', 'romance_scam'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],

            // ── CAMBODIA (for trafficked Indonesians) ─────────────────────
            [
                'slug' => 'kbri_phnom_penh',
                'name' => 'KBRI Phnom Penh (Indonesian Embassy)',
                'country_iso' => 'KH',
                'contact_channels' => [
                    'tel' => '+855-23-217-934',
                    'emergency' => '+855-12-810-005',
                ],
                'languages_supported' => ['id', 'en', 'km'],
                'availability' => '24_7',
                'availability_note' => 'Emergency line for trafficked Indonesians',
                'domains_served' => ['tppo', 'migrant_worker'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],

            // ── MYANMAR ───────────────────────────────────────────────────
            [
                'slug' => 'kbri_yangon',
                'name' => 'KBRI Yangon (Indonesian Embassy Myanmar)',
                'country_iso' => 'MM',
                'contact_channels' => [
                    'tel' => '+95-1-254-465',
                    'emergency' => '+95-9-450-014-300',
                ],
                'languages_supported' => ['id', 'en'],
                'availability' => '24_7',
                'domains_served' => ['tppo', 'migrant_worker'],
                'verified' => true,
                'verified_at' => '2026-05-01',
            ],
        ];

        foreach ($hotlines as $h) {
            Hotline::updateOrCreate(['slug' => $h['slug']], $h);
        }

        $this->command->info('Seeded ' . count($hotlines) . ' hotlines.');
    }
}
