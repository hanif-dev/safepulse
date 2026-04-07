<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            // ── English articles ──────────────────────────────────────────────
            [
                'slug'     => 'how-phishing-scams-target-sea-youth',
                'title'    => 'How Phishing Scams Are Targeting Youth Across Southeast Asia',
                'language' => 'en',
                'category' => 'scam',
                'summary'  => 'A surge in phishing campaigns is exploiting social media platforms popular among young people in Indonesia, Philippines, and Vietnam. Learn to identify the red flags.',
                'body_markdown' => <<<'MD'
# How Phishing Scams Are Targeting Youth Across Southeast Asia

## The Scale of the Problem

Cybercriminals are increasingly targeting young people aged 15–30 across Southeast Asia. A 2023 study found that nearly **1 in 3** young internet users in the region had encountered a phishing attempt in the past 12 months.

## Common Attack Vectors

- **Fake scholarship offers** delivered via Instagram DMs
- **Counterfeit e-commerce sites** mimicking Tokopedia, Shopee, and Lazada
- **Job scam WhatsApp groups** promising high pay for minimal work
- **Romantic lures** on dating apps leading to investment fraud

## Red Flags to Watch For

1. The sender's email domain doesn't match the company name exactly
2. Messages create excessive urgency ("Your account will be closed in 24 hours!")
3. Links redirect through multiple short-URL services
4. Grammar and spelling are inconsistent

## How to Protect Yourself

Enable multi-factor authentication on every account. Use a password manager. Never enter payment details on a site you navigated to via a link—always type the URL directly.

## Reporting

Report suspected phishing to your national cybersecurity agency. In Indonesia: **BSSN** (bssn.go.id). In the Philippines: **DICT**.
MD,
                'published_at' => now()->subDays(5),
            ],

            [
                'slug'     => 'investment-scam-pig-butchering-explained',
                'title'    => '"Pig Butchering" Investment Scams: A Growing Threat',
                'language' => 'en',
                'category' => 'scam',
                'summary'  => 'Pig-butchering scams combine romance fraud with fake investment platforms, costing victims an average of USD 50,000. Here is how they work and how to escape.',
                'body_markdown' => <<<'MD'
# "Pig Butchering" Investment Scams

## What Is Pig Butchering?

The term comes from a Chinese phrase meaning to "fatten the pig before slaughter." Fraudsters build a trusting relationship over weeks or months—often romantic—then slowly introduce a supposed investment opportunity, typically cryptocurrency trading on a fake platform.

## Anatomy of the Scam

1. **Contact** – Stranger adds you on WhatsApp, Telegram, or LinkedIn
2. **Rapport building** – Weeks of friendly, sometimes flirtatious conversation
3. **Introduction** – They casually mention huge investment returns they are getting
4. **Invitation** – They invite you to join their "exclusive" trading platform
5. **Small wins** – You deposit a small amount; the platform shows fake profits
6. **Escalation** – You invest more; friends and family may be pressured too
7. **Slaughter** – When you try to withdraw, fees appear. Your money is gone.

## Warning Signs

- An unknown contact initiates conversation on a non-social app
- They pivot to investment talk within 2–4 weeks
- The investment platform is not listed on any regulatory exchange
- Withdrawals are blocked pending "taxes" or "insurance fees"

## If You Are a Victim

Do not pay additional fees—they will not release your funds. Contact your bank immediately. Report to your national financial regulator and local police.
MD,
                'published_at' => now()->subDays(12),
            ],

            [
                'slug'     => 'online-radicalization-youth-prevention',
                'title'    => 'Recognizing Online Radicalization: A Guide for Parents and Educators',
                'language' => 'en',
                'category' => 'radicalization',
                'summary'  => 'Extremist groups increasingly recruit through gaming platforms and social media. This guide helps parents and teachers spot early warning signs.',
                'body_markdown' => <<<'MD'
# Recognizing Online Radicalization

## Why the Internet?

Online spaces allow extremist groups to recruit at scale with minimal risk. Gaming communities, private Discord servers, and niche forums create echo chambers where radical ideas are normalized gradually.

## Warning Signs

### Behavioral Changes
- Social withdrawal; new secretive online friendships
- Expressing strong "us vs them" language
- Sudden interest in extremist symbols or language
- Expressing that violence against a group is "justified"

### Digital Footprint Signs
- Consuming content from flagged channels
- Using new encrypted messaging apps with unfamiliar contacts
- Participating in private forums with extremist content

## What Parents Can Do

1. **Talk openly** – Regular conversations reduce shame and secrecy
2. **Understand their digital world** – Ask about games, streamers, communities they follow
3. **Counter-narrative** – Introduce diverse perspectives and critical media literacy
4. **Seek support** – Contact youth counselling services if behavior is severe

## Resources

Many countries have "off-ramp" programs to help individuals disengage from extremist communities without legal consequences. Reach out to your national deradicalization program confidentially.
MD,
                'published_at' => now()->subDays(18),
            ],

            [
                'slug'     => 'money-laundering-digital-age-sea',
                'title'    => 'Digital Money Laundering in Southeast Asia: Methods and Red Flags',
                'language' => 'en',
                'category' => 'money_laundering',
                'summary'  => 'From crypto mixing to money mule networks, this article exposes how illicit funds are cleaned through digital channels in the region.',
                'body_markdown' => <<<'MD'
# Digital Money Laundering in Southeast Asia

## The Digital Shift

Traditional money laundering relied on cash-intensive businesses. Today, digital payment platforms, cryptocurrencies, and e-commerce provide new channels for concealing the origins of illicit funds.

## Common Methods

### Cryptocurrency Mixing
Funds are split into many small transactions across wallets to obscure their trail.

### Money Mule Networks
Innocent individuals—often recruited via fake job ads—receive and forward funds, unknowingly acting as intermediaries.

### Virtual Asset Layering
Illicit funds purchase NFTs or in-game assets which are then resold, appearing as legitimate income.

### Trade-Based Laundering
Over- or under-invoicing cross-border transactions to move value across jurisdictions.

## Red Flags for Individuals

- Being offered a percentage to "receive and forward" payments
- Job ads asking for your bank account details upfront
- Requests to buy gift cards or cryptocurrency and send codes to a third party

## If You Are Approached

Participating in money mule activity is a serious criminal offense in all Southeast Asian nations, even if you did not know the money was illicit. Decline and report.
MD,
                'published_at' => now()->subDays(25),
            ],

            [
                'slug'     => 'digital-resilience-building-habits',
                'title'    => '10 Daily Habits That Build Digital Resilience',
                'language' => 'en',
                'category' => 'digital_resilience',
                'summary'  => 'Digital resilience is not about fear—it is about building daily habits that keep you and your community safer online. Start with these ten practices.',
                'body_markdown' => <<<'MD'
# 10 Daily Habits That Build Digital Resilience

**1. Pause Before You Click**
Take three seconds before clicking any link. Ask: was I expecting this message?

**2. Use a Password Manager**
Unique, complex passwords for every account—without needing to remember them.

**3. Enable MFA Everywhere**
Multi-factor authentication blocks 99% of automated attacks.

**4. Keep Software Updated**
Software updates patch vulnerabilities that attackers actively exploit.

**5. Verify Before You Share**
Run headlines through fact-checking sites before forwarding to family groups.

**6. Use Encrypted Messaging**
Signal or WhatsApp for sensitive conversations.

**7. Audit App Permissions Monthly**
Remove microphone, location, and camera access from apps that don't need them.

**8. Back Up Critical Data**
Follow the 3-2-1 rule: 3 copies, 2 different media, 1 offsite.

**9. Talk About Scams Openly**
Sharing experiences reduces stigma and protects others.

**10. Report What You See**
Every report helps authorities map threats and protect more people.
MD,
                'published_at' => now()->subDays(30),
            ],

            [
                'slug'     => 'youth-peace-hub-social-media-conflict',
                'title'    => 'How Social Media Algorithms Fuel Youth Conflict — And What We Can Do',
                'language' => 'en',
                'category' => 'youth_peace',
                'summary'  => 'Recommendation algorithms optimize for engagement, often amplifying outrage and divisive content. Here is how young people can reclaim control of their digital diet.',
                'body_markdown' => <<<'MD'
# How Social Media Algorithms Fuel Youth Conflict

## The Engagement Trap

Platforms maximize engagement—and outrage keeps users scrolling longer than joy. The result: algorithms disproportionately surface divisive, emotionally charged content that deepens polarization between communities.

## The Youth Impact

Longitudinal studies link heavy algorithmic social media use among teenagers to increased anxiety, reduced empathy across political/ethnic lines, and higher susceptibility to extremist recruitment.

## Reclaiming Your Feed

- **Follow chronologically** – Switch away from algorithmic feeds where possible
- **Diversify intentionally** – Follow accounts from different backgrounds and viewpoints
- **Set time limits** – Use built-in screen-time tools to cap daily usage
- **Mute, don't block** – Blocking escalates conflict; muting reduces it quietly
- **Create, don't consume** – Active creation shifts your relationship with platforms

## For Educators

Digital literacy curriculum should include algorithm awareness—teaching students *why* they see what they see, not just *what* they see.
MD,
                'published_at' => now()->subDays(40),
            ],

            [
                'slug'     => 'romance-scam-recovery-mental-health',
                'title'    => 'Romance Scam Recovery: The Mental Health Dimension',
                'language' => 'en',
                'category' => 'scam',
                'summary'  => 'Victims of romance fraud face compounded trauma: financial loss and the grief of a relationship that never truly existed. This article addresses recovery pathways.',
                'body_markdown' => <<<'MD'
# Romance Scam Recovery: The Mental Health Dimension

## Beyond Financial Loss

Romance scam victims lose on average USD 10,000—but many report the psychological damage is far greater than the financial. The grief is real: mourning a relationship that seemed genuine, while processing shame and betrayal simultaneously.

## Common Psychological Impacts

- **Shame and self-blame** – "How could I have been so stupid?"
- **Trust damage** – Difficulty forming new relationships
- **PTSD symptoms** – Intrusive thoughts, hypervigilance, avoidance
- **Social withdrawal** – Fear of judgment from family and friends

## Recovery Steps

### Immediate
1. Cut all contact with the scammer
2. Secure your financial accounts
3. Tell someone you trust

### Medium-Term
4. Seek a mental health professional experienced in trauma and grief
5. Connect with peer support groups (Scam Survivors, GASO)
6. File a police report—not just for recovery but for your own closure

### Long-Term
7. Gradually rebuild trust through low-stakes social connection
8. Consider your experience as expertise—many survivors become advocates

## You Are Not Alone

Romance scams affect people of all ages, education levels, and genders. They are engineered by professional criminal operations. The shame belongs to them, not you.
MD,
                'published_at' => now()->subDays(50),
            ],

            [
                'slug'     => 'ngo-government-digital-safety-toolkit',
                'title'    => 'SafePulse NGO & Government Toolkit: Deploying Community Awareness',
                'language' => 'en',
                'category' => 'digital_resilience',
                'summary'  => 'A practical guide for NGOs and government agencies to use SafePulse resources in community workshops, school programs, and public awareness campaigns.',
                'body_markdown' => <<<'MD'
# SafePulse NGO & Government Toolkit

## Purpose

This toolkit helps civil-society organizations and government agencies deploy evidence-based anti-scam and digital safety education at community scale.

## Available Resources

### Workshop Materials
- **Scam Awareness Workshop** (90 min) – Suitable for community halls, schools, and workplace sessions. Includes facilitator guide, participant handouts, and a pre/post quiz.
- **Youth Digital Resilience Program** (6-session curriculum) – Designed for ages 14–25; integrates with existing civics or IT curricula.

### Digital Assets
- Infographic poster series (print-ready PDF, A1 and A3)
- Social media card pack (1080×1080, Facebook/Instagram optimized)
- Short-form video scripts (30s, 60s, 90s) for TikTok and Reels

### Data Integration
NGOs with data-collection capacity can submit anonymized incident data via the SafePulse API to contribute to the regional threat map.

## Requesting Access

Contact our Partnerships team via the Contact page. Government partners may request white-labelled materials and a customized API data feed.

## Impact Measurement

We provide pre/post survey templates and a reporting dashboard to help you demonstrate program impact to funders.
MD,
                'published_at' => now()->subDays(60),
            ],

            // ── Bahasa Indonesia articles ──────────────────────────────────────
            [
                'slug'     => 'kenali-penipuan-online-indonesia',
                'title'    => 'Kenali Modus Penipuan Online yang Marak di Indonesia',
                'language' => 'id',
                'category' => 'scam',
                'summary'  => 'Dari lowongan kerja palsu hingga investasi bodong, inilah modus-modus penipuan digital yang paling sering menjerat warga Indonesia dan cara menghindarinya.',
                'body_markdown' => <<<'MD'
# Kenali Modus Penipuan Online yang Marak di Indonesia

## Gambaran Umum

Indonesia tercatat sebagai salah satu negara dengan tingkat penipuan digital tertinggi di Asia Tenggara. Berdasarkan data BSSN, laporan insiden siber meningkat lebih dari **60%** antara 2021 dan 2023.

## Modus yang Paling Umum

### 1. Lowongan Kerja Palsu
Iklan pekerjaan bergaji tinggi untuk pekerjaan yang tidak jelas dibagikan melalui WhatsApp dan Telegram. Korban diminta membayar "biaya pelatihan" atau menyerahkan data KTP.

### 2. Investasi Bodong (Robot Trading / Kripto Palsu)
Platform trading palsu memperlihatkan keuntungan fantastis di awal. Saat korban mencoba menarik dana, akun mereka dibekukan dengan berbagai alasan.

### 3. Phishing via SMS/WhatsApp
Pesan berpura-pura berasal dari bank atau instansi pemerintah, meminta korban mengklik tautan dan memasukkan data perbankan.

### 4. Penipuan Belanja Online
Toko palsu di marketplace atau media sosial menerima pembayaran, lalu mengirim barang palsu—atau tidak mengirim sama sekali.

## Cara Melindungi Diri

- Verifikasi identitas penjual atau perekrut secara mandiri
- Jangan pernah klik tautan dari pengirim tidak dikenal
- Aktifkan notifikasi transaksi perbankan secara real-time
- Laporkan dugaan penipuan ke **laporan.ojk.go.id** atau **patrolisiber.id**

## Jika Anda Menjadi Korban

Segera hubungi bank Anda untuk memblokir rekening. Simpan semua bukti percakapan dan transfer. Buat laporan polisi—meskipun pemulihan dana tidak pasti, laporan Anda membantu melindungi orang lain.
MD,
                'published_at' => now()->subDays(8),
            ],

            [
                'slug'     => 'radikalisasi-online-remaja-indonesia',
                'title'    => 'Ancaman Radikalisasi Online bagi Remaja Indonesia',
                'language' => 'id',
                'category' => 'radicalization',
                'summary'  => 'Kelompok ekstremis semakin aktif merekrut remaja melalui media sosial dan platform gaming. Panduan ini membantu orang tua dan guru mengenali tanda-tanda awal.',
                'body_markdown' => <<<'MD'
# Ancaman Radikalisasi Online bagi Remaja Indonesia

## Mengapa Remaja Rentan?

Remaja berada dalam fase pencarian identitas. Kelompok ekstremis mengeksploitasi kebutuhan ini dengan menawarkan rasa kebersamaan, tujuan, dan kejelasan ideologis—sering kali melalui konten yang tampak keren dan relevan.

## Platform yang Sering Digunakan

- **Telegram** – Grup tertutup dengan ribuan anggota
- **Discord** – Server gaming yang terlihat biasa namun menyisipkan konten radikal
- **YouTube & TikTok** – Konten berbobot ideologis dikemas sebagai opini umum
- **Forum terenkripsi** – Akses via tautan undangan pribadi

## Tanda Peringatan Dini

Perubahan perilaku yang perlu diperhatikan:
- Menarik diri dari pertemanan lama dan keluarga
- Menggunakan bahasa "kami vs mereka" yang semakin tajam
- Membenarkan kekerasan terhadap kelompok tertentu
- Merahasiakan aktivitas online secara defensif

## Langkah untuk Orang Tua

1. **Bangun komunikasi terbuka** tanpa penghakiman
2. **Ikut terlibat** dalam dunia digital anak Anda—tanya tentang game dan kreator favorit mereka
3. **Ajak berdiskusi** tentang berita dan isu sosial secara kritis
4. **Cari bantuan profesional** jika tanda-tanda mengkhawatirkan

## Sumber Bantuan

Hubungi **BNPT (Badan Nasional Penanggulangan Terorisme)** untuk konsultasi rahasia terkait kekhawatiran radikalisasi.
MD,
                'published_at' => now()->subDays(15),
            ],

            [
                'slug'     => 'ketahanan-digital-kebiasaan-sehari-hari',
                'title'    => '8 Kebiasaan Harian untuk Memperkuat Ketahanan Digital Anda',
                'language' => 'id',
                'category' => 'digital_resilience',
                'summary'  => 'Keamanan digital bukan soal teknologi—melainkan kebiasaan. Delapan kebiasaan sederhana ini bisa melindungi Anda dan keluarga dari ancaman siber.',
                'body_markdown' => <<<'MD'
# 8 Kebiasaan Harian untuk Memperkuat Ketahanan Digital

**1. Jeda Sebelum Klik**
Beri diri Anda tiga detik sebelum mengklik tautan. Apakah Anda memang mengharapkan pesan ini?

**2. Gunakan Kata Sandi Unik**
Satu kata sandi untuk semua akun = satu titik kegagalan. Gunakan aplikasi pengelola kata sandi.

**3. Aktifkan Verifikasi Dua Langkah**
Autentikasi dua faktor memblokir sebagian besar serangan otomatis.

**4. Perbarui Aplikasi Secara Rutin**
Pembaruan menambal celah keamanan yang aktif dimanfaatkan peretas.

**5. Periksa Fakta Sebelum Meneruskan**
Gunakan **turnbackhoax.id** atau **cekfakta.com** sebelum meneruskan berita ke grup WhatsApp keluarga.

**6. Audit Izin Aplikasi**
Periksa setiap bulan aplikasi mana yang memiliki akses ke kamera, mikrofon, dan lokasi Anda.

**7. Cadangkan Data Penting**
Simpan salinan dokumen penting di tempat terpisah—cloud dan fisik.

**8. Ceritakan Pengalaman**
Berbagi cerita tentang upaya penipuan mengurangi rasa malu dan melindungi orang-orang di sekitar Anda.
MD,
                'published_at' => now()->subDays(35),
            ],

            [
                'slug'     => 'penipuan-romantis-pemulihan',
                'title'    => 'Penipuan Romantis: Dampak Psikologis dan Jalan Menuju Pemulihan',
                'language' => 'id',
                'category' => 'scam',
                'summary'  => 'Korban penipuan romantis tidak hanya kehilangan uang—mereka juga berduka atas hubungan yang tidak pernah nyata. Artikel ini membahas langkah-langkah pemulihan.',
                'body_markdown' => <<<'MD'
# Penipuan Romantis: Dampak Psikologis dan Jalan Menuju Pemulihan

## Lebih dari Sekadar Kerugian Finansial

Korban penipuan romantis sering kali melaporkan bahwa luka psikologis jauh lebih dalam daripada kerugian materi. Ada proses berduka yang nyata: kehilangan seseorang yang terasa nyata, sekaligus menghadapi rasa malu dan pengkhianatan.

## Respons Psikologis yang Umum

- **Menyalahkan diri sendiri** – "Bagaimana saya bisa sebodoh itu?"
- **Kerusakan kepercayaan** – Sulit membuka diri pada hubungan baru
- **Gejala trauma** – Pikiran mengganggu, kewaspadaan berlebihan
- **Penarikan sosial** – Takut dihakimi keluarga dan teman

## Langkah Pemulihan

### Segera
1. Putuskan semua kontak dengan penipu
2. Amankan akun keuangan Anda
3. Ceritakan kepada seseorang yang Anda percaya

### Jangka Menengah
4. Cari dukungan profesional—psikolog atau konselor trauma
5. Bergabung dengan komunitas penyintas online
6. Buat laporan polisi sebagai langkah penutupan diri

### Jangka Panjang
7. Bangun kembali kepercayaan secara perlahan melalui interaksi sosial berisiko rendah
8. Pertimbangkan berbagi pengalaman untuk membantu orang lain

## Anda Tidak Sendiri

Penipuan romantis dirancang oleh jaringan kriminal profesional. Rasa malu itu milik mereka, bukan Anda.
MD,
                'published_at' => now()->subDays(55),
            ],

            [
                'slug'     => 'perdagangan-uang-haram-digital',
                'title'    => 'Pencucian Uang Digital: Kenali Modusnya, Hindari Keterlibatan',
                'language' => 'id',
                'category' => 'money_laundering',
                'summary'  => 'Jaringan "money mule" merekrut korban yang tidak curiga melalui iklan kerja palsu. Ketahui bagaimana modus ini bekerja dan bagaimana melindungi diri Anda.',
                'body_markdown' => <<<'MD'
# Pencucian Uang Digital: Kenali Modusnya

## Apa Itu Money Mule?

"Money mule" adalah individu yang tanpa disadari—atau terpaksa—menerima dan meneruskan dana hasil kejahatan melalui rekening pribadi mereka. Kegiatan ini adalah tindak pidana meskipun pelakunya tidak mengetahui asal usul dana.

## Cara Rekrutmen

- Iklan kerja paruh waktu menjanjikan komisi 5–10% untuk "mentransfer dana klien"
- Kenalan baru di media sosial yang meminta bantuan "menyimpan uang sementara"
- Platform freelance palsu yang meminta detail rekening bank Anda

## Tanda Bahaya

- Tawaran pekerjaan tanpa wawancara atau kualifikasi jelas
- Diminta membuka rekening baru atas nama Anda
- Instruksi untuk merahasiakan aktivitas dari pihak ketiga

## Konsekuensi Hukum

Di Indonesia, keterlibatan dalam pencucian uang dapat dikenai hukuman **hingga 20 tahun penjara** berdasarkan UU No. 8 Tahun 2010, meskipun Anda tidak mengetahui asal dana.

## Jika Anda Sudah Terlibat

Segera hentikan semua aktivitas. Hubungi bank Anda dan laporkan situasi ini kepada **PPATK (Pusat Pelaporan dan Analisis Transaksi Keuangan)** di ppatk.go.id.
MD,
                'published_at' => now()->subDays(45),
            ],
        ];

        foreach ($articles as $article) {
            DB::table('articles')->insert(array_merge($article, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
