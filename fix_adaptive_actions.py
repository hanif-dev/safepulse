#!/usr/bin/env python3
"""
Fix AdaptiveResponseController::topActionsFor()
Replace translation-key placeholders with hardcoded bilingual text.
"""

filepath = '/workspaces/safepulse/backend/app/Http/Controllers/Api/AdaptiveResponseController.php'

with open(filepath, 'r') as f:
    content = f.read()

# Find and replace the entire topActionsFor method
# We locate it by its unique opening signature
START_MARKER = "    private function topActionsFor(string $role, string $domain, string $locale): array"
END_MARKER = "    private function emergencyHotlines"

start = content.find(START_MARKER)
end   = content.find(END_MARKER)

if start == -1 or end == -1:
    print(f"ERROR: marker not found. start={start}, end={end}")
    exit(1)

new_method = '''    private function topActionsFor(string $role, string $domain, string $locale): array
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

'''

new_content = content[:start] + new_method + content[end:]

with open(filepath, 'w') as f:
    f.write(new_content)

print("Done — topActionsFor replaced with hardcoded text.")
