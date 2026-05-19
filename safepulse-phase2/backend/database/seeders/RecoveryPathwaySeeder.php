<?php

namespace Database\Seeders;

use App\Models\RecoveryPathway;
use Illuminate\Database\Seeder;

class RecoveryPathwaySeeder extends Seeder
{
    public function run(): void
    {
        $pathways = [
            $this->romanceScamPathway(),
            $this->tppoPathway(),
            $this->phishingPathway(),
            $this->familyRadicalizationPathway(),
        ];

        foreach ($pathways as $p) {
            RecoveryPathway::updateOrCreate(['slug' => $p['slug']], $p);
        }

        $this->command->info('Seeded ' . count($pathways) . ' recovery pathways in 16 languages.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // 1. ROMANCE SCAM
    // ──────────────────────────────────────────────────────────────────────

    private function romanceScamPathway(): array
    {
        return [
            'slug' => 'romance-scam-recovery',
            'crime_domain' => 'romance_scam',
            'title' => [
                'en' => 'Romance Scam Recovery Pathway',
                'id' => 'Jalur Pemulihan dari Penipuan Asmara',
                'fr' => 'Parcours de Rétablissement après une Arnaque Sentimentale',
                'ar' => 'مسار التعافي من احتيال الرومانسية',
                'de' => 'Erholungspfad nach Romance-Scam',
                'es' => 'Itinerario de Recuperación tras Estafa Sentimental',
                'zh' => '情感诈骗康复路径',
                'zh-TW' => '感情詐騙復原路徑',
                'ru' => 'Путь восстановления после романтического мошенничества',
                'ko' => '로맨스 스캠 회복 경로',
                'ja' => 'ロマンス詐欺からの回復ガイド',
                'jv' => 'Dalan Pemulihan saka Apus Tresna',
                'th' => 'เส้นทางการฟื้นตัวจากการหลอกลวงทางความรัก',
                'vi' => 'Lộ trình Phục hồi sau Lừa đảo Tình cảm',
                'tl' => 'Landas ng Pagbangon mula sa Romance Scam',
                'km' => 'ផ្លូវនៃការងើបឡើងវិញពីការបោកប្រាស់ស្នេហា',
            ],
            'summary' => [
                'en' => 'A step-by-step guide grounded in WHO Psychological First Aid (Look-Listen-Link) and survivor-support practice. You did nothing wrong. Recovery is possible.',
                'id' => 'Panduan bertahap berdasarkan Psychological First Aid WHO (Lihat-Dengar-Hubungkan) dan praktik dukungan korban. Anda tidak melakukan kesalahan. Pemulihan mungkin.',
                'fr' => 'Un guide étape par étape ancré dans les Premiers Secours Psychologiques de l\'OMS. Vous n\'avez rien fait de mal. Le rétablissement est possible.',
                'ar' => 'دليل خطوة بخطوة قائم على الإسعافات النفسية الأولية لمنظمة الصحة العالمية. لم تفعل شيئاً خطأ. التعافي ممكن.',
                'de' => 'Eine schrittweise Anleitung, basierend auf WHO Psychological First Aid. Sie haben nichts falsch gemacht. Erholung ist möglich.',
                'es' => 'Una guía paso a paso basada en Primeros Auxilios Psicológicos de la OMS. No hizo nada malo. La recuperación es posible.',
                'zh' => '基于世卫组织心理急救（看-听-连）的逐步指南。您没有做错任何事。康复是可能的。',
                'zh-TW' => '基於世衛組織心理急救的逐步指南。您沒有做錯任何事。復原是可能的。',
                'ru' => 'Пошаговое руководство, основанное на психологической первой помощи ВОЗ. Вы ничего не сделали неправильно. Восстановление возможно.',
                'ko' => 'WHO 심리적 응급 처치를 기반으로 한 단계별 가이드. 당신은 잘못한 것이 없습니다. 회복이 가능합니다.',
                'ja' => 'WHO心理的応急処置に基づくステップバイステップガイド。あなたは何も間違っていません。回復は可能です。',
                'jv' => 'Pandhuan bertahap adhedhasar Psychological First Aid WHO. Sliramu ora salah. Pemulihan bisa.',
                'th' => 'คู่มือทีละขั้นตอนตามหลักการช่วยเหลือทางจิตใจขั้นต้นของ WHO คุณไม่ได้ทำอะไรผิด การฟื้นตัวเป็นไปได้',
                'vi' => 'Hướng dẫn từng bước dựa trên Sơ cứu Tâm lý của WHO. Bạn không làm gì sai. Phục hồi là có thể.',
                'tl' => 'Sunud-sunod na gabay batay sa Psychological First Aid ng WHO. Wala kang ginawang mali. Posible ang pagbangon.',
                'km' => 'មគ្គុទេសក៍មួយជំហានម្ដងៗដោយផ្អែកលើជំនួយផ្លូវចិត្តដំបូងរបស់អង្គការសុខភាពពិភពលោក។ អ្នកមិនបានធ្វើអ្វីខុសទេ។',
            ],
            'milestones' => [
                [
                    'week' => 1,
                    'tasks' => [
                        ['key' => 'block_perpetrator',  'title_key' => 'rp.romance.w1.block'],
                        ['key' => 'preserve_evidence', 'title_key' => 'rp.romance.w1.evidence'],
                        ['key' => 'contact_bank',      'title_key' => 'rp.romance.w1.bank'],
                        ['key' => 'report_ojk_157',    'title_key' => 'rp.romance.w1.ojk'],
                        ['key' => 'patrolisiber',      'title_key' => 'rp.romance.w1.patrolisiber'],
                    ],
                    'emotional_notes_key' => 'rp.romance.w1.notes',
                ],
                [
                    'week' => 2,
                    'tasks' => [
                        ['key' => 'lbh_consult',         'title_key' => 'rp.romance.w2.lbh'],
                        ['key' => 'inform_trusted',      'title_key' => 'rp.romance.w2.trust'],
                    ],
                ],
                [
                    'week' => 4,
                    'tasks' => [
                        ['key' => 'survivor_story',      'title_key' => 'rp.romance.w4.peer'],
                        ['key' => 'audit_remaining',     'title_key' => 'rp.romance.w4.audit'],
                    ],
                ],
                [
                    'week' => 8,
                    'tasks' => [
                        ['key' => 'mentor_match',        'title_key' => 'rp.romance.w8.mentor'],
                        ['key' => 'financial_plan',      'title_key' => 'rp.romance.w8.finance'],
                    ],
                ],
            ],
            'templates' => [
                ['kind' => 'bank_letter_id',   'path' => 'templates/id/bank_freeze_request.docx'],
                ['kind' => 'police_report_id', 'path' => 'templates/id/laporan_polisi.docx'],
                ['kind' => 'lbh_intake_id',    'path' => 'templates/id/lbh_intake.docx'],
            ],
            'hotlines' => [
                ['slug' => 'patrolisiber'], ['slug' => 'ojk_157'],
                ['slug' => 'sapa_129'], ['slug' => 'sejiwa_119'],
            ],
            'published' => true,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // 2. TPPO / Cambodia-Myanmar Return
    // ──────────────────────────────────────────────────────────────────────

    private function tppoPathway(): array
    {
        return [
            'slug' => 'tppo-recovery-cambodia-myanmar',
            'crime_domain' => 'tppo',
            'title' => [
                'en' => 'TPPO Survivor Pathway — Cambodia / Myanmar Return',
                'id' => 'Jalur Pemulihan Korban TPPO — Kepulangan dari Kamboja / Myanmar',
                'fr' => 'Parcours pour Survivants de la Traite — Retour du Cambodge / Myanmar',
                'km' => 'ផ្លូវសម្រាប់ជនរងគ្រោះនៃការជួញដូរ — ត្រឡប់មកវិញពីកម្ពុជា / មីយ៉ាន់ម៉ា',
                'zh' => '人口贩运幸存者康复路径——从柬埔寨/缅甸回国',
                // additional locales added by translation pipeline
            ],
            'summary' => [
                'en' => 'Step-by-step recovery for Indonesians returning from scam compounds. KBRI emergency contact, legal aid, mental health, financial recovery.',
                'id' => 'Pemulihan bertahap untuk WNI pulang dari kompleks scam. Kontak darurat KBRI, bantuan hukum, kesehatan mental, pemulihan finansial.',
            ],
            'milestones' => [
                [
                    'week' => 1,
                    'tasks' => [
                        ['key' => 'kbri_contact',     'title_key' => 'rp.tppo.w1.kbri'],
                        ['key' => 'document_status',  'title_key' => 'rp.tppo.w1.docs'],
                        ['key' => 'safehouse_intake', 'title_key' => 'rp.tppo.w1.safehouse'],
                    ],
                ],
                [
                    'week' => 2,
                    'tasks' => [
                        ['key' => 'medical_screening', 'title_key' => 'rp.tppo.w2.medical'],
                        ['key' => 'trauma_referral',   'title_key' => 'rp.tppo.w2.trauma'],
                    ],
                ],
                [
                    'week' => 4,
                    'tasks' => [
                        ['key' => 'reintegration_plan','title_key' => 'rp.tppo.w4.reintegration'],
                        ['key' => 'family_mediation',  'title_key' => 'rp.tppo.w4.family'],
                    ],
                ],
                [
                    'week' => 8,
                    'tasks' => [
                        ['key' => 'livelihood_support','title_key' => 'rp.tppo.w8.livelihood'],
                        ['key' => 'survivor_network',  'title_key' => 'rp.tppo.w8.network'],
                    ],
                ],
            ],
            'templates' => [
                ['kind' => 'tppo_kbri_intake_id', 'path' => 'templates/id/kbri_intake.docx'],
            ],
            'hotlines' => [
                ['slug' => 'kemlu_safe_travel'], ['slug' => 'kbri_phnom_penh'],
                ['slug' => 'kbri_yangon'], ['slug' => 'bp2mi_hotline'], ['slug' => 'sapa_129'],
            ],
            'published' => true,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // 3. PHISHING (financial loss)
    // ──────────────────────────────────────────────────────────────────────

    private function phishingPathway(): array
    {
        return [
            'slug' => 'phishing-financial-recovery',
            'crime_domain' => 'phishing',
            'title' => [
                'en' => 'Phishing & Banking Fraud Recovery',
                'id' => 'Pemulihan Korban Phishing & Penipuan Perbankan',
                'fr' => 'Récupération après Hameçonnage et Fraude Bancaire',
            ],
            'summary' => [
                'en' => 'Immediate steps to limit financial loss: bank kill-switch, OJK 157, Patrolisiber, PPATK via bank.',
                'id' => 'Langkah segera membatasi kerugian finansial: hentikan akun di bank, OJK 157, Patrolisiber, PPATK via bank.',
            ],
            'milestones' => [
                [
                    'week' => 1,
                    'tasks' => [
                        ['key' => 'change_passwords',  'title_key' => 'rp.phishing.w1.passwords'],
                        ['key' => 'enable_2fa',        'title_key' => 'rp.phishing.w1.2fa'],
                        ['key' => 'bank_freeze',       'title_key' => 'rp.phishing.w1.bank'],
                        ['key' => 'ojk_157',           'title_key' => 'rp.phishing.w1.ojk'],
                    ],
                ],
                [
                    'week' => 2,
                    'tasks' => [
                        ['key' => 'device_scan',       'title_key' => 'rp.phishing.w2.scan'],
                        ['key' => 'credit_review',     'title_key' => 'rp.phishing.w2.credit'],
                    ],
                ],
            ],
            'templates' => [
                ['kind' => 'bank_letter_id',  'path' => 'templates/id/bank_freeze_request.docx'],
            ],
            'hotlines' => [
                ['slug' => 'patrolisiber'], ['slug' => 'ojk_157'], ['slug' => 'aduankonten'],
            ],
            'published' => true,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // 4. Family Radicalization Concern
    // ──────────────────────────────────────────────────────────────────────

    private function familyRadicalizationPathway(): array
    {
        return [
            'slug' => 'family-radicalization-concern',
            'crime_domain' => 'radicalization',
            'title' => [
                'en' => 'If Someone You Love Is Changing — A Family Guide',
                'id' => 'Jika Orang Terkasih Mulai Berubah — Panduan untuk Keluarga',
                'fr' => 'Si un Proche Change — Guide pour la Famille',
            ],
            'summary' => [
                'en' => 'Non-confrontational approach scripts, trusted-figure engagement, BNPT family channel. Do NOT confront directly — it accelerates withdrawal.',
                'id' => 'Skrip pendekatan non-konfrontatif, libatkan tokoh terpercaya, kanal keluarga BNPT. JANGAN konfrontasi langsung — itu mempercepat penarikan diri.',
            ],
            'milestones' => [
                [
                    'week' => 1,
                    'tasks' => [
                        ['key' => 'document_observed',     'title_key' => 'rp.radical.w1.document'],
                        ['key' => 'maintain_relationship', 'title_key' => 'rp.radical.w1.relationship'],
                        ['key' => 'avoid_confrontation',   'title_key' => 'rp.radical.w1.no_confront'],
                    ],
                ],
                [
                    'week' => 2,
                    'tasks' => [
                        ['key' => 'engage_trusted_figure', 'title_key' => 'rp.radical.w2.trusted'],
                        ['key' => 'contact_bnpt_family',   'title_key' => 'rp.radical.w2.bnpt'],
                    ],
                ],
                [
                    'week' => 4,
                    'tasks' => [
                        ['key' => 'cso_referral_ypp',      'title_key' => 'rp.radical.w4.ypp'],
                        ['key' => 'counter_narrative',     'title_key' => 'rp.radical.w4.narrative'],
                    ],
                ],
            ],
            'hotlines' => [
                ['slug' => 'patrolisiber'],
            ],
            'published' => true,
        ];
    }
}
