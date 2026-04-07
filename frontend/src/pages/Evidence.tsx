import { useTranslation } from 'react-i18next';
import SectionHero from '../components/SectionHero';

const STUDIES = [
  {
    id: 1,
    tag: 'Case Study',
    title: 'Reducing Phishing Victimisation Among University Students — Jakarta, 2023',
    org: 'SafePulse × Universitas Indonesia',
    summary: 'A 6-week digital literacy intervention with 1,200 students reduced self-reported phishing click-through rates by 64% compared to control group.',
    outcomes: ['64% reduction in phishing clicks', '89% participant satisfaction', 'Curriculum adopted by 8 faculties'],
    category: 'Education',
  },
  {
    id: 2,
    tag: 'Research Brief',
    title: 'Mapping Investment Fraud Networks in Southeast Asia',
    org: 'SafePulse Research × INTERPOL Public Awareness',
    summary: 'Analysis of 4,200 anonymised incident reports identified 12 distinct fraud network signatures operating across Indonesia, Philippines, and Vietnam.',
    outcomes: ['12 network signatures identified', '3 countries covered', 'Data shared with INTERPOL'],
    category: 'Research',
  },
  {
    id: 3,
    tag: 'Impact Report',
    title: 'Youth Peace Hub Pilot — Radicalization Prevention in 40 Secondary Schools',
    org: 'SafePulse × BNPT Indonesia',
    summary: 'Longitudinal study across 40 schools showed statistically significant reduction in susceptibility to extremist messaging among 14–17 year olds after a 3-month program.',
    outcomes: ['40 schools, 12,000 students', '38% lower radicalization susceptibility', 'Replicated in Malaysia (2024)'],
    category: 'Youth',
  },
  {
    id: 4,
    tag: 'Whitepaper',
    title: 'Digital Money Mule Recruitment Patterns: A Social Network Analysis',
    org: 'SafePulse Intelligence Unit',
    summary: 'Using anonymised incident data and open-source intelligence, this paper maps how criminal networks recruit money mules through job platforms and social media.',
    outcomes: ['7 recruitment archetypes documented', 'Telegram + LinkedIn dominant vectors', 'Policy recommendations included'],
    category: 'Research',
  },
  {
    id: 5,
    tag: 'Case Study',
    title: 'Community-Led Scam Reporting Network — Rural Philippines',
    org: 'SafePulse × Bayanihan Digital Alliance',
    summary: 'Training 200 community health workers as digital safety advocates created a peer-reporting network that detected 3 active scam campaigns before mass victimisation.',
    outcomes: ['200 advocates trained', '3 campaigns pre-empted', '~850 potential victims protected'],
    category: 'Community',
  },
];

const CATEGORY_COLORS: Record<string, string> = {
  Education: 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300',
  Research:  'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
  Youth:     'bg-accent-100 text-accent-700 dark:bg-accent-900/40 dark:text-accent-300',
  Community: 'bg-warning-100 text-warning-700 dark:bg-orange-900/40 dark:text-orange-300',
};

export default function Evidence() {
  const { t } = useTranslation();
  return (
    <>
      <SectionHero title={t('evidence.title')} subtitle={t('evidence.subtitle')} />

      <section className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="space-y-8">
          {STUDIES.map((s) => (
            <article
              key={s.id}
              className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-8 shadow-sm hover:shadow-md transition-shadow"
            >
              <div className="flex flex-wrap items-center gap-3 mb-4">
                <span className="text-xs font-bold uppercase tracking-widest text-gray-400">{s.tag}</span>
                <span className={`text-xs font-semibold px-2.5 py-1 rounded-full ${CATEGORY_COLORS[s.category]}`}>
                  {s.category}
                </span>
              </div>
              <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-1">{s.title}</h2>
              <p className="text-sm text-primary-600 dark:text-primary-400 font-medium mb-4">{s.org}</p>
              <p className="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">{s.summary}</p>
              <div className="flex flex-wrap gap-3">
                {s.outcomes.map((o) => (
                  <span
                    key={o}
                    className="flex items-center gap-1.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full border border-gray-100 dark:border-gray-700"
                  >
                    <span className="text-accent-500 font-bold" aria-hidden="true">✓</span>
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
