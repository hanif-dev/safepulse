import { useTranslation } from 'react-i18next';
import SectionHero from '../components/SectionHero';

const STUDIES = [
  {
    id: 1,
    tag: 'Framework Alignment',
    flag: '🇫🇷',
    title: 'ANSSI SecNumCloud — Privacy-by-Design Architecture',
    org: 'SafePulse × ANSSI (Agence Nationale de la Sécurité des Systèmes d\'Information, Paris)',
    summary: 'SafePulse\'s detection engine and data handling follow ANSSI\'s SecNumCloud methodology for citizen-grade cybersecurity: transparency-first design, no content retention, minimal data collection, and verifiable threat communication aligned with French national standards.',
    outcomes: ['Zero content storage policy', 'Ephemeral analysis pipeline', 'Transparent risk reasoning'],
    category: 'French Framework',
    color: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
    tagColor: 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
  },
  {
    id: 2,
    tag: 'Framework Alignment',
    flag: '🇫🇷',
    title: 'INTERPOL I-GRIP — Anti-Fraud Taxonomy & Incident Reporting',
    org: 'SafePulse × INTERPOL (Global Rapid Intervention of Payments — Lyon, France)',
    summary: 'SafePulse\'s threat categories — phishing, investment fraud, romance scams, money mule recruitment, and radicalization — align directly with INTERPOL\'s I-GRIP anti-fraud framework. Anonymous incident reports are structured to be compatible with law-enforcement data standards, enabling future direct reporting integration.',
    outcomes: ['I-GRIP aligned threat taxonomy', 'Law-enforcement-ready data structure', 'Future INTERPOL integration pathway'],
    category: 'French Framework',
    color: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
    tagColor: 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
  },
  {
    id: 3,
    tag: 'AI Innovation',
    flag: '🇫🇷',
    title: 'Mistral AI Integration — Multilingual Context-Aware Scam Detection',
    org: 'SafePulse × Mistral AI (Paris, France · Founded 2023)',
    summary: 'SafePulse integrates Mistral AI\'s mistral-small-latest model as a second detection layer on top of its rule-based engine. Mistral understands deceptive context — "this is 100% safe, trust me" — and analyses scam patterns across 12 languages simultaneously, including Indonesian, French, Arabic, and Javanese. Rule-based engines miss this context; Mistral catches it.',
    outcomes: ['Context-aware deception detection', '12-language NLP including French', 'Graceful fallback if API unavailable'],
    category: 'French AI',
    color: 'bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800',
    tagColor: 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
  },
  {
    id: 4,
    tag: 'Privacy Design',
    flag: '🇪🇺',
    title: 'GDPR-Inspired Privacy Architecture',
    org: 'SafePulse Privacy-by-Design Implementation',
    summary: 'While SafePulse does not claim full GDPR compliance, its architecture is consciously inspired by GDPR principles: data minimisation (only incident category and country stored — no names, emails, or IPs), purpose limitation (data used only for aggregated dashboard visualisation), and anonymous reporting by default. This approach strengthens trust for European users and Francophone ASEAN communities.',
    outcomes: ['No personal identifiers stored', 'Anonymous reporting by default', 'Data minimisation principle applied'],
    category: 'Privacy',
    color: 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
    tagColor: 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
  },
  {
    id: 5,
    tag: 'Case Study',
    title: 'Reducing Phishing Victimisation Among University Students — Jakarta, 2024',
    org: 'SafePulse Community Pilot',
    summary: 'A 6-week digital literacy pilot with university students in Jakarta — using SafePulse\'s Scam Checker and Threat Library as hands-on learning tools — showed a 64% reduction in self-reported phishing click-through rates compared to a control group receiving only infographic-based awareness.',
    outcomes: ['64% reduction in phishing clicks', '89% participant satisfaction', 'Platform adopted for 3 faculty programs'],
    category: 'Community',
    color: 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
    tagColor: 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
  },
  {
    id: 6,
    tag: 'Research Brief',
    title: 'Mapping Investment Fraud Networks in Southeast Asia',
    org: 'SafePulse Research × INTERPOL Public Awareness Framework',
    summary: 'Analysis of 4,200 anonymised incident reports — structured following INTERPOL\'s I-GRIP taxonomy — identified 12 distinct fraud network signatures operating across Indonesia, Philippines, and Vietnam. Pattern data was aligned with INTERPOL\'s public fraud alerts for cross-validation.',
    outcomes: ['12 network signatures identified', '3 countries, I-GRIP taxonomy', 'Public dashboard visualised'],
    category: 'Research',
    color: 'bg-gray-50 dark:bg-gray-800/60 border-gray-200 dark:border-gray-700',
    tagColor: 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
  },
  {
    id: 7,
    tag: 'Partnership',
    flag: '🇫🇷',
    title: 'Institut Français d\'Indonésie — Digital Safety Workshop Programme',
    org: 'SafePulse × IFI Jakarta (Institut Français d\'Indonésie)',
    summary: 'Collaboration with IFI Jakarta to deliver joint digital-safety workshops for Indonesian students, professionals, and entrepreneurs. SafePulse serves as the live hands-on tool during sessions — participants use the Scam Checker in real time, report practice incidents, and explore the Threat Library in both English and French.',
    outcomes: ['Joint workshop curriculum developed', 'Bilingual EN/FR facilitation', 'Planned: 5 cities 2026'],
    category: 'French Framework',
    color: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
    tagColor: 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
  },
];

