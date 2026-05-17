<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Adds 2 articles per new language (14 languages × 2 = 28 articles).
 * Uses insertOrIgnore — does NOT delete existing EN/ID articles.
 *
 * Article 1: Online scam identification (category: scam)
 * Article 2: Digital resilience for youth (category: digital_resilience / youth_peace)
 *
 * Aligned with ISIRC abstract — 10 crime domains:
 *   phishing · romance scam · trafficking · land fraud · money laundering ·
 *   CSAM · cyberbullying · violence-as-a-service · migrant worker literacy ·
 *   civic digital conflict
 *
 * Run: php artisan db:seed --class=ArticleSeederMultilingual --force
 */
class ArticleSeederMultilingual extends Seeder
{
    public function run(): void
    {
        $articles = $this->articles();

        foreach ($articles as $article) {
            DB::table('articles')->insertOrIgnore(array_merge($article, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $total = DB::table('articles')->count();
        $this->command->info("✓ Done. Total articles in database: {$total}");
    }

    private function articles(): array
    {
        return [

            // ══════════════════════════════════════════════════════════════
            // FRANÇAIS (fr)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'arnaques-en-ligne-asie-du-sud-est',
                'title'         => 'Escroqueries en Ligne en Asie du Sud-Est : Comment les Identifier',
                'language'      => 'fr',
                'category'      => 'scam',
                'summary' => "Les escroqueries numériques coûtent plus de 3 milliards USD par an en Asie du Sud-Est. Apprenez à reconnaître les cinq schémas les plus courants — hameçonnage, faux investissements, arnaques romantiques — avant de tomber dans le piège.",

                'body_markdown' => <<<'MD'
## L'Épidémie Numérique en Asie du Sud-Est

Les escroqueries en ligne ne sont pas de simples inconvénients — elles constituent une véritable crise de santé publique. En 2023, les pertes financières liées aux fraudes numériques ont dépassé 3,2 milliards USD dans la région, touchant des millions de personnes ordinaires.

## Les Cinq Schémas les Plus Courants

### 1. Hameçonnage par SMS (Smishing)
Des messages imitant votre banque, un service de livraison ou une agence gouvernementale vous demandent de cliquer sur un lien urgent. Ces liens volent vos identifiants ou installent des logiciels malveillants.

### 2. Fraude à l'Investissement (Pig Butchering)
Un inconnu en ligne établit une relation de confiance sur plusieurs semaines avant de vous inviter à investir sur une plateforme frauduleuse. Les profits affichés sont fictifs — l'argent disparaît lors du retrait.

### 3. Arnaque Romantique
Des fraudeurs professionnels créent de faux profils attrayants, investissent dans une relation émotionnelle, puis demandent de l'argent sous prétexte d'une urgence. La honte empêche souvent les victimes de signaler ces escroqueries.

### 4. Fausse Offre d'Emploi
Des annonces promettant des revenus élevés depuis chez soi cachent des recrutements de passeurs de fonds (money mules) ou pire — des centres d'escroquerie forcée documentés en Birmanie, Cambodge et Laos.

### 5. Usurpation d'Identité Institutionnelle
Des escrocs imitent des agences gouvernementales, des banques ou des services de livraison pour créer une urgence artificielle et extorquer des données personnelles ou des paiements.

## Signaux d'Alerte Universels

- Urgence extrême : "dans les 24 heures", "immédiatement"
- Demande d'OTP, de PIN ou de mot de passe — **aucune institution légitime ne le fait**
- Lien vers un domaine suspect (`.xyz`, `.top`, `.click`) ou un raccourcisseur d'URL
- Profits garantis sans risque — impossible dans tout marché régulé

## Que Faire si Vous Êtes Ciblé

1. **Ne cliquez pas** sur le lien — vérifiez directement auprès de l'institution
2. **Faites des captures d'écran** avant de supprimer le message
3. **Signalez** à votre autorité nationale de cybersécurité
4. **Partagez** cette information dans votre réseau — chaque information partagée protège quelqu'un

> **Rappel** : La honte appartient aux fraudeurs, pas aux victimes. Ces escroqueries sont conçues par des professionnels pour tromper des personnes intelligentes.
MD,
                'published_at'  => now()->subDays(4),
            ],

            [
                'slug'          => 'resilience-numerique-jeunes-asean',
                'title'         => 'Résilience Numérique pour les Jeunes : Cinq Habitudes pour Naviguer en Sécurité',
                'language'      => 'fr',
                'category'      => 'digital_resilience',
                'summary'       => 'Les jeunes de 15 à 29 ans sont les plus actifs en ligne mais aussi les plus exposés aux manipulations numériques. Cinq habitudes simples, validées par la recherche, réduisent significativement les risques d\'escroquerie et de radicalisation.',
                'body_markdown' => <<<'MD'
## Le Paradoxe Numérique des Jeunes

Les plateformes que vous utilisez sont conçues pour maximiser votre temps de connexion, pas votre bien-être. Comprendre ce mécanisme est la première étape vers une résilience numérique authentique.

## Cinq Habitudes Essentielles

### Habitude 1 : La Pause de Trois Secondes
Avant de cliquer, partager ou répondre — trois secondes suffisent pour activer votre sens critique. Les escrocs misent sur l'impulsivité. Le temps est votre meilleur allié.

### Habitude 2 : Vérifier Avant de Croire
Toute information surprenante mérite une vérification : cherchez le nom de la plateforme + "arnaque" ou "scam". Les communautés de victimes réagissent souvent plus vite que les autorités.

### Habitude 3 : Audit de Confidentialité Mensuel
Chaque mois, vérifiez quelles applications ont accès à votre caméra, microphone et localisation. Désactivez les permissions inutiles. C'est une hygiène numérique, pas de la paranoïa.

### Habitude 4 : Séparer Émotion et Action Financière
Si une interaction en ligne provoque une émotion intense — excitation, peur, amour soudain — suivie d'une demande d'argent ou de données, attendez 24 heures. L'urgence est la signature du fraudeur.

### Habitude 5 : Partager les Expériences Suspectes
La honte pousse au silence. Mais chaque témoignage partagé protège d'autres personnes. Signalez, même si vous n'avez pas été victime — les tentatives comptent aussi.

## Votre Sécurité Numérique en Pratique

- Utilisez un gestionnaire de mots de passe (Bitwarden est gratuit)
- Activez la double authentification sur tous vos comptes importants
- Vérifiez régulièrement ce qui est visible publiquement sur vos profils

## Conclusion

Être victime d'une arnaque n'est pas un signe de stupidité — c'est la preuve que vous interagissez avec des systèmes conçus pour exploiter la confiance humaine. La résilience numérique, c'est être **vigilant sans perdre confiance dans le monde**.
MD,
                'published_at'  => now()->subDays(8),
            ],

            // ══════════════════════════════════════════════════════════════
            // ARABIC / العربية (ar)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'tahqiq-min-alhiyal-aliliktruniya',
                'title'         => 'كيف تتعرف على عمليات الاحتيال الإلكترونية في جنوب شرق آسيا',
                'language'      => 'ar',
                'category'      => 'scam',
                'summary'       => 'تكلف عمليات الاحتيال الرقمي أكثر من 3 مليارات دولار سنوياً في جنوب شرق آسيا. تعلم كيف تتعرف على أنماط الاحتيال الخمسة الأكثر شيوعاً وكيف تحمي نفسك وعائلتك.',
                'body_markdown' => <<<'MD'
## وباء رقمي في جنوب شرق آسيا

لا تعدّ عمليات الاحتيال الإلكترونية مجرد إزعاج بسيط، بل هي أزمة صحة عامة حقيقية. في عام 2023، تجاوزت الخسائر المالية الناجمة عن الاحتيال الرقمي 3.2 مليار دولار في المنطقة، مما أثّر على ملايين المواطنين العاديين.

## الأنماط الخمسة الأكثر شيوعاً

### 1. التصيد الاحتيالي عبر الرسائل (Smishing)
رسائل تنتحل صفة البنك أو خدمة التوصيل أو جهة حكومية تطلب منك النقر على رابط عاجل. هذه الروابط تسرق بياناتك أو تثبت برامج ضارة.

### 2. احتيال الاستثمار (ذبح الخنزير)
يبني شخص غريب علاقة ثقة معك خلال أسابيع، ثم يدعوك للاستثمار في منصة مزيفة. الأرباح المعروضة وهمية — والأموال تختفي عند محاولة السحب.

### 3. احتيال الحب والرومانسية
يبني المحتالون علاقات عاطفية مزيفة ثم يطلبون المال بحجة حالات طوارئ. الخجل يمنع الضحايا في الغالب من الإبلاغ.

### 4. عروض العمل الوهمية
إعلانات تعد بدخل مرتفع من المنزل تخفي في الحقيقة تجنيداً لنقل الأموال غير المشروعة، أو في الحالات الأشد خطورة، تجنيداً قسرياً في مراكز احتيال موثقة.

### 5. انتحال هوية المؤسسات
يقوم المحتالون بتقليد البنوك أو الجهات الحكومية لخلق ضغط وهمي وانتزاع بياناتك الشخصية أو مدفوعاتك.

## إشارات التحذير العالمية

- إلحاح شديد: "في غضون 24 ساعة"، "فوراً"
- طلب كلمة المرور أو رمز OTP — **لا تشاركهما مع أحد أبداً**
- رابط يستخدم نطاقاً مشبوهاً أو مختصراً
- أرباح مضمونة بلا مخاطر — مستحيل في أي سوق منظّم

## ماذا تفعل إذا تعرضت للاستهداف؟

1. **لا تنقر** على الرابط — تحقق مباشرة من المؤسسة عبر قنواتها الرسمية
2. **التقط صوراً للشاشة** قبل حذف الرسائل كدليل
3. **أبلغ** الجهات المختصة بالجرائم الإلكترونية في بلدك
4. **شارك** المعلومة في دائرتك — كل معلومة مشتركة تحمي شخصاً آخر

> **تذكر**: العار يقع على المحتالين، ليس على الضحايا. هذه عمليات احتيال محترفة مصممة لخداع الأذكياء.
MD,
                'published_at'  => now()->subDays(5),
            ],

            [
                'slug'          => 'alsumud-alraqami-lilshabab',
                'title'         => 'الصمود الرقمي للشباب: خمس عادات للحماية على الإنترنت',
                'language'      => 'ar',
                'category'      => 'youth_peace',
                'summary'       => 'الشباب بين 15 و29 عاماً هم الأكثر نشاطاً على الإنترنت والأكثر عرضة للتلاعب الرقمي. خمس عادات بسيطة مدعومة بالبحث العلمي تقلل بشكل ملحوظ من مخاطر الاحتيال والتطرف.',
                'body_markdown' => <<<'MD'
## مفارقة الشباب الرقمية

المنصات التي تستخدمها مصممة لزيادة وقت استخدامك، وليس لتعزيز سعادتك. فهم هذه الآلية هو الخطوة الأولى نحو مرونة رقمية حقيقية.

## خمس عادات أساسية

### العادة الأولى: وقفة ثلاث ثوانٍ
قبل النقر أو المشاركة أو الرد — ثلاث ثوانٍ كافية لتفعيل تفكيرك النقدي. المحتالون يراهنون على الاندفاع.

### العادة الثانية: التحقق قبل التصديق
أي معلومة مفاجئة تستحق التحقق. ابحث عن اسم المنصة مع كلمة "احتيال". مجتمعات الضحايا غالباً أسرع من السلطات.

### العادة الثالثة: مراجعة الخصوصية شهرياً
تحقق شهرياً من التطبيقات التي لها وصول إلى كاميرتك وميكروفونك وموقعك. ألغِ الأذونات غير الضرورية.

### العادة الرابعة: الفصل بين العاطفة والقرار المالي
إذا أثار تفاعل إلكتروني مشاعر قوية — إثارة، خوف، حب مفاجئ — يعقبه طلب مال أو بيانات، انتظر 24 ساعة. الإلحاح هو توقيع المحتال.

### العادة الخامسة: مشاركة التجارب المشبوهة
الخجل يدفع نحو الصمت. لكن كل شهادة مشتركة تحمي الآخرين. أبلغ حتى لو لم تقع ضحية — محاولات الاحتيال تحسب أيضاً.

## خلاصة

الوقوع ضحية للاحتيال ليس دليلاً على الغباء — بل هو دليل على أنك تتفاعل مع أنظمة مصممة لاستغلال الثقة الإنسانية. المرونة الرقمية تعني أن تكون **يقظاً دون أن تفقد الثقة في العالم**.
MD,
                'published_at'  => now()->subDays(9),
            ],

            // ══════════════════════════════════════════════════════════════
            // DEUTSCH (de)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'online-betrug-suedostasien-erkennen',
                'title'         => 'Online-Betrug in Südostasien: Erkennen und Schützen',
                'language'      => 'de',
                'category'      => 'scam',
                'summary'       => 'Online-Betrug kostet Südostasien jährlich über 3 Milliarden USD. Lernen Sie die fünf häufigsten Betrugsmuster — Phishing, Investitionsbetrug, Romantikbetrug — zu erkennen, bevor es zu spät ist.',
                'body_markdown' => <<<'MD'
## Eine digitale Epidemie

Online-Betrug ist keine bloße Unannehmlichkeit — er ist eine echte Bedrohung für die öffentliche Gesundheit. Im Jahr 2023 überstiegen die Verluste durch digitalen Betrug in Südostasien 3,2 Milliarden USD.

## Die Fünf Häufigsten Betrugsmuster

### 1. SMS-Phishing (Smishing)
Nachrichten, die Ihre Bank, einen Paketdienst oder eine Behörde imitieren, fordern Sie auf, auf einen dringenden Link zu klicken. Diese Links stehlen Ihre Zugangsdaten oder installieren Schadsoftware.

### 2. Investitionsbetrug (Pig Butchering)
Ein Unbekannter baut über Wochen Vertrauen auf und lädt Sie dann ein, auf einer gefälschten Plattform zu investieren. Die angezeigten Gewinne sind fiktiv — das Geld verschwindet beim Auszahlungsversuch.

### 3. Romantikbetrug
Professionelle Betrüger erstellen attraktive Fake-Profile, investieren in eine emotionale Beziehung und bitten dann um Geld. Scham hindert Opfer oft daran, Anzeige zu erstatten.

### 4. Gefälschte Stellenangebote
Anzeigen, die hohe Einnahmen von zu Hause versprechen, verbergen tatsächlich Rekrutierungen als Geldkurier (Money Mule) oder sogar Zwangsrekrutierungen in dokumentierte Betrugsanlagen.

### 5. Institutionen-Imitation
Betrüger imitieren Banken oder Behörden, um künstlichen Druck zu erzeugen und persönliche Daten oder Zahlungen zu erpressen.

## Universelle Warnsignale

- Extremer Druck: "innerhalb von 24 Stunden", "sofort"
- Anfrage nach OTP, PIN oder Passwort — **keine seriöse Institution macht das**
- Link zu einer verdächtigen Domain oder einem URL-Kürzer
- Garantierte Gewinne ohne Risiko — in keinem regulierten Markt möglich

## Was zu Tun Ist

1. **Klicken Sie nicht** auf Links — überprüfen Sie direkt bei der Institution
2. **Screenshots machen** vor dem Löschen der Nachricht
3. **Melden** Sie den Vorfall bei der nationalen Cybersicherheitsbehörde
4. **Teilen** Sie die Information in Ihrem Netzwerk

> **Denken Sie daran**: Die Schande liegt bei den Betrügern, nicht bei den Opfern.
MD,
                'published_at'  => now()->subDays(6),
            ],

            [
                'slug'          => 'digitale-resilienz-jugendliche-suedostasien',
                'title'         => 'Digitale Resilienz für Jugendliche: Fünf Gewohnheiten für sicheres Surfen',
                'language'      => 'de',
                'category'      => 'digital_resilience',
                'summary'       => 'Jugendliche zwischen 15 und 29 Jahren sind online am aktivsten, aber auch am stärksten gefährdet. Fünf einfache, wissenschaftlich fundierte Gewohnheiten reduzieren das Risiko von Betrug und Radikalisierung erheblich.',
                'body_markdown' => <<<'MD'
## Das digitale Paradox der Jugend

Die Plattformen, die Sie nutzen, sind darauf ausgelegt, Ihre Online-Zeit zu maximieren — nicht Ihr Wohlbefinden. Diesen Mechanismus zu verstehen ist der erste Schritt zu echter digitaler Resilienz.

## Fünf Wesentliche Gewohnheiten

### Gewohnheit 1: Die Drei-Sekunden-Pause
Bevor Sie klicken, teilen oder antworten — drei Sekunden reichen, um Ihr kritisches Denken zu aktivieren. Betrüger setzen auf Impulsivität.

### Gewohnheit 2: Überprüfen Vor dem Glauben
Jede überraschende Information verdient eine Überprüfung. Suchen Sie den Plattformnamen + "Betrug" oder "Scam". Opfer-Communitys reagieren oft schneller als Behörden.

### Gewohnheit 3: Monatlicher Datenschutz-Audit
Überprüfen Sie monatlich, welche Apps Zugang zu Ihrer Kamera, Ihrem Mikrofon und Ihrem Standort haben. Deaktivieren Sie unnötige Berechtigungen.

### Gewohnheit 4: Emotion Von Finanzentscheidung Trennen
Wenn eine Online-Interaktion starke Gefühle auslöst — Aufregung, Angst, plötzliche Liebe — gefolgt von einer Geld- oder Datenbitte: warten Sie 24 Stunden. Dringlichkeit ist die Signatur des Betrügers.

### Gewohnheit 5: Verdächtige Erfahrungen Teilen
Scham führt zum Schweigen. Aber jede geteilte Erfahrung schützt andere Menschen. Melden Sie Betrugsversuche — auch wenn Sie nicht Opfer geworden sind.

## Fazit

Opfer eines Betrugs zu werden ist kein Zeichen von Dummheit — es beweist, dass Sie mit Systemen interagieren, die darauf ausgelegt sind, menschliches Vertrauen auszunutzen. Digitale Resilienz bedeutet, **wachsam zu sein, ohne das Vertrauen in die Welt zu verlieren**.
MD,
                'published_at'  => now()->subDays(11),
            ],

            // ══════════════════════════════════════════════════════════════
            // ESPAÑOL (es)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'estafas-en-linea-asia-sudoriental',
                'title'         => 'Estafas en Línea en el Sudeste Asiático: Cómo Identificarlas y Protegerse',
                'language'      => 'es',
                'category'      => 'scam',
                'summary'       => 'El fraude digital cuesta más de 3,000 millones USD anuales en el Sudeste Asiático. Aprenda a reconocer los cinco patrones más comunes — phishing, inversiones falsas, estafas románticas — antes de que sea demasiado tarde.',
                'body_markdown' => <<<'MD'
## Una Epidemia Digital

Las estafas en línea no son simples inconveniencias — son una crisis real de salud pública. En 2023, las pérdidas por fraude digital superaron los 3,200 millones USD en la región, afectando a millones de ciudadanos ordinarios.

## Los Cinco Patrones Más Comunes

### 1. Phishing por SMS (Smishing)
Mensajes que imitan a su banco, un servicio de paquetería o una agencia gubernamental le piden que haga clic en un enlace urgente. Estos enlaces roban sus credenciales o instalan malware.

### 2. Fraude de Inversión (Matanza del Cerdo)
Un desconocido en línea construye confianza durante semanas y luego le invita a invertir en una plataforma fraudulenta. Las ganancias mostradas son ficticias — el dinero desaparece al intentar retirarlo.

### 3. Estafa Romántica
Estafadores profesionales crean perfiles falsos atractivos, invierten en una relación emocional y luego piden dinero con pretexto de emergencias. La vergüenza impide frecuentemente que las víctimas denuncien.

### 4. Ofertas de Trabajo Falsas
Anuncios que prometen altos ingresos desde casa ocultan en realidad reclutamientos como transportistas de dinero (money mules) o, en los casos más graves, reclutamientos forzados en centros de estafa documentados.

### 5. Suplantación de Identidad Institucional
Los estafadores imitan bancos o agencias gubernamentales para crear presión artificial y obtener datos personales o pagos.

## Señales de Alerta Universales

- Urgencia extrema: "en 24 horas", "inmediatamente"
- Solicitud de OTP, PIN o contraseña — **ninguna institución legítima lo hace**
- Enlace a dominio sospechoso o acortador de URL
- Ganancias garantizadas sin riesgo — imposible en cualquier mercado regulado

## Qué Hacer Si Es Objetivo de una Estafa

1. **No haga clic** en el enlace — verifique directamente con la institución
2. **Tome capturas de pantalla** antes de eliminar el mensaje
3. **Denuncie** a la autoridad nacional de ciberseguridad
4. **Comparta** esta información en su red — cada conocimiento compartido protege a alguien

> **Recuerde**: La vergüenza le pertenece a los estafadores, no a las víctimas.
MD,
                'published_at'  => now()->subDays(7),
            ],

            [
                'slug'          => 'resiliencia-digital-jovenes-asean',
                'title'         => 'Resiliencia Digital para Jóvenes: Cinco Hábitos Esenciales de Seguridad',
                'language'      => 'es',
                'category'      => 'digital_resilience',
                'summary'       => 'Los jóvenes de 15 a 29 años son los más activos en línea pero también los más expuestos a la manipulación digital. Cinco hábitos simples, validados por la investigación, reducen significativamente los riesgos de estafa y radicalización.',
                'body_markdown' => <<<'MD'
## La Paradoja Digital de los Jóvenes

Las plataformas que usas están diseñadas para maximizar tu tiempo de conexión, no tu bienestar. Entender este mecanismo es el primer paso hacia una verdadera resiliencia digital.

## Cinco Hábitos Esenciales

### Hábito 1: La Pausa de Tres Segundos
Antes de hacer clic, compartir o responder — tres segundos bastan para activar tu pensamiento crítico. Los estafadores apuestan por la impulsividad.

### Hábito 2: Verificar Antes de Creer
Cualquier información sorprendente merece verificación. Busca el nombre de la plataforma + "estafa" o "scam". Las comunidades de víctimas suelen responder más rápido que las autoridades.

### Hábito 3: Auditoría de Privacidad Mensual
Cada mes, verifica qué aplicaciones tienen acceso a tu cámara, micrófono y ubicación. Desactiva permisos innecesarios.

### Hábito 4: Separar Emoción de Acción Financiera
Si una interacción en línea provoca emociones intensas — emoción, miedo, amor repentino — seguidas de una solicitud de dinero o datos, espera 24 horas. La urgencia es la firma del estafador.

### Hábito 5: Compartir Experiencias Sospechosas
La vergüenza lleva al silencio. Pero cada testimonio compartido protege a otras personas. Denuncia, aunque no hayas sido víctima.

## Conclusión

Ser víctima de una estafa no es señal de estupidez — es prueba de que interactúas con sistemas diseñados para explotar la confianza humana. La resiliencia digital significa ser **vigilante sin perder la confianza en el mundo**.
MD,
                'published_at'  => now()->subDays(14),
            ],

            // ══════════════════════════════════════════════════════════════
            // 中文简体 (zh)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'dongnanaya-wangluo-pianzhu-shibie-zhinan',
                'title'         => '东南亚网络诈骗识别与防范指南',
                'language'      => 'zh',
                'category'      => 'scam',
                'summary'       => '网络诈骗每年在东南亚造成逾32亿美元损失。了解五种最常见的诈骗模式——网络钓鱼、虚假投资、浪漫诈骗——在为时已晚之前保护自己和家人。',
                'body_markdown' => <<<'MD'
## 数字流行病

网络诈骗不仅仅是一种不便——它是一场真正的公共卫生危机。2023年，东南亚地区因数字诈骗造成的损失超过32亿美元，影响了数百万普通市民。

## 五种最常见的诈骗模式

### 1. 短信钓鱼（Smishing）
冒充银行、快递公司或政府机构的短信要求您点击紧急链接。这些链接会窃取您的登录凭据或安装恶意软件。

### 2. 投资诈骗（杀猪盘）
陌生人在网上建立数周的信任关系，然后邀请您在虚假平台上投资。显示的利润是虚构的——提款时资金便消失无踪。

### 3. 浪漫诈骗
职业骗子建立吸引人的虚假账号，投入情感关系，然后以紧急情况为由索要钱财。羞耻感往往阻止受害者举报。

### 4. 虚假工作机会
承诺高收入居家工作的广告实际上隐藏着洗钱协助（Money Mule）招募，或更严重的——强迫参与诈骗中心的案例。

### 5. 机构身份冒充
骗子冒充银行或政府机构制造人为紧迫感，索取个人信息或付款。

## 通用警示信号

- 极度紧迫："24小时内"、"立即"
- 索取OTP、PIN或密码——**任何合法机构都不会这样做**
- 链接指向可疑域名或短链接服务
- 无风险保证收益——在任何受监管市场中都不可能

## 遇到可疑情况如何应对

1. **不要点击**链接——直接通过官方渠道联系机构
2. **截图保存**后再删除消息，作为证据
3. **向**国家网络安全机构举报
4. **分享**这些信息——每一次分享都在保护他人

> **请记住**：羞耻属于骗子，不属于受害者。这些骗局由专业犯罪团伙精心设计，专门欺骗聪明人。
MD,
                'published_at'  => now()->subDays(6),
            ],

