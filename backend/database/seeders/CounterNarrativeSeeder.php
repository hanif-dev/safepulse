<?php

namespace Database\Seeders;

use App\Models\CounterNarrativeLink;
use Illuminate\Database\Seeder;

/**
 * Counter-narrative content — outbound curated links ONLY.
 * SafePulse hosts NO extremist material.
 *
 * Curated from: Hedayah Counter Narrative Library, PeaceGeneration Indonesia,
 * AMAN Indonesia, YPP (Yayasan Prasasti Perdamaian).
 */
class CounterNarrativeSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'title'          => 'Hedayah Counter Narrative Library',
                'curator'        => 'Hedayah Center (Abu Dhabi)',
                'external_url'   => 'https://hedayah.com/resources/counter-narrative-library/',
                'content_type'   => 'library',
                'target_audience'=> 'practitioner',
                'languages'      => ['en', 'ar'],
                'verified'       => true,
            ],
            [
                'title'          => '12 Basic Values of Peace',
                'curator'        => 'PeaceGeneration Indonesia',
                'external_url'   => 'https://peacegeneration.org',
                'content_type'   => 'curriculum',
                'target_audience'=> 'youth',
                'languages'      => ['id', 'en'],
                'verified'       => true,
            ],
            [
                'title'          => 'Yayasan Prasasti Perdamaian (YPP) — Family Engagement Programs',
                'curator'        => 'YPP — Dete Aliah',
                'external_url'   => 'https://prasasti-perdamaian.org',
                'content_type'   => 'program',
                'target_audience'=> 'family_concerned',
                'languages'      => ['id'],
                'verified'       => true,
            ],
            [
                'title'          => 'AMAN Indonesia — Women & PCVE',
                'curator'        => 'Asian Muslim Action Network Indonesia',
                'external_url'   => 'https://aman-indonesia.org',
                'content_type'   => 'organization',
                'target_audience'=> 'family_concerned',
                'languages'      => ['id', 'en'],
                'verified'       => true,
            ],
            [
                'title'          => 'Yayasan Lingkar Perdamaian (YLP) — Survivor Reintegration',
                'curator'        => 'YLP — Ali Fauzi',
                'external_url'   => 'https://lingkarperdamaian.org',
                'content_type'   => 'program',
                'target_audience'=> 'family_concerned',
                'languages'      => ['id'],
                'verified'       => true,
            ],
            [
                'title'          => 'RAN Local: Family Support Guidelines',
                'curator'        => 'Radicalisation Awareness Network (EU)',
                'external_url'   => 'https://home-affairs.ec.europa.eu/networks/radicalisation-awareness-network-ran_en',
                'content_type'   => 'guide',
                'target_audience'=> 'family_concerned',
                'languages'      => ['en'],
                'verified'       => true,
            ],
        ];

        foreach ($links as $l) {
            CounterNarrativeLink::updateOrCreate(
                ['title' => $l['title']],
                $l
            );
        }

        $this->command->info('Seeded ' . count($links) . ' counter-narrative outbound links.');
    }
}