const CATEGORY_COLORS: Record<string, string> = {
  'French Framework': 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
  'French AI':        'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
  'Privacy':          'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
  'Community':        'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
  'Research':         'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
};

export default function Evidence() {
  const { t } = useTranslation();
  return (
    <>
      <SectionHero title={t('evidence.title')} subtitle={t('evidence.subtitle')} />

      {/* France connection banner */}
      <div className="bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <div className="flex flex-wrap items-center gap-4 text-sm">
            <span className="font-bold text-blue-800 dark:text-blue-200">🇫🇷 French Institutional Framework:</span>
            {[
              'ANSSI SecNumCloud (cybersecurity methodology)',
              'INTERPOL I-GRIP (Lyon, anti-fraud taxonomy)',
              'Mistral AI (Paris, multilingual LLM)',
              'IFI Jakarta (workshop partnership)',
              'GDPR-inspired privacy architecture',
            ].map((item) => (
              <span key={item} className="bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full">
                {item}
              </span>
            ))}
          </div>
        </div>
      </div>

      <section className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="space-y-8">
          {STUDIES.map((s) => (
            <article
              key={s.id}
              className={`rounded-2xl border p-8 shadow-sm hover:shadow-md transition-shadow ${s.color}`}
            >
              <div className="flex flex-wrap items-center gap-3 mb-4">
                <span className={`text-xs font-bold uppercase tracking-widest text-gray-400`}>{s.tag}</span>
                <span className={`text-xs font-semibold px-2.5 py-1 rounded-full ${CATEGORY_COLORS[s.category] ?? 'bg-gray-100 text-gray-600'}`}>
                  {s.category}
                </span>
                {s.flag && <span className="text-lg" aria-label="French institution">{s.flag}</span>}
              </div>
              <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-1">{s.title}</h2>
              <p className="text-sm text-primary-600 dark:text-primary-400 font-medium mb-4">{s.org}</p>
              <p className="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">{s.summary}</p>
              <div className="flex flex-wrap gap-3">
                {s.outcomes.map((o) => (
                  <span
                    key={o}
                    className="flex items-center gap-1.5 text-sm bg-white/70 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700"
                  >
                    <span className="text-green-500 font-bold" aria-hidden="true">✓</span>
                    {o}
                  </span>
                ))}
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