            [
                'slug'          => 'qingnian-shuzi-tanxing-wu-xiguan',
                'title'         => '青少年数字韧性：保护自己免受网络威胁的五个好习惯',
                'language'      => 'zh',
                'category'      => 'youth_peace',
                'summary'       => '15至29岁的年轻人是最活跃的网络用户，却也是最容易受到数字操纵的群体。五个简单且经过研究验证的习惯可显著降低遭受诈骗和极端化的风险。',
                'body_markdown' => <<<'MD'
## 年轻人的数字悖论

您使用的平台旨在最大化您的上网时间，而非您的幸福感。了解这一机制是建立真正数字韧性的第一步。

## 五个核心习惯

### 习惯一：三秒暂停
在点击、分享或回复之前——三秒钟足以激活您的批判性思维。诈骗者赌的就是您的冲动。

### 习惯二：先验证后相信
任何令人惊讶的信息都值得核实。搜索平台名称加"诈骗"或"scam"。受害者社区的反应通常比官方机构更快。

### 习惯三：每月隐私审计
每月检查哪些应用程序可访问您的摄像头、麦克风和位置。关闭不必要的权限——这是数字卫生，不是偏执。

### 习惯四：将情绪与财务决策分开
如果某次网络互动引发了强烈情绪——兴奋、恐惧、突如其来的爱慕——随后又出现金钱或数据请求，请等待24小时再行动。紧迫感是诈骗者的标志。

### 习惯五：分享可疑经历
羞耻感导致沉默。但每一次分享都在保护他人。举报诈骗尝试——即使您没有成为受害者也很重要。

## 结语

成为诈骗受害者并不代表愚蠢——它证明您正在与为利用人类信任而设计的系统互动。数字韧性意味着在**保持警觉的同时不失去对世界的信任**。
MD,
                'published_at'  => now()->subDays(15),
            ],

