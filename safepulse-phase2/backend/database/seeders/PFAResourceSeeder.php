<?php

namespace Database\Seeders;

use App\Models\PFAResource;
use Illuminate\Database\Seeder;

/**
 * WHO Psychological First Aid resources — Look, Listen, Link.
 *
 * Source: WHO/War Trauma Foundation/World Vision International (2011)
 *         "Psychological First Aid: Guide for Field Workers"
 *
 * IMPORTANT: PFA is NOT therapy, NOT debriefing, NOT analysis of the event.
 * It's practical support — looking, listening, linking to resources.
 */
class PFAResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            // ── LOOK ───────────────────────────────────────────────────────
            [
                'action' => 'look',
                'topic'  => 'safety_check',
                'content_localized' => [
                    'en' => 'Make sure you are physically safe. If you are in immediate danger, contact emergency services first. SafePulse will be here when you are safe.',
                    'id' => 'Pastikan Anda aman secara fisik. Jika Anda dalam bahaya langsung, hubungi layanan darurat dulu. SafePulse akan tetap ada saat Anda sudah aman.',
                    'fr' => 'Assurez-vous d\'être physiquement en sécurité. Si vous êtes en danger immédiat, contactez les services d\'urgence d\'abord.',
                ],
                'referral_targets' => null,
            ],
            [
                'action' => 'look',
                'topic'  => 'practical_needs',
                'content_localized' => [
                    'en' => 'Practical needs first: somewhere to sleep tonight, food, charging your phone, contacting one trusted person. These come before emotional processing.',
                    'id' => 'Kebutuhan praktis dulu: tempat tidur malam ini, makanan, mengisi daya HP, menghubungi satu orang terpercaya. Ini didahulukan sebelum proses emosional.',
                ],
            ],
            // ── LISTEN ─────────────────────────────────────────────────────
            [
                'action' => 'listen',
                'topic'  => 'validation',
                'content_localized' => [
                    'en' => 'What happened to you was not your fault. The criminals who did this are skilled professionals. Many smart, capable people have been deceived in the same way.',
                    'id' => 'Apa yang terjadi pada Anda bukan kesalahan Anda. Para pelaku adalah penipu profesional. Banyak orang cerdas dan mampu telah ditipu dengan cara yang sama.',
                    'fr' => 'Ce qui vous est arrivé n\'est pas votre faute. Les criminels sont des professionnels habiles. Beaucoup de personnes intelligentes ont été trompées de la même manière.',
                ],
            ],
            [
                'action' => 'listen',
                'topic'  => 'grounding_5_4_3_2_1',
                'content_localized' => [
                    'en' => 'A grounding exercise you can do anywhere: name 5 things you can see, 4 you can touch, 3 you can hear, 2 you can smell, 1 you can taste. Take your time. There is no rush.',
                    'id' => 'Latihan grounding yang bisa Anda lakukan di mana saja: sebutkan 5 hal yang Anda lihat, 4 yang bisa Anda sentuh, 3 yang Anda dengar, 2 yang Anda cium, 1 yang Anda rasakan. Ambil waktu Anda. Tidak perlu terburu-buru.',
                ],
            ],
            // ── LINK ───────────────────────────────────────────────────────
            [
                'action' => 'link',
                'topic'  => 'referral_24_7',
                'content_localized' => [
                    'en' => 'These services are available now: SAPA 129 (women & children violence), SEJIWA 119 ext 8 (mental health), LISA helpline (suicide prevention). They speak Indonesian. You do not have to be in crisis to call.',
                    'id' => 'Layanan ini tersedia sekarang: SAPA 129 (kekerasan perempuan & anak), SEJIWA 119 ext 8 (kesehatan jiwa), LISA helpline (pencegahan bunuh diri). Mereka berbahasa Indonesia. Anda tidak perlu menunggu krisis untuk menelepon.',
                ],
                'referral_targets' => [
                    ['slug' => 'sapa_129'],
                    ['slug' => 'sejiwa_119'],
                    ['slug' => 'lisa_helpline'],
                ],
            ],
            [
                'action' => 'link',
                'topic'  => 'note_not_therapy',
                'content_localized' => [
                    'en' => 'SafePulse offers practical guidance and routing — not therapy. Long-term recovery often benefits from professional counselling. Many Indonesian psychologists offer sliding-scale fees.',
                    'id' => 'SafePulse memberikan panduan praktis dan pengarahan — bukan terapi. Pemulihan jangka panjang sering terbantu oleh konseling profesional. Banyak psikolog Indonesia menyediakan biaya berdasarkan kemampuan.',
                ],
            ],
        ];

        foreach ($resources as $r) {
            PFAResource::create($r);
        }

        $this->command->info('Seeded ' . count($resources) . ' WHO PFA resources.');
    }
}
