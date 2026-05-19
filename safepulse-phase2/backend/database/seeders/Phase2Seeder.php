<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Master seeder for Phase 2.
 *
 * Run: php artisan db:seed --class=Phase2Seeder --force
 */
class Phase2Seeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HotlineSeeder::class,
            LegalAidSeeder::class,
            PFAResourceSeeder::class,
            RecoveryPathwaySeeder::class,
            MigrantEducationSeeder::class,
            CounterNarrativeSeeder::class,
        ]);

        $this->command->info('Phase 2 seeding complete.');
    }
}
