<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 5 articles based on verified 2025-2026 sources:
 *   - TPPO Dumai (Polda Riau, InfoPublik, April 2026)
 *   - WNI scam compounds Cambodia (KBRI Phnom Penh, Kemlu, Jan-May 2026)
 *   - Love scam syndicate Tangerang (Ditjen Imigrasi, Jan 2026)
 *   - ASEAN human trafficking (Modern Diplomacy, March 2025)
 *
 * NOTE: All PHP strings use double quotes to avoid apostrophe parse errors.
 *
 * Run: php artisan db:seed --class=ArticleSeederNewCases --force
 */
class ArticleSeederNewCases extends Seeder
{
    public function run(): void
    {
        foreach ($this->articles() as $article) {
            DB::table('articles')->insertOrIgnore(array_merge($article, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        $this->command->info("Done. Total articles: " . DB::table('articles')->count());
    }

    private function articles(): array
    {
        return [

            // ════════════════════════════════════════════════════════════
            // ENGLISH — Article 1
            // ════════════════════════════════════════════════════════════
            [
                'slug'     => 'human-trafficking-migrant-worker-recruitment-indonesia',
                'title'    => "How Human Trafficking Syndicates Recruit Indonesian Migrant Workers: The Dumai Pattern",
                'language' => 'en',
                'category' => 'scam',
                'summary'  => "In April 2026, police in Dumai, Riau rescued 29 illegal migrant workers from a trafficking syndicate charging Rp 12-16 million per victim. The pattern repeats across Riau: systematic recruitment, holding houses, and illegal departure routes. This is how it works — and how to spot it before it is too late.",
                'body_markdown' => "## A Pattern That Keeps Repeating

On 24 April 2026, at 3 AM, a police team in Dumai, Riau stopped a suspicious vehicle on Jalan Raya Lubuk Gaung. Inside were nine Indonesian citizens packed into a small car, en route to an illegal holding house in Batu Teritip. By the time officers raided the shelter, 29 people in total had been rescued — most from Nusa Tenggara Barat, all lured by promises of overseas work.

\"Although the perpetrators were different, the modus operandi remained identical: recruit, shelter, and promise non-procedural departure,\" said Kombes Pol Hasyim Risahondua, Director of General Criminal Investigation at Polda Riau.

This was not an isolated case. The same pattern had been recorded in December 2025 (five victims en route to Cambodia), September 2025 (nine victims to Malaysia), and multiple earlier operations in the same Sungai Sembilan sub-district.

## How the Syndicate Operates

### Step 1: Recruitment Through Social Networks
Recruiters — often community members or informal labour brokers — approach targets in their home areas. The pitch is simple: good pay, fast process, no complicated paperwork. Victims from economically marginal areas in NTB, Aceh, and Java are most frequently targeted.

### Step 2: Collection and Holding
Victims pay between Rp 12 million and Rp 16 million per person as a processing fee. They are then transported to unofficial holding locations — often ordinary houses in residential areas near the coast — where they wait for departure.

### Step 3: Illegal Departure
Departure happens through so-called \"tikus\" (rat) routes: small coastal vessels departing from unmonitored beaches, bypassing official ports and immigration checkpoints. Destinations include Malaysia, Cambodia, and beyond.

### Step 4: Vulnerability Abroad
Once abroad without legal documents, victims have no legal protection. Some end up in legitimate low-wage work; others are sold to scam compound operators or exploited in worse conditions.

## The Legal Framework

Under Indonesian Law No. 18/2017 on the Protection of Migrant Workers and Law No. 21/2007 on Trafficking in Persons (TPPO), syndicate members face a maximum of 15 years imprisonment. Victims who paid fees are also legally at risk if they knowingly departed through illegal channels, though prosecutors typically focus on network organisers.

## How to Protect Yourself and Your Family

- **Verify all job offers** through the official BP3MI (Badan Pelindungan Pekerja Migran Indonesia) portal: bp2mi.go.id
- **Legitimate overseas work never requires large upfront fees** — processing costs are regulated and documented
- **Report suspicious recruitment activity** to BP3MI Riau at bp3mi.riau@gmail.com or the national TPPO hotline: 119 ext 5
- **A community member asking for secrecy about departure is a red flag** — legitimate departures go through official channels with family knowledge

## Report Anonymously

If you know of someone being recruited through illegal channels, or if you have been approached yourself, use SafePulse to submit an anonymous incident report. Your report can help map syndicate activity and alert authorities.

> **Sources:** InfoPublik / Polda Riau (24 April 2026), BP3MI Riau (September and December 2025)",
                'published_at' => now()->subDays(3),
            ],

            // ════════════════════════════════════════════════════════════
            // ENGLISH — Article 2
            // ════════════════════════════════════════════════════════════
            [
                'slug'     => 'love-scam-ai-syndicate-tangerang-indonesia-2026',
                'title'    => "AI-Powered Love Scam Syndicate Dismantled in Tangerang: 27 Foreign Nationals Detained",
                'language' => 'en',
                'category' => 'scam',
                'summary'  => "In January 2026, Indonesian immigration authorities dismantled an international love scam syndicate in Tangerang operating with artificial intelligence. The network used Hello GPT to make conversations appear human, then sent compromising materials to extort victims. Understanding how it worked can help you recognise similar approaches.",
                'body_markdown' => "## What Happened

Between 8 and 16 January 2026, the Directorate General of Immigration (Ditjen Imigrasi) conducted a series of raids across Gading Serpong and BSD, Tangerang, dismantling an international cybercrime syndicate operating through love scamming. Twenty-seven foreign nationals — 26 Chinese citizens and one Vietnamese citizen — were detained.

\"Our team secured computers, mobile phones, and two Chinese passports at the first location. The network operated in a highly organised manner using artificial intelligence,\" said Acting Director General of Immigration Yuldi Yusman.

## How the Syndicate Used AI

This case marked a significant escalation: the network deployed **Hello GPT**, an AI tool, to generate convincing romantic conversations that were indistinguishable from human interaction. The modus operandi followed a three-stage process:

**Stage 1 — AI-generated contact.** Victims were identified through social media profiling. The syndicate used AI to initiate and sustain romantic conversations over extended periods, making victims believe they were speaking with a genuine person.

**Stage 2 — Escalation.** Once emotional trust was established, syndicate members sent compromising or sexually suggestive images to draw victims into video calls.

**Stage 3 — Extortion.** During video calls, victims were recorded or their images were captured. The syndicate then threatened to share the material publicly unless victims paid. This practice is known as sextortion.

## The Transnational Network

Investigators found that the syndicate was controlled by a financier in China identified by the initials ZH. Indonesian operations were managed by ZK, with field operatives ZJ (alias Titi) and a married couple CZ and BZ. This cross-border structure — funding and direction from abroad, operations inside Indonesia — is characteristic of organised cybercrime networks across Southeast Asia.

## Why AI Changes the Threat Landscape

Traditional romance scams relied on human operators managing multiple victims. AI tools like Hello GPT allow a single operator to maintain dozens of simultaneous emotionally convincing conversations. This dramatically scales the operational capacity of scam syndicates at minimal additional cost.

The implications are significant for anyone interacting with romantic contacts online:

- The emotional warmth you feel in a conversation may be entirely AI-generated
- Response speed, emotional attunement, and personal recall are no longer proof of human presence
- The only reliable verification is video call with face, in real time, ideally with a live verification challenge

## Protecting Yourself from AI-Assisted Romance Scams

1. **Request a live, unscripted video call early** — ask them to hold up three fingers or say a random phrase
2. **Reverse image search profile photos** — AI-generated faces can be detected at FaceCheck.id or Google Lens
3. **Never share intimate images** with people you have not met in person
4. **If compromising material is threatened**, do not pay — contact Bareskrim Polri (bareskrim.polri.go.id) or the National Cybercrime Hotline: 110
5. **Save all evidence** — screenshots of conversations, transfer receipts, account information

> **Sources:** Ditjen Imigrasi press release (19 January 2026), Media Indonesia (19 January 2026)",
                'published_at' => now()->subDays(5),
            ],

            // ════════════════════════════════════════════════════════════
            // ENGLISH — Article 3
            // ════════════════════════════════════════════════════════════
            [
                'slug'     => 'indonesians-trapped-cambodia-scam-compounds-2026',
                'title'    => "8,000 Indonesians Trapped in Cambodian Scam Compounds: A Public Health Emergency",
                'language' => 'en',
                'category' => 'scam',
                'summary'  => "By May 2026, over 8,000 Indonesian citizens had reported to the Indonesian Embassy in Phnom Penh after escaping online fraud syndicates in Cambodia. The numbers surpassed the entire caseload of 2025 in just four months. This article explains how people are recruited, what conditions they face, and how to get help.",
                'body_markdown' => "## The Scale of the Crisis

By 5 May 2026, the Indonesian Embassy (KBRI) in Phnom Penh had recorded 8,002 Indonesian citizens requesting repatriation assistance after leaving online fraud syndicates operating in Cambodia. Of those, 3,348 had already been facilitated to return to Indonesia.

The lonjakan (surge) began on 16 January 2026, when the Cambodian government launched intensive operations against fraud networks. What followed was unprecedented: more than 100 Indonesians were reporting to KBRI daily, with a peak of 180 in a single day on 5 May 2026.

To put this in perspective: the total caseload for all of 2025 was 5,088 people. By March 2026, that number had already been surpassed.

## How People End Up There

The majority of Indonesian citizens in Cambodian scam compounds were recruited through the same mechanism documented across Southeast Asia: **false job offers**.

Promises of high-paying work in tourism, customer service, or technology drew applicants, often young people with limited formal employment prospects. Many paid brokers significant sums for placement fees. Upon arrival — typically after transiting through Malaysia or Thailand — they were transferred to compounds in Cambodia, Myanmar, or Laos, where their documents were confiscated.

Once inside, workers were compelled to conduct online scams targeting victims globally, including Indonesians, Chinese, and Western targets. Failure to meet daily quotas could result in penalties, restricted food, or threats of violence.

## Who Is Vulnerable

Data from KBRI Phnom Penh and Indonesian government sources consistently identifies the same risk profile:

- Young people aged 18-30 from lower-income households
- Those with limited formal employment experience
- Individuals actively seeking overseas work through informal channels
- Those who encountered job offers through social media rather than official agencies

## The Process of Return

For citizens who managed to leave compounds and reach KBRI, the repatriation process involved:

1. Documentation — many had no passports; KBRI issued Temporary Travel Documents (SPLP)
2. Overstay fine negotiations — the Cambodian government granted waivers to over 3,200 Indonesians
3. Immigration assessment on return — at Soekarno-Hatta International Airport, returnees were assessed by Bareskrim and other agencies to determine degree of involvement in fraud activities
4. Referral to relevant support services

## How to Protect Yourself and Your Family

- **Verify overseas job offers** through BP2MI (bp2mi.go.id) before committing to any agency
- **No legitimate employer requires you to surrender your passport** upon arrival
- **Contact BP3MI or KBRI immediately** if you are abroad and feel unsafe: KBRI Phnom Penh emergency line +855 23 217 934
- **Report suspicious recruitment** via SafePulse incident reporter or the National TPPO hotline: 119 ext 5
- **If a family member is missing** after taking up an overseas job offer, contact the Kemlu Hotline: 1500-454

> **Sources:** KBRI Phnom Penh official statements (January-May 2026), Kemlu RI, ANTARA, Lingkar News",
                'published_at' => now()->subDays(7),
            ],

            // ════════════════════════════════════════════════════════════
            // FRANÇAIS — Article 1
            // ════════════════════════════════════════════════════════════
            [
                'slug'     => 'traite-humains-centres-escroquerie-asie-du-sud-est',
                'title'    => "Traite des Personnes et Centres d'Escroquerie en Asie du Sud-Est: Ce Que Chaque Travailleur Doit Savoir",
                'language' => 'fr',
                'category' => 'scam',
                'summary'  => "Plus de 8 000 Indonesiens ont fui des centres d'escroquerie au Cambodge en 2026. Des milliers d'autres restent pieges en Birmanie, au Laos et en Malaisie. Ces reseaux recrutent via de fausses offres d'emploi sur les reseaux sociaux. Voici comment ils operent et comment vous proteger.",
                'body_markdown' => "## Une Crise de Sante Publique Regionale

En mai 2026, 8 002 citoyens indonesiens avaient contacte l'ambassade d'Indonesie (KBRI) a Phnom Penh, au Cambodge, pour demander une assistance au rapatriement apres avoir quitte des reseaux de fraude en ligne. Ce chiffre depasse le total des cas traites pendant toute l'annee 2025 — en seulement quatre mois.

Cette crise n'est pas propre a l'Indonesie. L'ONUDC (Office des Nations Unies contre la drogue et le crime) documente des centres similaires en Birmanie (region de l'Etat Shan), au Laos, en Malaisie et aux Philippines. Les victimes viennent de toute l'Asie du Sud-Est, mais aussi de Chine, d'Afrique, et — de plus en plus — d'Europe et des Ameriques.

## Comment Fonctionne le Recrutement

### La Fausse Offre d'Emploi

Le recrutement commence presque toujours par une annonce attrayante: traducteur, responsable clientele, agent de reservation en ligne, technicien. Les salaires proposes sont deux a quatre fois superieurs aux niveaux locaux. Les annonces circulent sur Facebook, TikTok, Telegram et WhatsApp — souvent relayees par des membres de la communaute qui ont eux-memes ete recrutes.

### Le Passage de Frontiere

Les victimes voyagent legalement dans un premier temps — vers la Malaisie, la Thailande ou le Vietnam. Leur passeport est confisque a l'arrivee. Elles sont ensuite transportees par voie terrestre vers des zones frontieres peu controlees.

### L'Enfermement dans le Compound

Une fois dans le compound, les travailleurs sont contraints de realiser des escroqueries en ligne. Les quotas journaliers sont fixes. L'echec a atteindre ces quotas peut entrainer des restrictions alimentaires, des violences physiques ou des penalites financieres.

## Qui Est Vulnerable

Selon les donnees des ambassades et des ONG de la region:

- Jeunes adultes de 18 a 30 ans avec peu d'opportunites d'emploi formel
- Personnes ayant repondu a des offres d'emploi sur les reseaux sociaux sans verification prealable
- Ceux qui ont confie leur organisation de voyage a un tiers
- Personnes dont un proche a deja travaille a l'etranger (les reseaux exploitent les references existantes)

## Comment se Proteger

- **Verifiez toute offre d'emploi** aupres des agences officielles de votre pays avant tout engagement
- **Aucun employeur legitime ne confisque votre passeport** a l'arrivee — c'est illegal dans tous les pays ASEAN
- **Signalez immediatement** si vous vous sentez en danger a l'etranger: contactez votre ambassade
- **Si un proche a disparu** apres avoir accepte une offre d'emploi a l'etranger, contactez la police et le ministere des affaires etrangeres sans delai

## Signaler de Facon Anonyme

SafePulse permet de signaler des tentatives de recrutement suspectes de maniere completement anonyme. Chaque signalement aide a cartographier les reseaux actifs et a alerter les autorites.

> **Sources:** KBRI Phnom Penh (janvier-mai 2026), Lingkar News, ANTARA, Modern Diplomacy (mars 2025)",
                'published_at' => now()->subDays(9),
            ],

            // ════════════════════════════════════════════════════════════
            // FRANÇAIS — Article 2
            // ════════════════════════════════════════════════════════════
            [
                'slug'     => 'escroquerie-amoureuse-ia-syndicats-indonesie-2026',
                'title'    => "Escroqueries Amoureuses Pilotees par l'IA: Comment les Syndicats Operent en Indonesie",
                'language' => 'fr',
                'category' => 'scam',
                'summary'  => "En janvier 2026, les autorites indonesiennes ont demantele un reseau international de love scamming a Tangerang utilisant une IA pour simuler des conversations romantiques. 27 ressortissants etrangers ont ete interpelles. Comprendre ce mecanisme permet de mieux se proteger.",
                'body_markdown' => "## L'Affaire de Tangerang: Quand l'IA Sert les Escrocs

Entre le 8 et le 16 janvier 2026, la Direction generale de l'Immigration indonesienne (Ditjen Imigrasi) a mene une serie de raids a Gading Serpong et BSD, dans la region de Tangerang, Banten. Au terme de ces operations, 27 ressortissants etrangers — 26 Chinois et un Vietnamien — ont ete interpelles et places en detention.

Ce qui rendait ce reseau particulierement dangereux etait son utilisation de **Hello GPT**, un outil d'intelligence artificielle permettant de generer des conversations romantiques indiscernables de celles d'un etre humain.

## Le Mecanisme en Trois Etapes

### Etape 1: Contact Genere par IA

Le reseau identifiait des victimes potentielles via les reseaux sociaux. Un outil d'IA prenait ensuite en charge les echanges initiaux: messages chaleureux, questions personnalisees, humour contextuel. Les victimes croyaient interagir avec une vraie personne.

### Etape 2: Escalade Emotionnelle et Materiel Compromettant

Une fois la confiance etablie, les membres du reseau envoyaient des photos a caractere suggestif pour inciter la victime a participer a des appels video.

### Etape 3: Extorsion (Sextorsion)

Pendant les appels video, les images des victimes etaient capturees a leur insu. Le reseau menacait ensuite de diffuser ce materiel — aupres de la famille, des collegues, ou publiquement — sauf paiement d'une somme. Ce type d'escroquerie est communement appele sextorsion.

## Pourquoi l'IA Change la Donne

Avant l'avènement des outils d'IA generative, chaque operateur de romance scam ne pouvait gerer qu'un nombre limite de victimes simultanement. Hello GPT et des outils similaires permettent desormais a un seul operateur de maintenir des dizaines de conversations emotionnellement convaincantes en parallele.

Cela signifie que les signaux auxquels vous faisiez confiance — rapidite de reponse, memorisation de details personnels, fluidite emotionnelle — ne sont plus des indicateurs fiables de la presence d'un etre humain.

## Comment Vous Proteger

- **Demandez un appel video en direct non scenarise** des le debut: demandez a la personne de lever trois doigts ou de prononcer une phrase aleatoire que vous choisissez
- **Faites une recherche inverse d'image** sur les photos de profil (Google Lens, FaceCheck.id) — les visages generes par IA peuvent souvent etre detectes
- **Ne partagez jamais d'images intimes** avec quelqu'un que vous n'avez pas rencontre physiquement
- **Si vous faites l'objet d'un chantage**, ne payez pas — signalez a la police nationale et conservez toutes les preuves
- **Rappelez-vous**: la chaleur emotionnelle dans une conversation peut etre entierement generee par algorithme

## Signalement et Soutien

Si vous avez ete cible par une escroquerie amoureuse ou si vous connaissez quelqu'un dans cette situation, signalez via SafePulse de maniere anonyme. En Indonesie, contactez egalement Bareskrim Polri (bareskrim.polri.go.id) ou la hotline nationale: 110.

> **Sources:** Ditjen Imigrasi RI (19 janvier 2026), Media Indonesia, Jakarta Selatan Immigration Office (EN version)",
                'published_at' => now()->subDays(11),
            ],

        ];
    }
}