            // ══════════════════════════════════════════════════════════════
            // 中文繁體 (zh-TW)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'dongnanaya-wangluo-pianzhu-zhTW',
                'title'         => '東南亞網路詐騙識別與防範指南',
                'language'      => 'zh-TW',
                'category'      => 'scam',
                'summary'       => '網路詐騙每年在東南亞造成逾32億美元損失。了解五種最常見的詐騙模式——網路釣魚、虛假投資、浪漫詐騙——在為時已晚之前保護自己和家人。',
                'body_markdown' => <<<'MD'
## 數位流行病

網路詐騙不僅僅是一種不便——它是一場真正的公共衛生危機。2023年，東南亞地區因數位詐騙造成的損失超過32億美元，影響了數百萬普通市民。

## 五種最常見的詐騙模式

### 1. 簡訊釣魚（Smishing）
冒充銀行、快遞公司或政府機構的簡訊要求您點擊緊急連結。這些連結會竊取您的登入憑證或安裝惡意軟體。

### 2. 投資詐騙（殺豬盤）
陌生人在網上建立數週的信任關係，然後邀請您在虛假平台上投資。顯示的利潤是虛構的——提款時資金便消失無蹤。

### 3. 浪漫詐騙
職業騙子建立吸引人的虛假帳號，投入情感關係，然後以緊急情況為由索要錢財。

### 4. 虛假工作機會
承諾高收入居家工作的廣告實際上隱藏著洗錢協助招募或強迫參與詐騙中心的案例。

### 5. 機構身份冒充
騙子冒充銀行或政府機構制造人為緊迫感，索取個人資訊或付款。

## 通用警示訊號

- 極度緊迫：「24小時內」、「立即」
- 索取OTP、PIN或密碼——**任何合法機構都不會這樣做**
- 連結指向可疑網域或短連結服務
- 無風險保證收益——在任何受監管市場中都不可能

## 遇到可疑情況如何應對

1. **不要點擊**連結——直接透過官方管道聯繫機構
2. **截圖保存**後再刪除訊息，作為證據
3. **向**國家網路安全機構舉報
4. **分享**這些資訊——每一次分享都在保護他人
MD,
                'published_at'  => now()->subDays(8),
            ],

            [
                'slug'          => 'qingnian-shuzi-tanxing-zhTW',
                'title'         => '青少年數位韌性：在網路世界保護自己的五個好習慣',
                'language'      => 'zh-TW',
                'category'      => 'digital_resilience',
                'summary'       => '15至29歲的年輕人是最活躍的網路使用者，卻也最容易受到數位操縱。五個簡單且經研究驗證的習慣可顯著降低遭受詐騙和極端化的風險。',
                'body_markdown' => <<<'MD'
## 年輕人的數位悖論

您使用的平台旨在最大化您的上網時間，而非您的幸福感。了解這一機制是建立真正數位韌性的第一步。

## 五個核心習慣

### 習慣一：三秒暫停
在點擊、分享或回覆之前——三秒鐘足以啟動您的批判性思維。詐騙者賭的就是您的衝動。

### 習慣二：先驗證後相信
任何令人驚訝的資訊都值得核實。搜尋平台名稱加「詐騙」或「scam」——受害者社群的反應通常比官方機構更快。

### 習慣三：每月隱私稽核
每月檢查哪些應用程式可存取您的攝影機、麥克風和位置。關閉不必要的權限。

### 習慣四：將情緒與財務決策分開
如果某次網路互動引發了強烈情緒，隨後又出現金錢或資料請求，請等待24小時再行動。緊迫感是詐騙者的標誌。

### 習慣五：分享可疑經歷
羞恥感導致沉默。但每一次分享都在保護他人。舉報詐騙嘗試——即使您沒有成為受害者也很重要。
MD,
                'published_at'  => now()->subDays(16),
            ],

            // ══════════════════════════════════════════════════════════════
            // РУССКИЙ (ru)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'onlayn-moshennichestvo-yugo-vostochnaya-aziya',
                'title'         => 'Онлайн-мошенничество в Юго-Восточной Азии: как защитить себя',
                'language'      => 'ru',
                'category'      => 'scam',
                'summary'       => 'Цифровое мошенничество ежегодно обходится Юго-Восточной Азии в более чем 3 миллиарда долларов. Узнайте, как распознать пять самых распространённых схем обмана и защитить себя и близких.',
                'body_markdown' => <<<'MD'
