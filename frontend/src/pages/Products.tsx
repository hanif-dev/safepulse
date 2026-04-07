import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import SectionHero from '../components/SectionHero';

const PRODUCTS = [
  {
    icon: '🛡️',
    key: 'scam_shield',
    color: 'from-primary-600 to-primary-800',
    features: [
      'Message & email body analysis',
      'URL safety scoring',
      'Phone number risk check',
      'Bank account / wallet screening',
      'Real-time rule-based engine',
      'Zero data retention on checks',
    ],
    cta: { label: 'Try Scam Checker →', to: '/check' },
  },
  {
    icon: '☮️',
    key: 'youth',
    color: 'from-accent-600 to-accent-800',
    features: [
      '6-session school curriculum',
      'Algorithm awareness modules',
      'Counter-narrative toolkits',
      'Peer-educator certification',
      'Multilingual content (EN / ID)',
      'Pre/post impact assessments',
    ],
    cta: { label: 'Read the Evidence →', to: '/evidence' },
  },
  {
    icon: '🏛️',
    key: 'ngo',
    color: 'from-warning-500 to-orange-700',
    features: [
      'Print-ready awareness posters',
      'Social media campaign packs',
      'Community workshop guides',
      'Anonymous incident data feeds',
      'Threat dashboard embed',
      'White-labelled report templates',
    ],
    cta: { label: 'Contact Partnerships →', to: '/contact' },
  },
];

export default function Products() {
  const { t } = useTranslation();
  return (
    <>
      <SectionHero title={t('products.title')} subtitle={t('products.subtitle')} />

      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div className="grid lg:grid-cols-3 gap-8">
          {PRODUCTS.map((p) => (
            <article
              key={p.key}
              className="flex flex-col rounded-3xl overflow-hidden shadow-lg border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-shadow"
            >
              {/* Header */}
              <div className={`bg-gradient-to-br ${p.color} p-8 text-white`}>
                <span className="text-5xl block mb-4" aria-hidden="true">{p.icon}</span>
                <h2 className="text-2xl font-extrabold mb-2">{t(`products.${p.key}_title`)}</h2>
                <p className="text-sm opacity-90 leading-relaxed">{t(`products.${p.key}_desc`)}</p>
              </div>
              {/* Feature list */}
              <div className="flex-1 bg-white dark:bg-gray-900 p-6">
                <ul className="space-y-3">
                  {p.features.map((f) => (
                    <li key={f} className="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                      <span className="text-accent-500 font-bold mt-0.5 shrink-0">✓</span>
                      {f}
                    </li>
                  ))}
                </ul>
              </div>
              {/* CTA */}
              <div className="bg-white dark:bg-gray-900 px-6 pb-6">
                <Link
                  to={p.cta.to}
                  className="block w-full text-center bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity"
                >
                  {p.cta.label}
                </Link>
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
