import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

const IMPACT_STATS = [
  { key: 'stat_protected', value: '2.4M+',  icon: '🛡️' },
  { key: 'stat_incidents', value: '18,500+', icon: '📋' },
  { key: 'stat_countries', value: '8',       icon: '🌏' },
  { key: 'stat_articles',  value: '200+',    icon: '📖' },
];

const FEATURES = [
  { icon: '🔍', title: 'Real-time Scam Detection', desc: 'Analyse messages, URLs, phone numbers, and accounts instantly with our rule-based engine.' },
  { icon: '📊', title: 'Public Health Dashboard',  desc: 'Live incident maps and trend charts aggregating anonymous community reports.' },
  { icon: '🎓', title: 'Youth Education Programs', desc: 'Curriculum-ready content helping young people recognise manipulation and build resilience.' },
  { icon: '🤝', title: 'NGO & Government Tools',   desc: 'Turnkey awareness campaign kits, data exports, and workshop facilitation guides.' },
  { icon: '🌐', title: 'Multilingual Platform',    desc: 'Available in English and Bahasa Indonesia, with Japanese and German coming soon.' },
  { icon: '♿', title: 'Accessible by Design',     desc: 'WCAG 2.1 AA compliant with dark mode, font-size controls, and text-to-speech support.' },
];

export default function Home() {
  const { t } = useTranslation();

  return (
    <>
      {/* ── Hero ──────────────────────────────────────────────────────────── */}
      <section className="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden">
        <div className="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-accent-400 via-transparent to-transparent" aria-hidden="true" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 grid md:grid-cols-2 gap-12 items-center">
          <div>
            <span className="inline-block bg-primary-700/60 text-primary-200 text-xs font-semibold px-3 py-1 rounded-full mb-6 tracking-widest uppercase">
              Anti-Scam · Digital Resilience · Youth Safety
            </span>
            <h1 className="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
              {t('home.hero_title')}
            </h1>
            <p className="text-lg md:text-xl text-primary-200 leading-relaxed mb-10 max-w-lg">
              {t('home.hero_subtitle')}
            </p>
            <div className="flex flex-wrap gap-4">
              <Link
                to="/check"
                className="inline-flex items-center gap-2 bg-white text-primary-800 font-bold px-6 py-3 rounded-xl hover:bg-primary-50 transition-colors shadow-lg"
              >
                🔍 {t('home.cta_check')}
              </Link>
              <Link
                to="/report"
                className="inline-flex items-center gap-2 bg-primary-700/50 border border-primary-500 text-white font-semibold px-6 py-3 rounded-xl hover:bg-primary-700 transition-colors"
              >
                📋 {t('home.cta_report')}
              </Link>
            </div>
          </div>
          {/* Decorative panel */}
          <div className="hidden md:flex justify-center">
            <div className="bg-white/10 backdrop-blur border border-white/20 rounded-3xl p-8 w-80 space-y-4">
              {['phishing', 'investment', 'radicalization', 'money_laundering'].map((cat, i) => (
                <div key={cat} className={`flex items-center gap-3 rounded-xl p-3 ${i === 1 ? 'bg-danger-500/30 border border-danger-400/40' : 'bg-white/5'}`}>
                  <span className="text-2xl">{['🎣','💰','📣','💸'][i]}</span>
                  <div>
                    <p className="text-sm font-semibold text-white capitalize">{cat.replace('_', ' ')}</p>
                    <p className="text-xs text-primary-300">{['Detected','⚠ High Risk','Monitoring','Flagged'][i]}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── Impact stats strip ────────────────────────────────────────────── */}
      <section className="bg-primary-700 text-white py-10" aria-label="Impact statistics">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
          {IMPACT_STATS.map((s) => (
            <div key={s.key}>
              <p className="text-3xl font-extrabold">{s.value}</p>
              <p className="text-sm text-primary-200 mt-1">{t(`home.${s.key}`)}</p>
            </div>
          ))}
        </div>
      </section>

      {/* ── Features grid ─────────────────────────────────────────────────── */}
      <section className="py-20 px-4" aria-labelledby="features-heading">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-14">
            <h2 id="features-heading" className="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
              A Public Health Approach to Digital Safety
            </h2>
            <p className="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto text-lg">
              We treat online scams and radicalization the way epidemiologists treat disease — with early detection, community surveillance, and evidence-based interventions.
            </p>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {FEATURES.map((f) => (
              <article
                key={f.title}
                className="bg-gray-50 dark:bg-gray-800/60 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-0.5 transition-all"
              >
                <span className="text-4xl mb-4 block" aria-hidden="true">{f.icon}</span>
                <h3 className="font-bold text-gray-900 dark:text-white mb-2">{f.title}</h3>
                <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{f.desc}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      {/* ── CTA banner ────────────────────────────────────────────────────── */}
      <section className="bg-accent-500 dark:bg-accent-600 py-16 px-4">
        <div className="max-w-3xl mx-auto text-center">
          <h2 className="text-3xl font-extrabold text-white mb-4">See threats in your region</h2>
          <p className="text-accent-100 mb-8 text-lg">Explore our live public-health dashboard aggregating anonymous incident data.</p>
          <Link
            to="/dashboard"
            className="inline-flex items-center gap-2 bg-white text-accent-700 font-bold px-8 py-3 rounded-xl hover:bg-gray-50 transition-colors shadow-lg"
          >
            📊 Open Dashboard
          </Link>
        </div>
      </section>
    </>
  );
}
