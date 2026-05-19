<?php

namespace Database\Seeders;

use App\Models\MigrantEducationModule;
use Illuminate\Database\Seeder;

/**
 * Pre-Departure Migrant Worker Education curriculum.
 *
 * Aligned with:
 *  - BP2MI Revised PDO Modules 2022
 *  - 2024 Palm Oil Sector module (BP2MI + IOM + Consumer Goods Forum)
 *  - IOM Comprehensive Information and Orientation Programme (CIOP)
 *  - UU 18/2017 Migrant Worker Protection
 *
 * Initial pilot: Indonesia → Malaysia palm oil sector
 * (BP3MI NTB is the largest sending province for this corridor)
 */
class MigrantEducationSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'sequence'    => 1,
                'module_code' => 'M1_VERIFIKASI',
                'destination' => 'MY',
                'sector'      => 'palm_oil',
                'title' => [
                    'en' => 'Module 1: Verifying a Job Offer',
                    'id' => 'Modul 1: Verifikasi Tawaran Kerja',
                    'jv' => 'Modul 1: Verifikasi Tawaran Gawean',
                ],
                'content' => [
                    'id' => "Sebelum berangkat, verifikasi tawaran kerja Anda melalui sistem resmi:\n\n1. Cek nama P3MI (agen) di portal resmi BP2MI: siskotkln.bp2mi.go.id\n2. Pastikan nama Anda terdaftar dalam SISKOTKLN sebelum berangkat\n3. Periksa kontrak kerja sebelum tanda tangan — pastikan ada nama perusahaan, gaji, durasi, hak cuti, biaya yang ditanggung perusahaan\n\n**Tanda Bahaya:**\n- Agen meminta uang muka besar (Rp 5 juta+)\n- Berangkat tanpa visa kerja resmi\n- Nama Anda tidak terdaftar di SISKOTKLN\n- Janji 'gaji 10 juta tanpa pengalaman' di luar negeri",
                    'en' => "Before departing, verify your job offer through official systems:\n\n1. Check the recruitment agency (P3MI) name on the BP2MI portal: siskotkln.bp2mi.go.id\n2. Confirm your name is registered in SISKOTKLN before departure\n3. Review the work contract before signing — ensure company name, salary, duration, leave rights, and company-borne costs are specified\n\n**Red Flags:**\n- Agency requests large advance payment (Rp 5 million+)\n- Departure without a proper work visa\n- Your name not registered in SISKOTKLN\n- Promises of '10-million-rupiah salary with no experience' abroad",
                ],
                'video_urls' => null,
                'questions'  => [
                    [
                        'q'       => 'Apa langkah pertama untuk memverifikasi tawaran kerja luar negeri?',
                        'options' => ['Bayar uang muka', 'Cek P3MI di SISKOTKLN BP2MI', 'Percaya rekomendasi tetangga', 'Langsung berangkat'],
                        'correct' => 1,
                    ],
                ],
                'source' => 'BP2MI Revised PDO Modules 2022; UU 18/2017',
            ],
            [
                'sequence'    => 2,
                'module_code' => 'M2_HAK',
                'destination' => 'MY',
                'sector'      => 'palm_oil',
                'title' => [
                    'en' => 'Module 2: Your Rights as a Migrant Worker',
                    'id' => 'Modul 2: Hak-Hak Anda sebagai Pekerja Migran',
                ],
                'content' => [
                    'id' => "Sebagai pekerja migran Indonesia (PMI), Anda berhak atas:\n\n- **Kontrak kerja tertulis** dalam bahasa yang Anda pahami (bahasa Indonesia atau Inggris)\n- **Gaji minimum** sesuai upah minimum Sabah/Sarawak (untuk sektor sawit)\n- **Memegang paspor Anda sendiri** — paspor TIDAK BOLEH disita oleh majikan\n- **Hari libur mingguan** (minimal 1 hari per minggu)\n- **Akses komunikasi** dengan keluarga\n- **Kebebasan bergerak** di luar jam kerja\n- **Perlindungan dari kekerasan fisik, seksual, dan verbal**\n\n**Jika ada pelanggaran:** hubungi KBRI Kuala Lumpur (+60-3-2116-4000) atau KJRI di kota Anda.",
                    'en' => "As an Indonesian migrant worker (PMI), you have the right to:\n\n- **A written work contract** in a language you understand\n- **Minimum wage** per the Sabah/Sarawak minimum (for palm oil)\n- **Hold your own passport** — passport CANNOT be confiscated by employer\n- **Weekly day off** (minimum 1 day per week)\n- **Communication access** with family\n- **Freedom of movement** outside work hours\n- **Protection from physical, sexual, and verbal violence**\n\n**If rights are violated:** contact KBRI Kuala Lumpur (+60-3-2116-4000) or KJRI in your city.",
                ],
                'questions' => [
                    [
                        'q'       => 'Apakah majikan Anda boleh menyita paspor Anda?',
                        'options' => ['Boleh, untuk keamanan', 'TIDAK BOLEH — itu pelanggaran hukum', 'Hanya 6 bulan pertama', 'Boleh jika tertulis di kontrak'],
                        'correct' => 1,
                    ],
                ],
                'source' => 'UU 18/2017; ILO Migrant Worker Standards; KBRI KL',
            ],
            [
                'sequence'    => 3,
                'module_code' => 'M3_TPPO_SIGNS',
                'destination' => 'MY',
                'sector'      => 'palm_oil',
                'title' => [
                    'en' => 'Module 3: Recognizing Human Trafficking Signs',
                    'id' => 'Modul 3: Mengenali Tanda-Tanda TPPO',
                ],
                'content' => [
                    'id' => "TPPO (Tindak Pidana Perdagangan Orang) sering disamarkan sebagai tawaran kerja. Kenali tanda-tandanya:\n\n**Sebelum berangkat:**\n- Paspor 'dipegang sementara' oleh agen\n- Berangkat melalui jalur tidak resmi (bukan dari KBRI yang terverifikasi)\n- Tidak ada kontrak tertulis atau kontrak tidak boleh dibaca\n- Janji gaji jauh lebih tinggi dari standar pasar\n\n**Saat tiba:**\n- Paspor disita oleh majikan/perekrut\n- Hutang yang tiba-tiba muncul ('biaya transportasi', 'biaya makan')\n- Anda tidak boleh keluar atau menelepon keluarga\n- Pekerjaan berbeda dari yang dijanjikan (misalnya, dijanjikan kerja di toko, ternyata di scam compound)\n\n**Jika Anda atau orang yang Anda kenal mengalami ini:** Hubungi KBRI segera. Anda tidak akan dipenjara karena melapor — Anda korban, bukan pelaku.",
                    'en' => "Human trafficking is often disguised as a job offer. Recognize the signs:\n\n**Before departure:**\n- Passport 'temporarily held' by the agency\n- Departure through unofficial routes (not via verified KBRI channels)\n- No written contract or contract you cannot read\n- Promised salary far above market rate\n\n**On arrival:**\n- Passport confiscated by employer/recruiter\n- Sudden debts appearing ('transport fees', 'food costs')\n- You cannot leave or call family\n- Work different from what was promised (e.g., promised shop work, actually scam compound)\n\n**If you or someone you know experiences this:** Contact KBRI immediately. You will not be jailed for reporting — you are a victim, not a perpetrator.",
                ],
                'questions' => [
                    [
                        'q'       => 'Manakah TANDA KUAT TPPO?',
                        'options' => ['Kontrak resmi 2 tahun', 'Paspor disita majikan setelah tiba', 'Gaji dibayar tepat waktu', 'Bisa berlibur sebulan sekali'],
                        'correct' => 1,
                    ],
                ],
                'source' => 'IJM Indonesia; IOM TPPO Indicators; BP2MI 2022',
            ],
            [
                'sequence'    => 4,
                'module_code' => 'M4_KBRI',
                'destination' => 'MY',
                'sector'      => null,
                'title' => [
                    'en' => 'Module 4: Embassy Emergency Contacts',
                    'id' => 'Modul 4: Kontak Darurat KBRI',
                ],
                'content' => [
                    'id' => "Simpan kontak ini di HP Anda SEBELUM berangkat:\n\n**KBRI Kuala Lumpur**\n- Tel: +60-3-2116-4000\n- Hotline 24 jam: +60-3-2116-4017\n- Email: kbri.kualalumpur@kemlu.go.id\n\n**KJRI Penang**\n- Tel: +60-4-2275162\n\n**KJRI Johor Bahru**\n- Tel: +60-7-2213241\n\n**KJRI Kota Kinabalu (Sabah — penting untuk sektor sawit)**\n- Tel: +60-88-218600\n\n**KJRI Kuching (Sarawak)**\n- Tel: +60-82-456734\n\n**Portal Peduli WNI (Online):**\nhttps://peduliwni.kemlu.go.id\n\n**Aplikasi Safe Travel:**\nDownload di Play Store. Aplikasi memiliki tombol panic untuk situasi darurat.",
                ],
                'questions' => [
                    [
                        'q'       => 'Berapa nomor hotline 24 jam KBRI Kuala Lumpur?',
                        'options' => ['+60-3-2116-4000', '+60-3-2116-4017', '+60-3-2116-4001', 'Tidak ada'],
                        'correct' => 1,
                    ],
                ],
                'source' => 'Kemlu Safe Travel; Portal Peduli WNI',
            ],
            [
                'sequence'    => 5,
                'module_code' => 'M5_SURVIVOR',
                'destination' => 'MY',
                'sector'      => null,
                'title' => [
                    'en' => 'Module 5: Survivor Stories',
                    'id' => 'Modul 5: Cerita Survivor',
                ],
                'content' => [
                    'id' => "Cerita dari WNI yang berhasil pulang. Nama disamarkan, kisah nyata.\n\n**[Cerita 1: Returnee dari Kamboja, 2024]**\nSaya dijanjikan kerja sebagai customer service di Phnom Penh, gaji USD 1.500/bulan. Setelah tiba, paspor saya diambil. Saya dipaksa kerja sebagai scammer 16 jam sehari. Saya kabur, sampai di KBRI Phnom Penh, dan akhirnya dipulangkan oleh Kemlu. Sekarang saya mengingatkan teman-teman: gaji terlalu tinggi tanpa pengalaman = JEBAKAN.\n\n*Catatan: cerita ini dimuat dengan izin survivor.*\n\nLihat selengkapnya di forum SafePulse Community Healing.",
                ],
                'questions' => [],
                'source' => 'IJM Indonesia; Survivor consent collected via standard MoU',
            ],
            [
                'sequence'    => 6,
                'module_code' => 'M6_FINANCIAL',
                'destination' => 'MY',
                'sector'      => null,
                'title' => [
                    'en' => 'Module 6: Financial Literacy Abroad',
                    'id' => 'Modul 6: Literasi Keuangan di Luar Negeri',
                ],
                'content' => [
                    'id' => "Mengelola uang Anda di luar negeri:\n\n**Pengiriman Uang (Remittance):**\n- Gunakan saluran resmi: bank, Western Union, MoneyGram, Wise, Maybank2u, OCBC NISP\n- Hindari 'pengantar uang informal' — uang bisa hilang tanpa jaminan\n- Bandingkan biaya: rata-rata 1-3% untuk pengiriman resmi\n\n**Tabungan:**\n- Buka rekening Indonesia sebelum berangkat\n- Sisihkan minimal 20% gaji untuk tabungan\n- Hindari pinjaman online ilegal di Indonesia (pinjol)\n\n**Hindari Penipuan:**\n- Tidak ada 'investasi pasti untung' yang ditawarkan via WhatsApp\n- Pinjaman dengan bunga > 24%/tahun adalah ilegal\n- Jangan beri kode OTP ke siapa pun — bahkan yang mengaku dari bank",
                ],
                'questions' => [],
                'source' => 'BP2MI Financial Literacy Module 2022; OJK Konsumen',
            ],
            [
                'sequence'    => 7,
                'module_code' => 'M7_VE_AWARE',
                'destination' => 'MY',
                'sector'      => null,
                'title' => [
                    'en' => 'Module 7: Recognizing Recruitment by Extremist Groups',
                    'id' => 'Modul 7: Mengenali Rekrutmen oleh Kelompok Ekstrem',
                ],
                'content' => [
                    'id' => "Modul ini ditambahkan dalam Revisi BP2MI 2022 karena kasus PMI direkrut oleh kelompok ekstrem di luar negeri.\n\n**Tanda-tanda rekrutmen ekstremis:**\n- Ajakan ke 'pertemuan rahasia' di luar lingkaran kerja\n- Pemberian materi yang menyalahkan satu kelompok\n- Permintaan untuk memutus kontak dengan keluarga di Indonesia\n- Janji 'pekerjaan suci' atau 'misi mulia' dengan bayaran tinggi\n- Tekanan untuk pindah ke lokasi yang tidak jelas\n\n**Jika Anda diajak:**\n- Tidak perlu konfrontasi — cukup tolak halus dan jaga jarak\n- Hubungi KBRI atau Patrolisiber Indonesia jika merasa terancam\n- Jangan beritahu perekrut bahwa Anda melapor\n\nHubungi BNPT Family Channel jika anggota keluarga Anda di Indonesia menunjukkan perubahan setelah mendapat pesan dari luar negeri.",
                ],
                'questions' => [],
                'source' => 'BP2MI Revised PDO Modules 2022 (VE awareness addition); BNPT',
            ],
        ];

        foreach ($modules as $m) {
            MigrantEducationModule::updateOrCreate(
                ['module_code' => $m['module_code']],
                [
                    'sequence'           => $m['sequence'],
                    'module_code'        => $m['module_code'],
                    'destination_country'=> $m['destination'],
                    'sector'             => $m['sector'],
                    'title_localized'    => $m['title'],
                    'content_localized'  => $m['content'],
                    'video_urls'         => $m['video_urls'] ?? null,
                    'pre_post_questions' => $m['questions'],
                    'source_attribution' => $m['source'],
                    'published'          => true,
                ]
            );
        }

        $this->command->info('Seeded ' . count($modules) . ' migrant education modules.');
    }
}