## Цифровая эпидемия

Онлайн-мошенничество — это не просто неудобство, а настоящий кризис общественного здравоохранения. В 2023 году убытки от цифрового мошенничества в регионе превысили 3,2 миллиарда долларов.

## Пять наиболее распространённых схем

### 1. SMS-фишинг (Смишинг)
Сообщения, имитирующие банк, службу доставки или государственный орган, просят вас перейти по срочной ссылке. Эти ссылки похищают ваши учётные данные или устанавливают вредоносное программное обеспечение.

### 2. Инвестиционное мошенничество («Разделка свиньи»)
Незнакомец в интернете выстраивает доверительные отношения в течение нескольких недель, а затем приглашает вложить деньги на поддельную платформу. Отображаемая прибыль фиктивна — деньги исчезают при попытке вывода.

### 3. Романтическое мошенничество
Профессиональные мошенники создают привлекательные фиктивные профили, вкладываются в эмоциональные отношения, а затем просят деньги под предлогом чрезвычайной ситуации.

### 4. Фиктивные предложения работы
Объявления, обещающие высокий заработок на дому, скрывают вербовку в качестве денежных мулов или, в крайних случаях, принудительный труд в задокументированных мошеннических центрах.

### 5. Имитация институтов
Мошенники копируют банки или государственные органы, создавая искусственное давление для получения личных данных или платежей.

## Универсальные предупреждающие сигналы

- Крайняя срочность: «в течение 24 часов», «немедленно»
- Запрос OTP, PIN или пароля — **ни одна легитимная организация этого не делает**
- Ссылка на подозрительный домен или сокращатель URL
- Гарантированная прибыль без риска — невозможно ни на одном регулируемом рынке

## Что делать, если вы стали мишенью

1. **Не нажимайте** на ссылку — проверьте напрямую через официальный канал
2. **Сделайте скриншоты** перед удалением сообщения
3. **Сообщите** в национальный орган кибербезопасности
4. **Поделитесь** информацией — каждое распространённое знание защищает кого-то ещё

> **Помните**: стыд принадлежит мошенникам, а не жертвам.
MD,
                'published_at'  => now()->subDays(9),
            ],

            [
                'slug'          => 'tsifrovaya-ustoychivost-molodezhi',
                'title'         => 'Цифровая устойчивость молодёжи: пять ключевых привычек безопасности',
                'language'      => 'ru',
                'category'      => 'digital_resilience',
                'summary'       => 'Молодёжь от 15 до 29 лет наиболее активна в интернете, но и наиболее уязвима к цифровым манипуляциям. Пять простых привычек, подтверждённых исследованиями, значительно снижают риски мошенничества и радикализации.',
                'body_markdown' => <<<'MD'
## Цифровой парадокс молодёжи

Платформы, которыми вы пользуетесь, созданы для максимизации времени вашего пребывания в интернете — а не для вашего благополучия. Понимание этого механизма — первый шаг к подлинной цифровой устойчивости.

## Пять основных привычек

### Привычка 1: Пауза на три секунды
Перед тем как кликнуть, поделиться или ответить — трёх секунд достаточно, чтобы активировать критическое мышление. Мошенники рассчитывают на импульсивность.

### Привычка 2: Проверять перед тем, как верить
Любая неожиданная информация заслуживает проверки. Ищите название платформы + «мошенничество» или «scam». Сообщества пострадавших реагируют быстрее официальных органов.

### Привычка 3: Ежемесячный аудит конфиденциальности
Каждый месяц проверяйте, какие приложения имеют доступ к вашей камере, микрофону и местоположению. Отключайте ненужные разрешения.

### Привычка 4: Разделять эмоции и финансовые решения
Если онлайн-взаимодействие вызывает сильные эмоции, за которыми следует просьба о деньгах или данных — подождите 24 часа. Срочность — фирменный знак мошенника.

### Привычка 5: Делиться подозрительным опытом
Стыд ведёт к молчанию. Но каждый рассказ защищает других людей. Сообщайте о попытках мошенничества — даже если вы не стали жертвой.

## Вывод

Стать жертвой мошенничества — не признак глупости. Это доказательство того, что вы взаимодействуете с системами, созданными для эксплуатации человеческого доверия. Цифровая устойчивость — это быть **бдительным, не теряя доверия к миру**.
MD,
                'published_at'  => now()->subDays(17),
            ],

            // ══════════════════════════════════════════════════════════════
            // 한국어 (ko)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'dongnam-asia-online-sagitujaeng-shikbyeol',
                'title'         => '동남아시아 온라인 사기 유형과 예방 가이드',
                'language'      => 'ko',
                'category'      => 'scam',
                'summary'       => '디지털 사기로 인해 동남아시아에서 연간 32억 달러 이상의 피해가 발생합니다. 피싱, 가짜 투자, 로맨스 사기 등 5가지 주요 패턴을 파악하고 자신과 가족을 보호하세요.',
                'body_markdown' => <<<'MD'
## 디지털 팬데믹

온라인 사기는 단순한 불편함이 아닌 실질적인 공중보건 위기입니다. 2023년 동남아시아 지역의 디지털 사기 피해액은 32억 달러를 넘어섰으며, 수백만 명의 일반 시민들이 영향을 받았습니다.

## 5가지 주요 사기 패턴

### 1. SMS 피싱 (스미싱)
은행, 택배 서비스 또는 정부 기관을 사칭한 문자가 긴급 링크를 클릭하도록 유도합니다. 이 링크는 로그인 정보를 훔치거나 악성 소프트웨어를 설치합니다.

### 2. 투자 사기 (돼지 도살 사기)
온라인에서 몇 주에 걸쳐 신뢰 관계를 구축한 낯선 사람이 가짜 플랫폼에 투자하도록 유도합니다. 표시된 수익은 허구이며, 출금 시 자금이 사라집니다.

### 3. 로맨스 사기
전문 사기꾼이 매력적인 가짜 프로필을 만들어 감정적 관계에 투자한 후, 긴급 상황을 빌미로 돈을 요구합니다.

### 4. 허위 취업 제안
재택근무로 높은 수입을 약속하는 광고는 실제로 돈 세탁 운반책(머니 뮬) 모집이거나, 더 심각하게는 강제 사기 센터 모집을 숨기고 있습니다.

### 5. 기관 사칭
사기꾼들이 은행이나 정부 기관을 사칭하여 인위적인 압박을 만들고 개인 정보나 결제를 유도합니다.

## 공통 위험 신호

- 극단적 긴박감: "24시간 내", "즉시"
- OTP, PIN 또는 비밀번호 요청 — **합법적인 기관은 절대 요청하지 않습니다**
- 의심스러운 도메인 또는 URL 단축 서비스 링크
- 위험 없는 보장 수익 — 규제된 시장에서는 불가능

## 표적이 되었을 때 할 일

1. **링크를 클릭하지 마세요** — 공식 채널을 통해 직접 확인하세요
2. **스크린샷을 저장하세요** — 삭제 전에 증거를 남기세요
3. **국가 사이버보안 기관에 신고하세요**
4. **정보를 공유하세요** — 공유된 지식은 누군가를 보호합니다
MD,
                'published_at'  => now()->subDays(10),
            ],

            [
                'slug'          => 'cheongsoyon-dijiteol-hoebokryeok',
                'title'         => '청소년 디지털 회복력: 온라인에서 자신을 보호하는 다섯 가지 습관',
                'language'      => 'ko',
                'category'      => 'youth_peace',
                'summary'       => '15~29세 청소년은 가장 활발한 인터넷 사용자이지만 디지털 조작에 가장 취약하기도 합니다. 연구로 검증된 5가지 간단한 습관이 사기와 급진화 위험을 크게 줄여줍니다.',
                'body_markdown' => <<<'MD'
## 청소년의 디지털 역설

여러분이 사용하는 플랫폼은 여러분의 행복이 아닌 온라인 시간을 최대화하도록 설계되어 있습니다. 이 메커니즘을 이해하는 것이 진정한 디지털 회복력의 첫걸음입니다.

## 5가지 핵심 습관

### 습관 1: 3초 멈춤
클릭, 공유, 답장 전에 — 3초면 비판적 사고를 활성화하기에 충분합니다. 사기꾼은 충동에 의존합니다.

### 습관 2: 믿기 전에 확인하기
놀라운 정보는 검증이 필요합니다. 플랫폼 이름 + "사기"를 검색하세요. 피해자 커뮤니티는 종종 공식 기관보다 빠르게 반응합니다.

### 습관 3: 월간 개인정보 감사
매월 카메라, 마이크, 위치에 접근하는 앱을 확인하세요. 불필요한 권한을 해제하세요.

### 습관 4: 감정과 금융 결정 분리하기
온라인 상호작용이 강한 감정을 유발하고 이어서 돈이나 데이터를 요청한다면 — 24시간 기다리세요. 긴박감은 사기꾼의 특징입니다.

### 습관 5: 의심스러운 경험 공유하기
수치심이 침묵을 만듭니다. 하지만 공유된 경험은 다른 사람을 보호합니다. 피해자가 아니어도 신고하세요.

## 결론

사기 피해자가 되는 것은 어리석음의 증거가 아닙니다. 인간의 신뢰를 착취하도록 설계된 시스템과 상호작용하고 있다는 증거입니다. 디지털 회복력은 **세상에 대한 신뢰를 잃지 않으면서 경계를 유지하는 것**입니다.
MD,
                'published_at'  => now()->subDays(18),
            ],

            // ══════════════════════════════════════════════════════════════
            // 日本語 (ja)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'tonan-ajia-onrain-sagi-miwakekata',
                'title'         => '東南アジアのオンライン詐欺：見分け方と対処法',
                'language'      => 'ja',
                'category'      => 'scam',
                'summary'       => 'デジタル詐欺は東南アジアで年間32億ドル以上の被害をもたらしています。フィッシング、偽投資、ロマンス詐欺など5つの主要パターンを理解し、自分と家族を守りましょう。',
                'body_markdown' => <<<'MD'
