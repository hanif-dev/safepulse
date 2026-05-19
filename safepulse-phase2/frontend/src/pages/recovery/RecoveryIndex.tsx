import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { listRecoveryPathways, RecoveryPathwayListItem } from '../../services/api-v2';
import EmergencyExitButton from '../../components/EmergencyExitButton';

const DOMAIN_ICONS: Record<string, string> = {
  romance_scam:    '💔',
  tppo:            '🚨',
  phishing:        '🎣',
  radicalization:  '🕊️',
  investment_fraud:'💰',
  cyberbullying:   '🛡️',
  csam:            '🔒',
  money_laundering:'⚖️',
  default:         '🌱',
};

export default function RecoveryIndex() {
  const { i18n } = useTranslation();
  const [items, setItems] = useState<RecoveryPathwayListItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    listRecoveryPathways(undefined, i18n.language)
      .then(r => setItems(r.data))
      .finally(() => setLoading(false));
  }, [i18n.language]);

  return (
    <>
      <section className="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <div className="text-center mb-12">
          <span className="text-5xl block mb-4">🌱</span>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-3">
            Recovery Pathways
          </h1>
          <p className="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Step-by-step guidance for survivors of digital crime — grounded in WHO Psychological
            First Aid and survivor-support practice. You did nothing wrong. Recovery is possible.
          </p>
        </div>

        {loading ? (
          <div className="text-center text-gray-500">Loading recovery pathways…</div>
        ) : (
          <div className="grid sm:grid-cols-2 gap-4">
            {items.map((item) => (
              <Link
                key={item.slug}
                to={`/recovery/${item.slug}`}
                className="block bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow"
              >
                <span className="text-3xl block mb-3">
                  {DOMAIN_ICONS[item.crime_domain] ?? DOMAIN_ICONS.default}
                </span>
                <h2 className="font-bold text-lg text-gray-900 dark:text-white mb-2">
                  {item.title}
                </h2>
                <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                  {item.summary}
                </p>
              </Link>
            ))}
          </div>
        )}

        <div className="mt-12 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-5 text-sm text-blue-800 dark:text-blue-200">
          🛡️ <strong>Privacy notice:</strong> SafePulse stores nothing about your visit.
          These pathways are public reference material — no login, no tracking.
        </div>
      </section>

      <EmergencyExitButton />
    </>
  );
}
