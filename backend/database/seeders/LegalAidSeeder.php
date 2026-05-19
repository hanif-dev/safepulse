<?php

namespace Database\Seeders;

use App\Models\LegalAidContact;
use Illuminate\Database\Seeder;

class LegalAidSeeder extends Seeder
{
    public function run(): void
    {
        $lbhOffices = [
            ['org' => 'LBH Jakarta',      'province' => 'DKI Jakarta', 'tel' => '+62-21-3145518',  'addr' => 'Jl. Diponegoro No. 74, Menteng, Jakarta Pusat 10320'],
            ['org' => 'LBH Bandung',      'province' => 'Jawa Barat',  'tel' => '+62-22-4218453',  'addr' => 'Jl. Sederhana No. 25, Pasteur, Bandung 40161'],
            ['org' => 'LBH Semarang',     'province' => 'Jawa Tengah', 'tel' => '+62-24-8454838',  'addr' => 'Jl. Parang Kembang IV/19, Semarang 50244'],
            ['org' => 'LBH Yogyakarta',   'province' => 'DI Yogyakarta','tel'=> '+62-274-376335',   'addr' => 'Jl. Agus Salim No. 25, Yogyakarta 55262'],
            ['org' => 'LBH Surabaya',     'province' => 'Jawa Timur',  'tel' => '+62-31-5022273',  'addr' => 'Jl. Kidal No. 6, Surabaya 60131'],
            ['org' => 'LBH Bali',         'province' => 'Bali',        'tel' => '+62-361-223010',  'addr' => 'Jl. Plawa No. 57, Denpasar 80235'],
            ['org' => 'LBH Lampung',      'province' => 'Lampung',     'tel' => '+62-721-485530',  'addr' => 'Jl. Cengkeh No. 86, Bandar Lampung 35114'],
            ['org' => 'LBH Padang',       'province' => 'Sumatera Barat','tel'=>'+62-751-39945',    'addr' => 'Jl. Pekanbaru No. 21, Padang 25171'],
            ['org' => 'LBH Medan',        'province' => 'Sumatera Utara','tel'=>'+62-61-7866811',   'addr' => 'Jl. Hindu No. 12, Medan 20231'],
            ['org' => 'LBH Palembang',    'province' => 'Sumatera Selatan','tel'=>'+62-711-353880', 'addr' => 'Jl. KH. Achmad Dahlan No. 25, Palembang 30135'],
            ['org' => 'LBH Pekanbaru',    'province' => 'Riau',        'tel' => '+62-761-26803',   'addr' => 'Jl. Cempaka No. 19, Pekanbaru 28156'],
            ['org' => 'LBH Banda Aceh',   'province' => 'Aceh',        'tel' => '+62-651-7551356', 'addr' => 'Jl. Tgk. Imuem Lueng Bata, Banda Aceh 23116'],
            ['org' => 'LBH Manado',       'province' => 'Sulawesi Utara','tel'=>'+62-431-862455',   'addr' => 'Jl. 17 Agustus, Manado 95117'],
            ['org' => 'LBH Makassar',     'province' => 'Sulawesi Selatan','tel'=>'+62-411-587623', 'addr' => 'Jl. Pelita Raya VIII, Makassar 90222'],
            ['org' => 'LBH Papua',        'province' => 'Papua',       'tel' => '+62-967-583555',  'addr' => 'Jl. Bhayangkara II, Jayapura 99117'],
            ['org' => 'LBH Pers',         'province' => 'DKI Jakarta', 'tel' => '+62-21-8770930',  'addr' => 'Jl. Kalibata Timur IV D No. 10, Jakarta 12740'],
        ];

        foreach ($lbhOffices as $lbh) {
            LegalAidContact::updateOrCreate(
                ['organization' => $lbh['org']],
                [
                    'organization'    => $lbh['org'],
                    'parent_network'  => 'YLBHI',
                    'province'        => $lbh['province'],
                    'address'         => ['full' => $lbh['addr']],
                    'contact_channels'=> ['tel' => $lbh['tel'], 'url' => 'https://ylbhi.or.id'],
                    'case_types_accepted' => ['scam_recovery', 'phishing', 'tppo', 'cyber_harassment', 'fraud'],
                    'pro_bono'        => true,
                ]
            );
        }

        $this->command->info('Seeded ' . count($lbhOffices) . ' YLBHI LBH offices.');
    }
}