## デジタル・パンデミック

オンライン詐欺は単なる不便ではなく、真の公衆衛生上の危機です。2023年、東南アジアのデジタル詐欺による損失は32億ドルを超え、数百万人の一般市民に影響を与えました。

## 5つの主要詐欺パターン

### 1. SMSフィッシング（スミッシング）
銀行、配送業者、または政府機関を装ったメッセージが緊急リンクのクリックを求めます。これらのリンクは認証情報を盗むか、マルウェアをインストールします。

### 2. 投資詐欺（豚の解体詐欺）
オンラインの見知らぬ人が数週間かけて信頼関係を築き、偽のプラットフォームへの投資に誘い込みます。表示された利益は架空のものです。

### 3. ロマンス詐欺
プロの詐欺師が魅力的な偽プロフィールを作成し、感情的な関係に投資した後、緊急事態を理由にお金を要求します。

### 4. 偽の求人情報
在宅で高収入を約束する広告は、実際にはマネーミュール（不正資金運搬役）の募集や、記録された詐欺センターへの強制勧誘を隠しています。

### 5. 機関なりすまし
詐欺師が銀行や政府機関を装って人工的なプレッシャーを作り出し、個人情報や支払いを引き出します。

## 共通の警告サイン

- 極端な緊迫感：「24時間以内」、「即刻」
- OTP・PIN・パスワードの要求 — **正規の機関は絶対に求めません**
- 不審なドメインやURL短縮サービスへのリンク
- リスクなしの保証収益 — 規制された市場では不可能

## 標的にされた場合の対処法

1. **リンクをクリックしない** — 公式チャネルから直接確認する
2. **スクリーンショットを保存** してからメッセージを削除する
3. **国家サイバーセキュリティ機関に報告** する
4. **情報を共有** する — 共有された知識は誰かを守ります
MD,
                'published_at'  => now()->subDays(11),
            ],

            [
                'slug'          => 'wakamonotachi-no-dejitaru-reziriensu',
                'title'         => '若者のデジタル・レジリエンス：オンラインで身を守る5つの習慣',
                'language'      => 'ja',
                'category'      => 'digital_resilience',
                'summary'       => '15〜29歳の若者は最もアクティブなインターネットユーザーですが、デジタル操作に対しても最も脆弱です。研究で検証された5つのシンプルな習慣が詐欺と過激化のリスクを大幅に低減します。',
                'body_markdown' => <<<'MD'
## 若者のデジタルパラドックス

あなたが使っているプラットフォームは、あなたの幸福ではなくオンライン時間を最大化するように設計されています。このメカニズムを理解することが、真のデジタル・レジリエンスへの第一歩です。

## 5つのコア習慣

### 習慣1：3秒の一時停止
クリック・共有・返信する前に — 3秒あれば批判的思考を働かせるのに十分です。詐欺師は衝動に頼っています。

### 習慣2：信じる前に確認する
驚くような情報はすべて検証に値します。プラットフォーム名+「詐欺」で検索してください。被害者コミュニティは公的機関より速く反応することが多いです。

### 習慣3：毎月のプライバシー監査
毎月、カメラ・マイク・位置情報へのアクセス権を持つアプリを確認しましょう。不要な権限を無効にしましょう。

### 習慣4：感情と金融的決断を分ける
オンラインのやりとりで強い感情が引き起こされ、お金やデータの要求が続くなら — 24時間待ってください。緊迫感は詐欺師の特徴です。

### 習慣5：不審な経験を共有する
恥ずかしさが沈黙を生みます。でも共有された経験は他の人を守ります。被害者でなくても報告してください。

## まとめ

詐欺の被害者になることは愚かさの証拠ではありません。人間の信頼を悪用するために設計されたシステムと相互作用しているという証拠です。デジタル・レジリエンスとは、**世界への信頼を失わずに警戒し続けること**です。
MD,
                'published_at'  => now()->subDays(19),
            ],

            // ══════════════════════════════════════════════════════════════
            // JAVANESE / ꦧꦱꦗꦮ (jv)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'ngerteni-penipuan-online-asia-tenggara',
                'title'         => 'Ngerteni Penipuan Online ing Asia Tenggara: Tandha-Tandha lan Carane Nglindhungi Awake Dhewe',
                'language'      => 'jv',
                'category'      => 'scam',
                'summary'       => 'Penipuan digital nyebabake kerugian luwih saka 3 milyar dolar saben taun ing Asia Tenggara. Sinau ngerteni limang pola penipuan sing paling umum — phishing, investasi palsu, penipuan romantis — sadurunge kasep.',
                'body_markdown' => <<<'MD'
## Wabah Digital

Penipuan online dudu mung gangguan — iki krisis kesehatan masyarakat sing nyata. Ing taun 2023, kerugian amarga penipuan digital ing kawasan iki ngluwihi 3,2 milyar dolar, mengaruhi jutaan warga biasa.

## Lima Pola Penipuan Sing Paling Umum

### 1. Phishing SMS (Smishing)
Pesen sing ngaku-ngaku dadi bank, layanan pengiriman, utawa instansi pemerintah njaluk sliramu ngeklik tautan sing "urgent". Tautan iku nyolong akun utawa masang malware.

### 2. Penipuan Investasi (Pig Butchering)
Wong sing ora dikenal mbangun kapercayan liwat pirang-pirang minggu, banjur ngajak investasi ing platform palsu. Bathi sing ditampilake ora nyata — dhuwit ilang nalika dicairkan.

### 3. Penipuan Romantis
Penipu profesional nggawe profil palsu sing narik, mbangun hubungan emosional, banjur njaluk dhuwit kanthi alasan darurat.

### 4. Lowongan Kerja Palsu
Iklan sing janji penghasilan gedhe saka omah asline nyingidake rekrutmen money mule utawa, luwih parah, kerja paksa ing pusat penipuan.

### 5. Nyamar dadi Institusi
Penipu nyamar dadi bank utawa instansi pemerintah kanggo nggawe tekanan buatan lan njupuk data pribadi utawa pembayaran.

## Tandha Bahaya Universal

- Urgensi ekstrem: "sajrone 24 jam", "langsung"
- Njaluk OTP, PIN, utawa sandi — **ora ana institusi resmi sing nglakoni iki**
- Tautan menyang domain mencurigakan
- Bathi dijamin tanpa risiko — ora mungkin ing pasar sing diatur

## Apa sing Kudu Ditindakake

1. **Aja ngeklik** tautan — verifikasi langsung liwat saluran resmi
2. **Screenshot** sadurunge mbusak pesen
3. **Laporan** menyang kepolisian siber
4. **Barengake** informasi iki — saben pangerten sing dibarengake nglindhungi wong liya
MD,
                'published_at'  => now()->subDays(12),
            ],

            [
                'slug'          => 'ketahanan-digital-pemuda-jawa',
                'title'         => 'Ketahanan Digital kanggo Pemuda: Lima Kebiasaan Penting kanggo Aman Online',
                'language'      => 'jv',
                'category'      => 'digital_resilience',
                'summary'       => 'Pemuda umur 15-29 taun paling aktif online nanging uga paling gampang kena manipulasi digital. Lima kebiasaan sederhana sing wis dibuktekake riset bisa ngurangi risiko penipuan lan radikalisasi kanthi signifikan.',
                'body_markdown' => <<<'MD'
## Paradoks Digital Pemuda

Platform sing sliramu gunakake dirancang kanggo maksimalake wektu online sliramu — dudu kesejahteraan sliramu. Mangerteni mekanisme iki iku langkah pertama menuju ketahanan digital sing sejati.

## Lima Kebiasaan Inti

### Kebiasaan 1: Jedah Telu Detik
Sadurunge ngeklik, barengake, utawa mangsuli — telu detik cukup kanggo ngaktifake pikiran kritis. Penipu ngandelake impulsivitas.

### Kebiasaan 2: Verifikasi Sadurunge Percaya
Informasi apa wae sing ngaget-ngageti pantes diverifikasi. Goleki jeneng platform + "penipuan" utawa "scam". Komunitas korban asring luwih cepet nanggapi tinimbang otoritas.

### Kebiasaan 3: Audit Privasi Saben Wulan
Saben wulan, priksa aplikasi apa wae sing nduweni akses menyang kamera, mikrofon, lan lokasi sliramu. Mateni izin sing ora perlu.

### Kebiasaan 4: Pisahake Emosi saka Keputusan Finansial
Yen interaksi online njalari emosi sing kuat banjur ana panjalukan dhuwit utawa data — enteni 24 jam. Urgensitas iku tanda-tandha penipu.

### Kebiasaan 5: Barengake Pengalaman Mencurigakan
Isin njalari meneng. Nanging saben pengalaman sing dibarengake nglindhungi wong liya. Laporan, sanajan sliramu dudu korban.

## Kesimpulan

Dadi korban penipuan dudu tandha kebodohan — iki buktine sliramu berinteraksi karo sistem sing dirancang kanggo ngeksploitasi kapercayan manungsa. Ketahanan digital tegese dadi **waspada tanpa kelangan kapercayan marang jagad iki**.
MD,
                'published_at'  => now()->subDays(20),
            ],

            // ══════════════════════════════════════════════════════════════
            // ไทย (th)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'rabubonthutit-asean-khomun-pongkan',
                'title'         => 'การระบุและป้องกันการหลอกลวงออนไลน์ในเอเชียตะวันออกเฉียงใต้',
                'language'      => 'th',
                'category'      => 'scam',
                'summary'       => 'การฉ้อโกงทางดิจิทัลสร้างความเสียหายกว่า 3 พันล้านดอลลาร์ต่อปีในเอเชียตะวันออกเฉียงใต้ เรียนรู้รูปแบบการหลอกลวง 5 รูปแบบที่พบบ่อยที่สุดก่อนที่จะสายเกินไป',
                'body_markdown' => <<<'MD'
