<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ArticleSeeder::class);
        $this->seedIncidents();
    }

    private function seedIncidents(): void
    {
        $categories   = ['phishing', 'investment', 'romance', 'radicalization', 'money_laundering', 'other'];
        $countries    = ['Indonesia', 'Philippines', 'Malaysia', 'Vietnam', 'Thailand', 'Singapore', 'Myanmar', 'Cambodia'];
        $ageGroups    = ['under_18', '18_24', '25_34', '35_44', '45_54', '55_64', '65_plus'];
        $impactLevels = ['low', 'medium', 'high'];

        $incidents = [];
        // 80 seeded incidents spread over 14 months for chart variance
        for ($i = 0; $i < 80; $i++) {
            $category = $categories[array_rand($categories)];
            $incidents[] = [
                'category'                => $category,
                'country'                 => $countries[array_rand($countries)],
                'age_group'               => $ageGroups[array_rand($ageGroups)],
                'description'             => $this->fakeDescription($category),
                'health_impact_level'     => $impactLevels[array_rand($impactLevels)],
                'financial_loss_estimate' => rand(0, 1) ? round(rand(50, 50000) + rand(0, 99) / 100, 2) : null,
                'created_at'              => now()->subDays(rand(0, 420)),
                'updated_at'              => now(),
            ];
        }

        DB::table('incidents')->insert($incidents);
    }

    private function fakeDescription(string $category): string
    {
        $descriptions = [
            'phishing'        => 'Received an SMS claiming my bank account was compromised. The link led to a fake banking portal that harvested my credentials.',
            'investment'      => 'Was introduced to a cryptocurrency trading platform via a new contact on Telegram. Deposited funds but could not withdraw. Platform became unresponsive.',
            'romance'         => 'Met someone on a dating app who eventually asked me to invest in their family business overseas. After transferring funds, contact was lost.',
            'radicalization'  => 'Family member started expressing extreme political views after joining a private Telegram group. Content encouraged violence against specific ethnic groups.',
            'money_laundering'=> 'Responded to a freelance job posting asking me to receive and forward payments. Realised later the funds may have originated from illegal activity.',
            'other'           => 'Received unsolicited calls offering fake government grants. Caller requested payment of a processing fee to release the grant.',
        ];
        return $descriptions[$category] ?? 'Suspicious digital activity was encountered and reported.';
    }
}
