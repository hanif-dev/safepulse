<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Categories yang valid (harus cocok dengan filter di frontend i18n):
 *   scam | radicalization | money_laundering | digital_resilience | youth_peace
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=ArticleSeeder --force
 *
 * Atau fresh dari nol:
 *   php artisan migrate:fresh --seed --force
 */
class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus artikel lama agar tidak duplikat
        DB::table('articles')->truncate();

        foreach ($this->articles() as $article) {
            DB::table('articles')->insert(array_merge($article, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ ' . DB::table('articles')->count() . ' articles seeded.');
    }

    private function articles(): array
    {
        return [

            // ═══════════════════════════════════════════════════════
            // ENGLISH — 8 articles
            // ═══════════════════════════════════════════════════════

            [
                'slug'          => 'phishing-sms-southeast-asia-2024',
                'title'         => 'How SMS Phishing Scams Are Targeting Millions Across Southeast Asia',
                'language'      => 'en',
                'category'      => 'scam',          // ← matches frontend filter
                'summary'       => 'Smishing — phishing via SMS — has surged across Indonesia, Philippines, and Vietnam. Fraudsters impersonate banks and government agencies to steal credentials. Learn the five most common templates and how to protect yourself in under two minutes.',
                'body_markdown' => <<<'MD'
## What Is Smishing?

Smishing combines **SMS** and **phishing**: criminals send text messages that appear to come from trusted institutions — your bank, a courier company, or a government tax authority — then trick you into clicking a malicious link or calling a fake hotline.

## The Five Most Common Templates in SEA

1. **"Your account is suspended"** — Urgent message claiming your bank account will be closed unless you verify immediately via a link.
2. **"Package held at customs"** — Fake courier message demanding a small fee payment, leading to a card-harvesting page.
3. **"Tax refund available"** — Impersonating the national tax authority with a link to "claim" your refund.
4. **"One-time password (OTP) verification"** — Asking you to share an OTP you just received, enabling an account takeover.
5. **"Congratulations, you've won"** — Lottery or prize fraud directing victims to surrender personal and financial details.

## Red Flags to Spot Immediately

- The sender number is a regular mobile number, not a registered short code
- The URL uses a free domain (`.xyz`, `.tk`, `.top`) or a misspelled brand name
- The message creates extreme urgency: "within 24 hours", "immediately", "right now"
- Grammar, spelling, or formatting errors inconsistent with a professional institution

## What to Do If You Receive a Suspicious SMS

1. **Do not click the link.** Copy it and paste into SafePulse's Scam Checker.
2. **Call your institution directly** using the number on the back of your card or their official website — never the number in the SMS.
3. **Report the number** to your national cybersecurity agency (BSSN in Indonesia, DICT in the Philippines).
4. **Block and delete** the message.

## Why Smishing Works

Our brains process text messages differently from emails. SMS creates a sense of intimacy and immediacy. Criminals exploit this by crafting messages that feel personal and urgent.

> **Remember:** Legitimate banks and government agencies will **never** ask for your OTP, PIN, or full password via SMS.
MD,
                'published_at'  => now()->subDays(3),
            ],

            [
                'slug'          => 'pig-butchering-investment-scam-explained',
                'title'         => '"Pig Butchering" Investment Fraud: How Criminal Networks Operate in Southeast Asia',
                'language'      => 'en',
                'category'      => 'scam',
                'summary'       => 'Pig-butchering scams blend romance and fake crypto investing, costing victims an average of USD 45,000. Criminal syndicates operate from compounds in Myanmar, Cambodia, and Laos. This guide exposes the full playbook from first contact to disappearing funds.',
                'body_markdown' => <<<'MD'
## What Is a Pig-Butchering Scam?

The term originates from the Chinese phrase *shā zhū pán* (杀猪盘) — "fattening the pig before slaughter." Victims are cultivated over weeks or months before being defrauded of significant sums through fake investment platforms.

## The Seven Stages of the Scam

### Stage 1 – Random Contact
You receive a message on WhatsApp, Telegram, or LinkedIn from an attractive stranger who "messaged the wrong number."

### Stage 2 – Rapport Building
The scammer invests days or weeks building a genuine-feeling relationship. They share photos (stolen from real people) and discuss your family, interests, and goals.

### Stage 3 – The Investment Mention
Casually, the scammer mentions they've been doing incredibly well in cryptocurrency trading, crediting a family mentor.

### Stage 4 – The Invitation
You're invited to try the platform with a small amount. The fake platform shows immediate, spectacular gains.

### Stage 5 – Escalation
Encouraged by "profits," victims invest more. Friends and family are sometimes pressured to join.

### Stage 6 – The Withdrawal Block
When you attempt to withdraw, the platform imposes taxes, insurance fees, or compliance charges that must be paid first — but paying generates further fees.

### Stage 7 – Disappearance
The platform vanishes, the contact goes silent, and the funds are unrecoverable.

## Who Are the Perpetrators?

Research by the UN identifies massive fraud compounds in Myanmar's Shan State, Cambodia, and border regions of Laos. Many "workers" are themselves trafficking victims forced to scam under threat of violence.

## Protecting Yourself

- Verify any investment platform with your national financial regulator before depositing
- Be deeply suspicious of investment opportunities introduced by new online contacts
- Check the platform's domain registration date — scam sites are often less than 6 months old
- Never invest money you cannot afford to lose entirely
MD,
                'published_at'  => now()->subDays(7),
            ],

            [
                'slug'          => 'online-radicalization-gaming-platforms',
                'title'         => 'How Extremist Groups Recruit Youth Through Gaming Platforms and Discord',
                'language'      => 'en',
                'category'      => 'radicalization',
                'summary'       => 'Online gaming communities have become a primary recruitment channel for extremist groups. Discord servers, in-game chats, and streaming platforms expose youth to radicalising content gradually. Parents and educators need updated strategies to recognise early warning signs.',
                'body_markdown' => <<<'MD'
## The Gaming-to-Radicalisation Pipeline

Extremist recruiters have learned that gaming platforms offer several advantages over traditional social media: younger audiences, reduced moderation, a sense of in-group identity, and immersive communication tools that make sustained contact easy.

## How Recruitment Happens

### Step 1: Entry Through Gaming Communities
Recruiters join popular multiplayer games and gaming Discord servers, presenting themselves as skilled and friendly players. They build genuine gaming friendships over weeks.

### Step 2: Gradual Ideological Introduction
Once trust is established, conversations shift. Political grievances, "us vs them" narratives, and conspiratorial ideas are introduced subtly — often disguised as jokes or memes.

### Step 3: Private Channels
The target is invited to smaller, private servers where content becomes progressively more extreme. These spaces are designed to feel exclusive and special.

### Step 4: Real-world Connections
Some pathways lead to in-person meetings or requests to take "action" to defend the group's ideology.

## Warning Signs for Parents and Educators

- Sudden secrecy about online activity and who they're talking to
- Use of new jargon or symbols from unfamiliar online communities
- Expression of strong "us vs them" language about ethnic, religious, or political groups
- Withdrawal from existing friends and increasing time with new unknown online contacts
- Justifying violence against specific groups as "necessary" or "deserved"

## What Works

Research consistently shows that **open, non-judgmental conversation** is the most effective prevention tool. Contact your national counter-extremism program if you have serious concerns — many offer confidential family support.
MD,
                'published_at'  => now()->subDays(12),
            ],

            [
                'slug'          => 'money-mule-networks-sea-youth',
                'title'         => 'Money Mule Networks: How Young People Are Recruited Into Financial Crime',
                'language'      => 'en',
                'category'      => 'money_laundering',
                'summary'       => 'Fraudsters recruit teenagers and university students as money mules through fake job ads and social media. Participants face serious criminal charges even without knowing the full scheme. Understanding the recruitment tactics can prevent life-altering legal consequences.',
                'body_markdown' => <<<'MD'
## What Is a Money Mule?

A money mule is a person who receives and transfers illegally obtained funds, acting as a financial intermediary for criminal networks. In Southeast Asia, organised crime groups increasingly target young people aged 17–30.

## How You Get Recruited

### Fake Job Advertisements
Ads appear on job boards and Instagram promising easy income — "financial account manager" or "payment processor." The job involves receiving transfers and forwarding them, minus a commission.

### Social Media and Dating Apps
Romantic interests or social media connections may ask you to receive money "temporarily" and forward it, using stories about account problems or international banking restrictions.

### Selling Accounts
Criminal networks buy dormant bank accounts or SIM cards from young people. Sellers may not realise their account will be used for fraud.

## The Legal Reality

In Indonesia (Law No. 8/2010), Malaysia (AMLA 2001), and Singapore (CDSA), participating in money laundering carries penalties of **5–20 years imprisonment** — even if you did not know the origin of the funds.

## Red Flags of Mule Recruitment

- Offered a percentage to "receive and forward" payments with no other duties
- Asked to open a new bank account and hand over the card and PIN
- Instructions to keep the arrangement secret from family

## If You Have Already Participated

Stop all activity immediately. Contact a lawyer before speaking to police. Voluntary disclosure often results in more lenient treatment than being caught through investigation.
MD,
                'published_at'  => now()->subDays(18),
            ],

            [
                'slug'          => 'romance-scam-mental-health-recovery',
                'title'         => 'Romance Scams and the Hidden Mental Health Crisis in Southeast Asia',
                'language'      => 'en',
                'category'      => 'scam',
                'summary'       => 'Beyond financial loss, romance scam survivors face grief, shame, and PTSD. Southeast Asia reported over 28,000 cases in 2023. This article addresses the psychological impact, barriers to seeking help, and evidence-based pathways to recovery.',
                'body_markdown' => <<<'MD'
## The Psychological Wound No One Talks About

When news covers romance scams, it focuses on financial loss. The psychological damage is often deeper and longer-lasting. Victims describe mourning a relationship that felt entirely real while simultaneously feeling ashamed for being deceived.

## What Survivors Experience

### Grief
The relationship — even though manufactured — generated real emotions. Losing it triggers genuine grief: loss of a future, of intimacy, of trust.

### Shame and Self-Blame
"How could I be so stupid?" is the most common phrase reported by survivors. This shame prevents disclosure and delays recovery.

### PTSD Symptoms
Intrusive thoughts, hypervigilance about future relationships, and emotional numbness are documented in clinical research.

### Social Withdrawal
Fear of judgment from family and friends — especially in communities where financial decisions carry family honour implications — isolates survivors precisely when they most need support.

## Evidence-Based Recovery Steps

1. **Break all contact** with the scammer immediately
2. **Tell one trusted person** — isolation prolongs suffering
3. **Seek a trauma-informed counsellor** — not all therapists are trained in financial fraud grief
4. **Join a peer support community** — GASO operates active SEA support groups
5. **Report to authorities** — filing a report provides psychological closure for many survivors

## To Friends and Family of Survivors

Avoid "I told you so." These are engineered scams designed by professional criminal organisations. The shame belongs to the perpetrators.
MD,
                'published_at'  => now()->subDays(22),
            ],

            [
                'slug'          => 'digital-literacy-seniors-sea',
                'title'         => 'Digital Literacy for Seniors: Protecting Older Adults from Online Scams',
                'language'      => 'en',
                'category'      => 'digital_resilience',
                'summary'       => 'Adults over 60 lose disproportionately to online scams — not because they are less intelligent, but because digital safety education has not kept pace with adoption. This guide provides practical strategies for older adults and their families.',
                'body_markdown' => <<<'MD'
## Why Seniors Are Disproportionately Targeted

Older adults are targeted because they often have greater financial assets, higher trust in institutional communication, and less exposure to current criminal tactics — not because of lesser intelligence.

## The Most Common Scams Targeting Seniors

### Grandparent Scam
A caller claims to be a grandchild in trouble — arrested, hospitalised, or stranded abroad — and urgently needs money sent before contacting other family members.

### Government Impersonation
Calls claiming to be from tax authority or immigration enforcement, threatening arrest unless immediate payment is made.

### Tech Support Fraud
A pop-up or call claims your computer has a virus. The "technician" gains remote access and steals banking credentials.

## Practical Strategies

**For seniors:**
- Establish a "family code word" to verify emergency calls are genuine
- Never allow remote access to your computer from an unsolicited caller
- No legitimate government agency demands payment by gift card

**For families:**
- Have open, non-condescending conversations about scam tactics
- Set up two-factor authentication on important accounts together
- Create a family agreement: "If anyone asks you to send money urgently, call [family member] first"
MD,
                'published_at'  => now()->subDays(28),
            ],

            [
                'slug'          => 'cryptocurrency-scam-eight-red-flags',
                'title'         => 'Eight Cryptocurrency Scam Red Flags Every Investor Must Know',
                'language'      => 'en',
                'category'      => 'scam',
                'summary'       => 'Crypto fraud cost Southeast Asian investors over USD 3.2 billion in 2023. Most scams share identifiable warning signs that appear before money is lost. This evidence-based checklist can be completed in five minutes before any investment decision.',
                'body_markdown' => <<<'MD'
## Eight Red Flags — Check All Before Investing

### 🚩 1. Guaranteed Returns
No legitimate investment offers guaranteed profits. "10–30% monthly returns with zero risk" is mathematically impossible in any regulated market.

### 🚩 2. Unregistered Platform
Check your national financial regulator. Indonesia: OJK (ojk.go.id). Malaysia: SC (sc.com.my). Thailand: SEC (sec.or.th). If the platform is not listed, do not invest.

### 🚩 3. Pressure to Recruit Others
If your returns depend on recruiting new investors, you are likely in a pyramid or Ponzi scheme.

### 🚩 4. No Verifiable Team
Fake photos, LinkedIn profiles with no history, or anonymous "founders" are serious warning signs.

### 🚩 5. Platform Created Less Than 12 Months Ago
Check domain registration date at whois.domaintools.com. Scam platforms are created, used briefly, then abandoned.

### 🚩 6. Withdrawal Fees Before Withdrawal
Legitimate platforms deduct fees from withdrawals — they never require you to deposit additional funds as "tax" or "insurance" first.

### 🚩 7. Introduced by a New Online Contact
If someone you met online introduced you to the platform, the probability of fraud is extremely high.

### 🚩 8. Extraordinary Testimonials
Fake reviews, screenshots of massive profits, and celebrity endorsements (usually fabricated) are standard crypto fraud marketing.

## Before You Invest: Three Checks

1. Search "[platform name] + scam" and read community reports
2. Verify registration with your national financial authority
3. Start with an amount you can afford to lose completely — then try to withdraw it before investing more
MD,
                'published_at'  => now()->subDays(35),
            ],

            [
                'slug'          => 'youth-digital-resilience-five-habits',
                'title'         => 'Five Habits That Build Digital Resilience for Young People in Southeast Asia',
                'language'      => 'en',
                'category'      => 'youth_peace',
                'summary'       => 'Young people can reclaim control of their digital lives through five evidence-backed habits that reduce scam exposure, manipulation risk, and algorithmic radicalisation — without giving up the platforms they love.',
                'body_markdown' => <<<'MD'
## Social Media Is an Environment, Not Just an App

The platforms you use are built by teams of engineers whose job is to keep you engaged as long as possible. Understanding this changes how you interact with content.

## Five Habits That Build Real Digital Resilience

### Habit 1: Pause Before You Share or Click
Give yourself three seconds. Ask: *Why am I being shown this? Who benefits if I click, share, or react?* This simple pause interrupts automatic responses that scammers rely on.

### Habit 2: Diversify Your Information Sources
If your entire understanding of a topic comes from one platform's algorithm, you are seeing a filtered reality. Deliberately seek out perspectives that challenge your existing views.

### Habit 3: Audit Your Connections Quarterly
Every three months, review who you follow. Remove accounts that consistently make you feel anxious, angry, or inferior. This is digital hygiene, not avoidance.

### Habit 4: Separate Emotion from Action
Scams and radicalising content are designed to trigger strong emotions and then immediately offer an action to take. If you feel a strong emotion followed by an urgent request, wait 24 hours before acting.

### Habit 5: Practise Healthy Scepticism About "Exclusive" Communities
Online groups that present themselves as special and require increasing loyalty share structural similarities with both scam operations and extremist recruitment.

## Your Privacy Is Your Security

- Use different passwords for every account (a password manager makes this easy)
- Enable two-factor authentication on all important accounts
- Review app permissions monthly
MD,
                'published_at'  => now()->subDays(42),
            ],

            // ═══════════════════════════════════════════════════════
            // INDONESIAN — 7 articles
            // ═══════════════════════════════════════════════════════

            [
                'slug'          => 'modus-penipuan-whatsapp-indonesia-2024',
                'title'         => 'Modus Penipuan WhatsApp Terbaru di Indonesia: Kenali Sebelum Terlambat',
                'language'      => 'id',
                'category'      => 'scam',
                'summary'       => 'WhatsApp menjadi saluran penipuan nomor satu di Indonesia dengan 12 juta laporan pada 2023. Penipu menyamar sebagai bank, kurir paket, hingga pegawai pemerintah. Pelajari lima modus terbaru dan cara melindungi keluarga kamu.',
                'body_markdown' => <<<'MD'
## Mengapa WhatsApp Jadi Ladang Subur Penipu?

Indonesia adalah negara pengguna WhatsApp terbesar di Asia Tenggara. Dengan lebih dari 120 juta pengguna aktif, platform ini menjadi target utama penjahat siber yang memanfaatkan kepercayaan tinggi masyarakat terhadap komunikasi personal.

## Lima Modus Terkini yang Wajib Kamu Tahu

### 1. Modus "Admin Bank"
Penipu mengaku sebagai admin bank dan mengirim pesan bahwa rekening kamu akan dibekukan. Mereka meminta verifikasi data melalui tautan palsu yang sangat mirip dengan situs bank asli.

### 2. Modus Kurir Paket
Pesan mengklaim ada paket tertahan karena biaya pengiriman belum dibayar. Korban diarahkan ke tautan palsu untuk pembayaran yang sebenarnya mencuri data kartu kredit.

### 3. Modus QRIS Palsu
Pelaku mengirimkan gambar QRIS yang sudah dimodifikasi. Saat discan, pembayaran masuk ke rekening penipu, bukan merchant yang sah.

### 4. Modus Lowongan Kerja
Tawaran kerja dengan gaji menggiurkan dari perusahaan ternama. Korban diminta membayar biaya pelatihan atau menyerahkan data KTP dan rekening bank.

### 5. Modus Hadiah Undian
"Selamat! Nomor kamu terpilih mendapat hadiah Rp 50 juta." Korban diminta membayar biaya administrasi untuk mencairkan hadiah yang tidak pernah ada.

## Tanda Bahaya yang Harus Langsung Dicurigai

- Nomor pengirim adalah nomor HP biasa, bukan nomor resmi perusahaan
- Terdapat tekanan waktu: "segera", "dalam 24 jam", "jangan sampai terlambat"
- Tautan menggunakan domain mencurigakan bukan domain resmi bank atau perusahaan
- Meminta OTP, PIN, atau kata sandi — **bank resmi tidak pernah meminta ini**

## Langkah Tepat Saat Menerima Pesan Mencurigakan

1. **Jangan klik tautan** dalam pesan tersebut
2. **Hubungi institusi langsung** melalui nomor resmi di kartu atau website mereka
3. **Laporkan** ke **patrolisiber.id** atau **laporan.ojk.go.id**
4. **Bagikan** informasi ini ke grup keluarga — edukasi komunitas adalah perlindungan terbaik

> **Ingat:** Penipu sering menyamar dengan sangat meyakinkan. Selalu verifikasi melalui saluran resmi sebelum mengambil tindakan apapun.
MD,
                'published_at'  => now()->subDays(5),
            ],

            [
                'slug'          => 'investasi-bodong-robot-trading-indonesia',
                'title'         => 'Waspada Investasi Bodong Robot Trading: Ribuan Korban, Triliunan Rupiah Melayang',
                'language'      => 'id',
                'category'      => 'scam',
                'summary'       => 'Penipuan robot trading menelan korban lebih dari Rp 9 triliun di Indonesia sepanjang 2021–2023. Platform ilegal menjanjikan keuntungan 10–30% per bulan tanpa risiko. Kenali cara kerja skema ini dan tips memverifikasi legalitas investasi.',
                'body_markdown' => <<<'MD'
## Apa Itu Robot Trading Bodong?

Robot trading adalah program komputer yang mengeksekusi transaksi perdagangan secara otomatis. Namun banyak "robot trading" yang ditawarkan kepada publik di Indonesia adalah kedok untuk skema Ponzi.

## Cara Kerja Penipuan Ini

### Fase 1: Perekrutan
Anggota direkrut melalui media sosial atau seminar online. Mereka dijanjikan keuntungan pasif 5–30% per bulan dari "perdagangan otomatis yang sudah terbukti."

### Fase 2: Keuntungan Awal yang Menggiurkan
Investor pertama menerima "keuntungan" yang sebenarnya bersumber dari uang investor berikutnya — bukan dari perdagangan nyata.

### Fase 3: Eskalasi
Peserta yang puas merekrut keluarga dan teman. Total dana yang terkumpul membengkak, sementara pengelola menarik keuntungan besar.

### Fase 4: Kolaps
Platform mendadak menutup akses penarikan, menutup website, dan pengelola menghilang.

## Cara Memverifikasi Legalitas Investasi

1. **Cek OJK:** Kunjungi ojk.go.id dan cari nama perusahaan di daftar izin resmi
2. **Cek Bappebti:** Untuk platform kripto, cek bappebti.go.id
3. **Google nama platform + "scam" atau "bodong"**
4. **Tanya: Boleh audit laporan keuangan?** Investasi sah terbuka untuk audit

## Jika Sudah Menjadi Korban

Hubungi OJK di 157 atau email konsumen@ojk.go.id. Kumpulkan semua bukti: tangkapan layar, transfer bank, dan percakapan untuk laporan ke Bareskrim Polri.
MD,
                'published_at'  => now()->subDays(10),
            ],

            [
                'slug'          => 'radikalisasi-online-remaja-media-sosial',
                'title'         => 'Radikalisasi Online Remaja: Bagaimana Algoritma Media Sosial Memperburuk Risiko',
                'language'      => 'id',
                'category'      => 'radicalization',
                'summary'       => 'Algoritma media sosial memperkuat konten ekstremis karena tingginya engagement. Remaja Indonesia usia 13–22 tahun paling rentan terpapar. Panduan ini membantu orang tua dan guru mengenali tanda awal radikalisasi dan langkah intervensi yang tepat.',
                'body_markdown' => <<<'MD'
## Bagaimana Algoritma Menjadi Masalah

Platform media sosial dirancang untuk memaksimalkan waktu yang dihabiskan pengguna. Konten yang memicu emosi kuat mendapat lebih banyak interaksi, sehingga algoritma mendistribusikannya lebih luas — termasuk konten ekstremis.

## Jalur Radikalisasi Digital yang Umum

### Konten Kebencian Berbungkus Dakwah
Konten yang menggunakan retorika keagamaan untuk mempromosikan kebencian, terasa seperti ceramah biasa di awal namun secara bertahap mendorong pandangan ekstrem.

### Komunitas Gaming Tertutup
Grup Discord dan komunitas gaming digunakan untuk rekrutmen. Topik awalnya soal game, namun secara gradual bergeser ke isu ideologis.

### Grup Telegram Eksklusif
Kelompok yang mengaku memiliki "kebenaran tersembunyi" dan mengundang anggota ke lingkaran yang semakin privat dan ekstrem.

## Tanda Peringatan yang Perlu Diperhatikan

- Menarik diri dari teman lama dan keluarga tanpa alasan jelas
- Menggunakan istilah atau simbol baru yang tidak familiar
- Menyatakan bahwa kekerasan terhadap kelompok tertentu adalah hal yang "wajib"
- Merahasiakan aktivitas online secara defensif

## Langkah Intervensi yang Tepat

1. **Jangan konfrontasi langsung** — ini mendorong remaja lebih jauh ke dalam kelompok
2. **Bangun komunikasi terbuka** tanpa penghakiman
3. **Hubungi BNPT** (bnpt.go.id) untuk konsultasi rahasia jika situasinya mengkhawatirkan
MD,
                'published_at'  => now()->subDays(16),
            ],

            [
                'slug'          => 'pencucian-uang-digital-sea-indonesia',
                'title'         => 'Pencucian Uang Digital di Era Kripto: Kenali Modusnya, Hindari Keterlibatan',
                'language'      => 'id',
                'category'      => 'money_laundering',
                'summary'       => 'Teknologi kripto dan dompet digital telah membuka jalur baru pencucian uang yang sulit dilacak. Jaringan money mule merekrut anak muda melalui iklan kerja palsu. Pelajari modus terkini dan cara melindungi diri dari keterlibatan tanpa sadar.',
                'body_markdown' => <<<'MD'
## Apa yang Dimaksud Pencucian Uang Digital?

Pencucian uang adalah proses menyamarkan asal-usul dana hasil kejahatan sehingga terlihat seperti penghasilan sah. Di era digital, proses ini memanfaatkan pertukaran kripto tanpa KYC, mixer kripto, NFT, dan akun bank atas nama orang lain.

## Modus yang Paling Aktif di Indonesia

### Jaringan Money Mule Digital
Penipu merekrut individu melalui iklan kerja palsu untuk menerima dan meneruskan dana. Rekrutan menyangka mereka bekerja sebagai "asisten keuangan," namun sebenarnya menjadi perantara kriminal.

### Jual Beli Rekening
Rekening bank yang tidak aktif dibeli dari pemiliknya. Penjual mungkin hanya menerima ratusan ribu rupiah, namun rekening mereka digunakan untuk transaksi bernilai miliaran.

### Toko Online Palsu
Toko marketplace fiktif mencatat penjualan barang yang tidak pernah ada untuk menciptakan catatan pendapatan yang terlihat sah.

## Konsekuensi Hukum di Indonesia

Berdasarkan **UU No. 8 Tahun 2010**, keterlibatan dalam pencucian uang dapat dikenakan hukuman penjara hingga **20 tahun** dan denda hingga **Rp 10 miliar** — meski tanpa pengetahuan penuh tentang asal usul dana.

## Tanda Bahaya yang Harus Diwaspadai

- Diminta menerima transfer ke rekening pribadi lalu meneruskannya dengan potongan komisi
- Ditawari bayaran untuk membuka rekening bank baru atas nama sendiri
- Instruksi untuk merahasiakan transaksi dari keluarga atau pihak bank

## Jika Sudah Terlibat

Hubungi **PPATK** di ppatk.go.id. Konsultasikan dengan pengacara sebelum berbicara kepada pihak berwenang — pelaporan sukarela sering menghasilkan penanganan yang lebih ringan.
MD,
                'published_at'  => now()->subDays(24),
            ],

            [
                'slug'          => 'literasi-digital-lansia-indonesia',
                'title'         => 'Lindungi Orang Tua dan Lansia dari Kejahatan Siber: Panduan Praktis untuk Keluarga',
                'language'      => 'id',
                'category'      => 'digital_resilience',
                'summary'       => 'Lansia kehilangan Rp 2,4 triliun akibat kejahatan siber di Indonesia pada 2022. Bukan karena kurang cerdas, tapi karena taktik penipu terus berkembang lebih cepat dari edukasi. Panduan ini membantu keluarga melindungi orang tua dengan bermartabat.',
                'body_markdown' => <<<'MD'
## Mengapa Lansia Menjadi Target Utama

Ada kesalahpahaman umum bahwa lansia menjadi target karena kurang cerdas. Kenyataannya: mereka memiliki aset finansial lebih besar, tingkat kepercayaan terhadap figur otoritas lebih tinggi, dan kurang terpapar taktik penipuan terkini yang berkembang sangat cepat.

## Penipuan yang Paling Sering Menyasar Lansia di Indonesia

### Penipuan "Cucu dalam Masalah"
Telepon mengklaim sebagai cucu atau anggota keluarga yang dalam keadaan darurat. Mereka membutuhkan uang segera dikirim sebelum menghubungi anggota keluarga lain.

### Penipuan Pegawai Bank Palsu
"Petugas bank" menelepon dan memberitahu ada transaksi mencurigakan. Korban diminta memindahkan uang ke "rekening aman sementara" milik penipu.

### Penipuan Undian Berhadiah
"Selamat, Bapak/Ibu terpilih sebagai pemenang." Biaya administrasi dipungut sebelum hadiah yang tidak pernah ada dikirim.

## Strategi Perlindungan Keluarga yang Efektif

**Buat kesepakatan keluarga:**
- Tetapkan "kata kode keluarga" yang hanya diketahui anggota keluarga inti
- Sepakati: "Jika ada yang minta uang mendesak, konfirmasi ke [nama anggota keluarga] dulu"

**Komunikasi berkelanjutan:**
- Ceritakan modus penipuan terbaru secara santai — bukan ceramah menakut-nakuti
- Pastikan mereka tahu: "Tidak ada yang akan marah jika kamu menutup telepon lebih dulu"

## Jika Sudah Terjadi

Hubungi bank segera untuk memblokir transaksi lebih lanjut. Laporkan ke **laporan.ojk.go.id**. Yang terpenting: dukung secara emosional — rasa malu tidak boleh menghalangi pemulihan.
MD,
                'published_at'  => now()->subDays(30),
            ],

            [
                'slug'          => 'ketahanan-digital-pemuda-sea',
                'title'         => 'Ketahanan Digital untuk Pemuda: Lima Kebiasaan yang Melindungi di Dunia Online',
                'language'      => 'id',
                'category'      => 'youth_peace',
                'summary'       => 'Pemuda usia 15–29 tahun adalah kelompok paling aktif sekaligus paling rentan terhadap ancaman digital. Lima kebiasaan sederhana ini terbukti secara riset mengurangi risiko penipuan dan manipulasi online tanpa harus meninggalkan platform favorit.',
                'body_markdown' => <<<'MD'
## Kenyataan yang Perlu Kamu Tahu

Media sosial dan aplikasi yang kamu gunakan tidak dirancang untuk kebahagiaanmu — mereka dirancang untuk memaksimalkan waktu yang kamu habiskan di dalamnya. Memahami ini adalah langkah pertama membangun ketahanan digital yang nyata.

## Lima Kebiasaan Ketahanan Digital

### Kebiasaan 1: Jeda Tiga Detik Sebelum Klik atau Bagikan
Penipu dan konten manipulatif dirancang untuk membuat kamu bertindak sebelum berpikir. Tiga detik jeda sudah cukup untuk mengaktifkan bagian otak yang berpikir kritis.

### Kebiasaan 2: Verifikasi Sebelum Percaya
Gunakan **turnbackhoax.id**, **cekfakta.com**, atau **Google gambar terbalik** untuk foto yang mencurigakan sebelum mempercayai atau membagikan informasi.

### Kebiasaan 3: Audit Privasi Bulanan
Setiap bulan, cek aplikasi mana yang punya akses ke kamera, mikrofon, dan lokasi kamu. Review siapa saja yang bisa melihat postingan dan informasi profilmu.

### Kebiasaan 4: Pisahkan Emosi dari Tindakan Finansial
Jika sebuah interaksi online membuatmu merasa sangat senang atau sangat cemas — dan diikuti dengan permintaan uang atau data — tunggu minimal 24 jam sebelum melakukan apapun. Waktu adalah musuh penipu.

### Kebiasaan 5: Ceritakan Pengalaman Mencurigakan
Rasa malu sering membuat orang diam ketika hampir tertipu. Namun berbagi pengalaman melindungi orang lain. Setiap cerita yang dibagikan adalah perisai bagi seseorang yang belum pernah mendengarnya.

## Ingat Ini

Menjadi korban penipuan bukan tanda kebodohan — ini tanda bahwa kamu adalah manusia yang berinteraksi dengan sistem yang dirancang mengeksploitasi kepercayaan. Ketahanan digital adalah tentang menjadi **bijak dan waspada tanpa kehilangan kepercayaan pada dunia**.
MD,
                'published_at'  => now()->subDays(38),
            ],

        ]; // end array
    } // end articles()
} // end class