## วิกฤตสุขภาพสาธารณะดิจิทัล

การหลอกลวงออนไลน์ไม่ใช่แค่ความไม่สะดวก — มันคือวิกฤตสุขภาพสาธารณะที่แท้จริง ในปี 2566 ความเสียหายจากการฉ้อโกงดิจิทัลในภูมิภาคนี้เกิน 3.2 พันล้านดอลลาร์

## 5 รูปแบบการหลอกลวงที่พบบ่อยที่สุด

### 1. ฟิชชิงทาง SMS (Smishing)
ข้อความที่แอบอ้างเป็นธนาคาร บริษัทขนส่ง หรือหน่วยงานรัฐบาล ขอให้คุณคลิกลิงก์เร่งด่วน ลิงก์เหล่านี้ขโมยข้อมูลประจำตัวหรือติดตั้งมัลแวร์

### 2. การฉ้อโกงการลงทุน (Pig Butchering)
คนแปลกหน้าสร้างความไว้วางใจเป็นเวลาหลายสัปดาห์แล้วชักชวนให้ลงทุนในแพลตฟอร์มปลอม ผลกำไรที่แสดงเป็นเพียงภาพลวงตา

### 3. การหลอกลวงทางความรัก
มิจฉาชีพมืออาชีพสร้างโปรไฟล์ปลอมที่น่าดึงดูด ลงทุนในความสัมพันธ์ทางอารมณ์ แล้วขอเงินโดยอ้างเหตุฉุกเฉิน

### 4. ข้อเสนองานปลอม
โฆษณาที่สัญญารายได้สูงจากการทำงานที่บ้านซ่อนการรับสมัคร money mule หรือกรณีร้ายแรงกว่า — การรับสมัครเข้าศูนย์หลอกลวงที่บังคับ

### 5. การปลอมตัวเป็นสถาบัน
มิจฉาชีพแอบอ้างเป็นธนาคารหรือหน่วยงานรัฐบาลเพื่อสร้างแรงกดดันเทียมและดึงข้อมูลส่วนตัวหรือการชำระเงิน

## สัญญาณเตือนสากล

- ความเร่งด่วนสูง: "ภายใน 24 ชั่วโมง", "ทันที"
- ขอ OTP, PIN หรือรหัสผ่าน — **ไม่มีสถาบันที่ถูกกฎหมายทำเช่นนี้**
- ลิงก์ไปยังโดเมนที่น่าสงสัยหรือบริการย่อ URL
- รับประกันผลตอบแทนโดยไม่มีความเสี่ยง — เป็นไปไม่ได้ในตลาดที่มีการกำกับดูแล

## สิ่งที่ต้องทำหากถูกตั้งเป้าหมาย

1. **อย่าคลิก**ลิงก์ — ตรวจสอบโดยตรงกับสถาบัน
2. **ถ่ายภาพหน้าจอ**ก่อนลบข้อความ
3. **รายงาน**ต่อหน่วยงานความมั่นคงไซเบอร์แห่งชาติ
4. **แชร์**ข้อมูล — ความรู้ที่แชร์ทุกชิ้นช่วยปกป้องคนอื่น
MD,
                'published_at'  => now()->subDays(13),
            ],

            [
                'slug'          => 'yuwachon-sathiraphap-digital-thai',
                'title'         => 'ความยืดหยุ่นทางดิจิทัลสำหรับเยาวชน: ห้านิสัยสำคัญเพื่อความปลอดภัยออนไลน์',
                'language'      => 'th',
                'category'      => 'youth_peace',
                'summary'       => 'เยาวชนอายุ 15-29 ปีเป็นผู้ใช้อินเทอร์เน็ตที่แอ็กทีฟที่สุด แต่ก็เปราะบางต่อการถูกจัดการทางดิจิทัลมากที่สุดด้วย ห้านิสัยง่ายๆ ที่ผ่านการพิสูจน์จากการวิจัยช่วยลดความเสี่ยงจากการหลอกลวงและการหัวรุนแรงได้อย่างมีนัยสำคัญ',
                'body_markdown' => <<<'MD'
## ความขัดแย้งทางดิจิทัลของเยาวชน

แพลตฟอร์มที่คุณใช้ถูกออกแบบมาเพื่อเพิ่มเวลาออนไลน์ของคุณ ไม่ใช่ความเป็นอยู่ที่ดีของคุณ การเข้าใจกลไกนี้คือก้าวแรกสู่ความยืดหยุ่นทางดิจิทัลที่แท้จริง

## ห้านิสัยหลัก

### นิสัยที่ 1: หยุดสามวินาที
ก่อนคลิก แชร์ หรือตอบกลับ — สามวินาทีเพียงพอที่จะเปิดใช้งานการคิดเชิงวิพากษ์ มิจฉาชีพพึ่งพาการกระทำด้วยแรงกระตุ้น

### นิสัยที่ 2: ตรวจสอบก่อนเชื่อ
ข้อมูลที่น่าประหลาดใจทุกชิ้นสมควรได้รับการตรวจสอบ ค้นหาชื่อแพลตฟอร์ม + "หลอกลวง" ชุมชนผู้เสียหายมักตอบสนองเร็วกว่าหน่วยงานของรัฐ

### นิสัยที่ 3: ตรวจสอบความเป็นส่วนตัวรายเดือน
ทุกเดือน ตรวจสอบว่าแอปใดมีสิทธิ์เข้าถึงกล้อง ไมโครโฟน และตำแหน่งของคุณ ปิดใช้งานสิทธิ์ที่ไม่จำเป็น

### นิสัยที่ 4: แยกอารมณ์ออกจากการตัดสินใจทางการเงิน
หากการโต้ตอบออนไลน์ทำให้เกิดอารมณ์รุนแรง ตามด้วยการขอเงินหรือข้อมูล — รอ 24 ชั่วโมง ความเร่งด่วนคือลายเซ็นของมิจฉาชีพ

### นิสัยที่ 5: แบ่งปันประสบการณ์ที่น่าสงสัย
ความอับอายนำไปสู่ความเงียบ แต่ประสบการณ์ที่แบ่งปันช่วยปกป้องผู้อื่น รายงาน แม้ว่าคุณจะไม่ได้เป็นเหยื่อ

## บทสรุป

การตกเป็นเหยื่อของการหลอกลวงไม่ใช่สัญญาณของความโง่เขลา — มันเป็นหลักฐานว่าคุณกำลังโต้ตอบกับระบบที่ออกแบบมาเพื่อแสวงหาประโยชน์จากความไว้วางใจของมนุษย์ ความยืดหยุ่นทางดิจิทัลหมายถึงการ**เฝ้าระวังโดยไม่สูญเสียความไว้วางใจในโลก**
MD,
                'published_at'  => now()->subDays(21),
            ],

            // ══════════════════════════════════════════════════════════════
            // TIẾNG VIỆT (vi)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'nhan-dien-lua-dao-truc-tuyen-dong-nam-a',
                'title'         => 'Nhận Diện Lừa Đảo Trực Tuyến ở Đông Nam Á: Năm Dấu Hiệu Cần Biết',
                'language'      => 'vi',
                'category'      => 'scam',
                'summary'       => 'Lừa đảo kỹ thuật số gây thiệt hại hơn 3 tỷ USD mỗi năm ở Đông Nam Á. Hãy học cách nhận biết năm mô hình lừa đảo phổ biến nhất — lừa đảo qua tin nhắn, đầu tư giả, lừa đảo tình cảm — trước khi quá muộn.',
                'body_markdown' => <<<'MD'
## Đại Dịch Kỹ Thuật Số

Lừa đảo trực tuyến không chỉ là sự bất tiện — đây là một cuộc khủng hoảng sức khỏe cộng đồng thực sự. Năm 2023, thiệt hại từ lừa đảo kỹ thuật số ở khu vực vượt 3,2 tỷ USD, ảnh hưởng đến hàng triệu người dân bình thường.

## Năm Mô Hình Lừa Đảo Phổ Biến Nhất

### 1. Lừa Đảo Qua SMS (Smishing)
Tin nhắn giả mạo ngân hàng, dịch vụ giao hàng hoặc cơ quan chính phủ yêu cầu bạn nhấp vào liên kết khẩn cấp. Các liên kết này đánh cắp thông tin đăng nhập hoặc cài phần mềm độc hại.

### 2. Lừa Đảo Đầu Tư (Mổ Heo)
Người lạ trực tuyến xây dựng niềm tin trong nhiều tuần, sau đó mời bạn đầu tư vào nền tảng giả mạo. Lợi nhuận hiển thị là ảo — tiền biến mất khi cố rút.

### 3. Lừa Đảo Tình Cảm
Kẻ lừa đảo chuyên nghiệp tạo hồ sơ giả hấp dẫn, đầu tư vào mối quan hệ cảm xúc, rồi yêu cầu tiền với lý do khẩn cấp.

### 4. Tin Tuyển Dụng Giả
Quảng cáo hứa thu nhập cao từ việc làm tại nhà thực ra ẩn giấu việc tuyển dụng vận chuyển tiền bất hợp pháp (money mule) hoặc nghiêm trọng hơn — lao động cưỡng bức.

### 5. Mạo Danh Tổ Chức
Kẻ lừa đảo giả mạo ngân hàng hoặc cơ quan chính phủ để tạo áp lực nhân tạo và lấy dữ liệu cá nhân hoặc tiền thanh toán.

## Dấu Hiệu Cảnh Báo Chung

- Áp lực cực độ: "trong vòng 24 giờ", "ngay lập tức"
- Yêu cầu OTP, PIN hoặc mật khẩu — **không tổ chức hợp pháp nào làm vậy**
- Liên kết đến tên miền đáng ngờ hoặc dịch vụ rút ngắn URL
- Lợi nhuận đảm bảo không rủi ro — không thể trong bất kỳ thị trường có quy định nào

## Phải Làm Gì Khi Bị Nhắm Đến

1. **Không nhấp** vào liên kết — xác minh trực tiếp với tổ chức qua kênh chính thức
2. **Chụp ảnh màn hình** trước khi xóa tin nhắn
3. **Báo cáo** cho cơ quan an ninh mạng quốc gia
4. **Chia sẻ** thông tin — mỗi kiến thức được chia sẻ bảo vệ một người khác
MD,
                'published_at'  => now()->subDays(14),
            ],

            [
                'slug'          => 'kha-nang-phuc-hoi-ky-thuat-so-gioi-tre',
                'title'         => 'Khả Năng Phục Hồi Kỹ Thuật Số cho Giới Trẻ: Năm Thói Quen Quan Trọng',
                'language'      => 'vi',
                'category'      => 'digital_resilience',
                'summary'       => 'Thanh niên từ 15-29 tuổi là người dùng internet tích cực nhất nhưng cũng dễ bị thao túng kỹ thuật số nhất. Năm thói quen đơn giản được nghiên cứu xác nhận giúp giảm đáng kể rủi ro lừa đảo và cực đoan hóa.',
                'body_markdown' => <<<'MD'
## Nghịch Lý Kỹ Thuật Số của Giới Trẻ

Các nền tảng bạn sử dụng được thiết kế để tối đa hóa thời gian trực tuyến của bạn — không phải sức khỏe của bạn. Hiểu cơ chế này là bước đầu tiên để có khả năng phục hồi kỹ thuật số thực sự.

## Năm Thói Quen Cốt Lõi

### Thói Quen 1: Tạm Dừng Ba Giây
Trước khi nhấp, chia sẻ hoặc trả lời — ba giây đủ để kích hoạt tư duy phản biện. Kẻ lừa đảo dựa vào sự bốc đồng.

### Thói Quen 2: Xác Minh Trước Khi Tin
Bất kỳ thông tin đáng ngạc nhiên nào cũng xứng đáng được xác minh. Tìm kiếm tên nền tảng + "lừa đảo". Cộng đồng nạn nhân thường phản hồi nhanh hơn cơ quan chức năng.

### Thói Quen 3: Kiểm Tra Quyền Riêng Tư Hàng Tháng
Mỗi tháng, kiểm tra ứng dụng nào có quyền truy cập camera, micro và vị trí của bạn. Tắt các quyền không cần thiết.

### Thói Quen 4: Tách Biệt Cảm Xúc Khỏi Quyết Định Tài Chính
Nếu tương tác trực tuyến gây ra cảm xúc mạnh, tiếp theo là yêu cầu tiền hoặc dữ liệu — hãy chờ 24 giờ. Sự khẩn cấp là dấu hiệu của kẻ lừa đảo.

### Thói Quen 5: Chia Sẻ Những Trải Nghiệm Đáng Ngờ
Xấu hổ dẫn đến im lặng. Nhưng mỗi trải nghiệm được chia sẻ bảo vệ người khác. Báo cáo, ngay cả khi bạn không phải nạn nhân.

## Kết Luận

Trở thành nạn nhân của lừa đảo không phải là dấu hiệu của sự ngốc nghếch — đó là bằng chứng bạn đang tương tác với các hệ thống được thiết kế để khai thác niềm tin của con người. Khả năng phục hồi kỹ thuật số có nghĩa là **cảnh giác mà không mất niềm tin vào thế giới**.
MD,
                'published_at'  => now()->subDays(22),
            ],

            // ══════════════════════════════════════════════════════════════
            // FILIPINO / TAGALOG (tl)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'pagtukoy-online-scam-timog-silangang-asya',
                'title'         => 'Pagtukoy ng mga Online Scam sa Timog-Silangang Asya: Limang Palatandaan',
                'language'      => 'tl',
                'category'      => 'scam',
                'summary'       => 'Ang digital na pandaraya ay nagdudulot ng higit sa 3 bilyong dolyar na pinsala bawat taon sa Timog-Silangang Asya. Matutunan kung paano tukuyin ang limang pinakakaraniwang uri ng scam bago pa mahuli ang lahat.',
                'body_markdown' => <<<'MD'
## Isang Digital na Epidemya

Ang mga online na scam ay hindi lamang abala — ito ay isang tunay na krisis sa pampublikong kalusugan. Noong 2023, ang mga pagkalugi mula sa digital na pandaraya sa rehiyon ay lumampas sa 3.2 bilyong dolyar, na nakaapekto sa milyun-milyong ordinaryong mamamayan.

## Limang Pinakakaraniwang Uri ng Scam

### 1. SMS Phishing (Smishing)
Ang mga mensaheng nagpapanggap bilang inyong bangko, serbisyo ng pagpapadala, o ahensya ng gobyerno ay humihiling sa inyo na mag-click ng isang urgent na link. Ang mga link na ito ay nagnanakaw ng inyong mga kredensyal o nag-i-install ng malware.

### 2. Investment Scam (Pig Butchering)
Isang estranyo sa online ay nagtatayo ng tiwala sa loob ng ilang linggo, pagkatapos ay inimbitahan kayong mag-invest sa isang pekeng platform. Ang mga ipinakitang kita ay huwad — ang pera ay nawawala kapag sinubukang i-withdraw.

### 3. Romance Scam
Ang mga propesyonal na manloloko ay gumagawa ng mga kaakit-akit na pekeng profile, namumuhunan sa isang emosyonal na relasyon, at pagkatapos ay humihingi ng pera dahil sa diumano'y emergency.

### 4. Mga Pekeng Job Offer
Ang mga advertiserment na nangangako ng mataas na kita mula sa bahay ay nagtatago ng pagre-recruit bilang money mule o, sa mas malubhang kaso, sapilitang trabaho sa mga documented na fraud center.

### 5. Pagpapanggap sa Institusyon
Ang mga manloloko ay nagpapanggap na mga bangko o ahensya ng gobyerno upang lumikha ng artipisyal na presyur at makuha ang personal na impormasyon o bayad.

## Mga Universal na Babala

- Matinding pagkaapurahan: "sa loob ng 24 na oras", "agad"
- Paghingi ng OTP, PIN, o password — **walang lehitimong institusyon ang gumagawa nito**
- Link sa kahina-hinalang domain o URL shortener
- Garantisadong kita nang walang panganib — imposible sa anumang regulated na merkado

## Ano ang Gagawin Kapag Tinargeto Kayo

1. **Huwag mag-click** ng link — direktang i-verify sa institusyon
2. **Mag-screenshot** bago burahin ang mensahe
3. **Iulat** sa pambansang cybersecurity na awtoridad
4. **I-share** ang impormasyon — ang bawat pinagsamang kaalaman ay nagpoprotekta sa iba
MD,
                'published_at'  => now()->subDays(15),
            ],

            [
                'slug'          => 'digital-resilience-kabataan-pilipinas',
                'title'         => 'Digital Resilience para sa Kabataan: Limang Mahahalagang Gawi',
                'language'      => 'tl',
                'category'      => 'youth_peace',
                'summary'       => 'Ang kabataang 15-29 taong gulang ay ang pinaka-aktibong gumagamit ng internet ngunit pati na rin ang pinakamarupok sa digital na manipulasyon. Ang limang simpleng gawi na pinatunayan ng pananaliksik ay makabuluhang nagpapababa ng mga panganib ng scam at radicalization.',
                'body_markdown' => <<<'MD'
## Ang Digital na Paradox ng Kabataan

Ang mga platform na ginagamit ninyo ay dinisenyo upang ma-maximize ang inyong oras sa online — hindi ang inyong kagalingan. Ang pag-unawa sa mekanismong ito ang unang hakbang patungo sa tunay na digital resilience.

## Limang Pangunahing Gawi

### Gawi 1: Tatlong Segundong Paghinto
Bago mag-click, mag-share, o tumugon — tatlong segundo ang sapat upang ma-activate ang kritikal na pag-iisip. Ang mga manloloko ay umaasa sa pagiging maagap.

### Gawi 2: I-verify Bago Maniwala
Ang anumang nakakagulat na impormasyon ay nararapat na i-verify. Hanapin ang pangalan ng platform + "scam". Ang mga komunidad ng biktima ay madalas na tumutugon nang mas mabilis kaysa sa mga awtoridad.

### Gawi 3: Buwanang Privacy Audit
Bawat buwan, suriin kung aling mga app ang may access sa inyong camera, mikropono, at lokasyon. I-disable ang mga hindi kinakailangang pahintulot.

### Gawi 4: Paghiwalayin ang Emosyon mula sa Desisyong Pinansyal
Kung ang isang online na pakikipag-ugnayan ay nagdudulot ng matinding emosyon, sinusundan ng kahilingan para sa pera o data — maghintay ng 24 na oras. Ang pagkaapurahan ay ang tanda ng isang manloloko.

### Gawi 5: I-share ang mga Kahina-hinalang Karanasan
Ang kahihiyan ay humahantong sa katahimikan. Ngunit ang bawat naibahaging karanasan ay nagpoprotekta sa iba. Mag-ulat, kahit hindi kayo naging biktima.

## Konklusyon

Ang pagiging biktima ng scam ay hindi palatandaan ng kahangalan — ito ay patunay na nakikipag-ugnayan kayo sa mga sistemang dinisenyo upang pagsamantalahan ang tiwala ng tao. Ang digital resilience ay nangangahulugang maging **alerto nang hindi nawawalan ng tiwala sa mundo**.
MD,
                'published_at'  => now()->subDays(23),
            ],

            // ══════════════════════════════════════════════════════════════
            // ភាសាខ្មែរ (km)
            // ══════════════════════════════════════════════════════════════

            [
                'slug'          => 'chneanh-pongteas-anline-asean-km',
                'title'         => 'ការកំណត់អត្តសញ្ញាណការបោកប្រាស់អនឡាញនៅអាស៊ីអាគ្នេយ៍: សញ្ញាប្រាំដែលត្រូវដឹង',
                'language'      => 'km',
                'category'      => 'scam',
                'summary'       => 'ការបោកប្រាស់ឌីជីថលបង្ករការខាតបង់ជាង 3 ពាន់លានដុល្លារក្នុងមួយឆ្នាំនៅអាស៊ីអាគ្នេយ៍។ ស្វែងយល់ពីរបៀបកំណត់អត្តសញ្ញាណប្រភេទការបោកប្រាស់ទំនើបទាំង 5 ប្រភេទ មុននឹងយឺតពេល។',
                'body_markdown' => <<<'MD'
## វិបត្តិសុខភាពសាធារណៈឌីជីថល

ការបោកប្រាស់អនឡាញមិនមែនគ្រាន់តែជាការរំខានប៉ុណ្ណោះទេ — វាជាវិបត្តិសុខភាពសាធារណៈពិតប្រាកដ។ ក្នុងឆ្នាំ 2023 ការខាតបង់ពីការបោកប្រាស់ឌីជីថលនៅក្នុងតំបន់នេះបានលើសពី 3.2 ពាន់លានដុល្លារ ដែលប៉ះពាល់ដល់ប្រជាជនធម្មតារាប់លាននាក់។

## ប្រភេទការបោកប្រាស់ទំនើបទាំង 5

### 1. ការបោកប្រាស់តាម SMS (Smishing)
សារដែលក្លែងធ្វើជាធនាគារ សេវាដឹកជញ្ជូន ឬភ្នាក់ងាររដ្ឋាភិបាល ស្នើឱ្យអ្នកចុចលើតំណភ្ជាប់បន្ទាន់។ តំណទាំងនេះលួចព័ត៌មានសម្ងាត់ឬដំឡើង malware ។

### 2. ការបោកប្រាស់វិនិយោគ (Pig Butchering)
មនុស្សចំណេះអាស្រ័យបង្កើតទំនុកចិត្តរយៈពេលជាច្រើនសប្តាហ៍ បន្ទាប់មកអ邀ឱ្យអ្នកវិនិយោគនៅលើវេទិកាក្លែងក្លាយ។ ផលចំណេញដែលបង្ហាញគឺបំភ្លឺ ហើយប្រាក់បាត់នៅពេលព្យាយាមដកប្រាក់ចេញ។

### 3. ការបោកប្រាស់ស្នេហ
អ្នកបោកប្រាស់វិជ្ជាជីវៈបង្កើតទម្រង់ក្លែងក្លាយដ៏គួរឱ្យទាក់ទាញ វិនិយោគលើទំនាក់ទំនងអារម្មណ៍ ហើយបន្ទាប់មកស្នើប្រាក់ដោយអ្នូតហេតុបន្ទាន់។

### 4. ការជ្រើសរើសសន្លប់
ការផ្សព្វផ្សាយដែលសន្យាប្រាក់ចំណូលខ្ពស់ពីការធ្វើការនៅផ្ទះ ពិតជាលាក់ទុកការជ្រើសរើសសម្រាប់ money mule ឬ ករណីធ្ងន់ធ្ងរជាងនេះ — ការងារដោយបង្ខំ។

### 5. ការក្លែងធ្វើស្ថាប័ន
អ្នកបោកប្រាស់ក្លែងធ្វើជាធនាគារឬស្ថាប័នរដ្ឋ ដើម្បីបង្កើតសម្ពាធសិប្បនិម្មិត ហើយទទួលបានទិន្នន័យផ្ទាល់ខ្លួន ឬការបង់ប្រាក់។

## សញ្ញាព្រមានទូទៅ

- ភាពបន្ទាន់ខ្លាំង: "ក្នុងរយៈពេល 24 ម៉ោង", "ភ្លាមៗ"
- ស្នើ OTP PIN ឬពាក្យសម្ងាត់ — **គ្មានស្ថាប័នស្របច្បាប់ណាមួយធ្វើដូច្នេះទេ**
- តំណភ្ជាប់ទៅកាន់ដែនគួរឱ្យសង្ស័យ ឬសេវាខ្លី URL
- ប្រាក់ចំណេញធានាដោយគ្មានហានិភ័យ — មិនអាចកើតឡើងបានក្នុងទីផ្សារដែលមានការអនុញ្ញាតណាមួយ

## ត្រូវធ្វើអ្វីនៅពេលត្រូវបោកប្រាស់

1. **កុំចុច**លើតំណ — ផ្ទៀងផ្ទាត់ដោយផ្ទាល់ជាមួយស្ថាប័ន
2. **ថតរូបអេក្រង**មុននឹងលប់សារ
3. **រាយការណ៍**ទៅភ្នាក់ងារសន្តិសុខតាមអ៊ីនធឺណិតជាតិ
4. **ចែករំលែក**ព័ត៌មាន — ចំណេះដឹងដែលចែករំលែករាល់ការការពារអ្នកផ្សេង
MD,
                'published_at'  => now()->subDays(16),
            ],

            [
                'slug'          => 'sathiraphap-digital-yuvajun-km',
                'title'         => 'ភាពធន់នឹងឌីជីថលសម្រាប់យុវវ័យ: ទម្លាប់ប្រាំដែលសំខាន់ក្នុងការការពារខ្លួន',
                'language'      => 'km',
                'category'      => 'digital_resilience',
                'summary'       => 'យុវវ័យអាយុ 15-29 ឆ្នាំគឺជាអ្នកប្រើប្រាស់អ៊ីនធឺណិតសកម្មបំផុត ប៉ុន្តែក៏ងាយរងគ្រោះបំផុតពីការឧបាយកលឌីជីថល។ ទម្លាប់ប្រាំដែលបញ្ជាក់ដោយការស្រាវជ្រាវជួយកាត់បន្ថយហានិភ័យការបោកប្រាស់ និងការប្រទូស្ដររ៉ាឌីខល.',
                'body_markdown' => <<<'MD'
## ភាពផ្ទុយគ្នានៃឌីជីថលរបស់យុវវ័យ

វេទិកាដែលអ្នកប្រើត្រូវបានរចនាឡើងដើម្បីបង្កើនពេលវេលាអនឡាញរបស់អ្នក — មិនមែនសុខភាពរបស់អ្នកទេ។ ការយល់ពីយន្តការនេះគឺជាជំហានដំបូងឆ្ពោះទៅរកភាពធន់ឌីជីថលពិតប្រាកដ។

## ទម្លាប់ស្នូលប្រាំ

### ទម្លាប់ទី 1: ឈប់បីវិនាទី
មុនពេលចុច ចែករំលែក ឬឆ្លើយ — បីវិនាទីគ្រប់គ្រាន់ក្នុងការដំណើរការការគិតបែបរិះគន់។ អ្នកបោកប្រាស់ពឹងផ្អែកលើអារម្មណ៍ស្ងោរ។

### ទម្លាប់ទី 2: ផ្ទៀងផ្ទាត់មុនពេលជឿ
ព័ត៌មានដ៏គួរឱ្យភ្ញាក់ផ្អើលណាមួយសមនឹងត្រូវបានផ្ទៀងផ្ទាត់។ ស្វែងរកឈ្មោះវេទិកា + "ការបោកប្រាស់"។ សហគមន៍ជនរងគ្រោះជារឿយៗឆ្លើយតបលឿនជាងអាជ្ញាធរ។

### ទម្លាប់ទី 3: ការត្រួតពិនិត្យឯកជននៃភាពសំខាន់ជារៀងរាល់ខែ
រៀងរាល់ខែ ត្រួតពិនិត្យថាកម្មវិធីណាមានសិទ្ធិចូលប្រើកាមេរ៉ា មីក្រូហ្វូន និងទីតាំងរបស់អ្នក។ បិទការអនុញ្ញាតដែលមិនចាំបាច់។

### ទម្លាប់ទី 4: បំបែកអារម្មណ៍ចេញពីការសម្រេចចិត្តហិរញ្ញវត្ថុ
ប្រសិនបើអន្តរកម្មអនឡាញបង្ករអារម្មណ៍ខ្លាំង បន្ទាប់ពីនោះការស្នើប្រាក់ ឬទិន្នន័យ — រង់ចាំ 24 ម៉ោង។ ភាពបន្ទាន់គឺជាហត្ថលេខារបស់អ្នកបោកប្រាស់។

### ទម្លាប់ទី 5: ចែករំលែកបទពិសោធន៍គួរឱ្យសង្ស័យ
ការអៀនខ្មាស់នាំទៅរកភាពស្ងប់ស្ងាត់។ ប៉ុន្តែបទពិសោធន៍ដែលបានចែករំលែករាល់ប្រជ្ជាអ្នកផ្សេង។ រាយការណ៍ ទោះបីជាអ្នកមិនបានក្លាយជាជនរងគ្រោះ។

## សេចក្ដីសន្និដ្ឋាន

ការក្លាយជាជនរងគ្រោះពីការបោកប្រាស់មិនមែនជាសញ្ញានៃភាពល្ងង់ខ្លៅ — វាជាភស្ដុតាងដែលអ្នកកំពុងទំនាក់ទំនងជាមួយប្រព័ន្ធដែលត្រូវបានរចនាឡើងដើម្បីជិះជាន់ទំនុកចិត្តរបស់មនុស្ស។ ភាពធន់ឌីជីថលមានន័យថា**ស្ថិតនៅយ៉ាងដឹងខ្លួនដោយមិនបាត់បង់ទំនុកចិត្តលើពិភពលោក**។
MD,
                'published_at'  => now()->subDays(24),
            ],

        ]; // end return
    }
}