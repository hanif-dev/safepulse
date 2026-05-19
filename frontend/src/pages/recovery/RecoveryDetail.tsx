import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { getRecoveryPathway, RecoveryPathwayDetail } from '../../services/api-v2';
import HotlineCard from '../../components/HotlineCard';
import EmergencyExitButton from '../../components/EmergencyExitButton';

export default function RecoveryDetailPage() {
  const { slug } = useParams<{ slug: string }>();
  const { i18n } = useTranslation();
  const [pathway, setPathway] = useState<RecoveryPathwayDetail | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!slug) return;
    getRecoveryPathway(slug, i18n.language)
      .then(setPathway)
      .finally(() => setLoading(false));
  }, [slug, i18n.language]);

  if (loading) return <div className="text-center py-20">Loading…</div>;
  if (!pathway) return <div className="text-center py-20">Pathway not found.</div>;

  return (
    <>
      <section className="max-w-3xl mx-auto px-4 sm:px-6 py-12 space-y-10">

        <header>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-3">
            {pathway.title}
          </h1>
          <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
            {pathway.summary}
          </p>
        </header>

        {/* Trauma-informed disclaimer (SAMHSA Transparency) */}
        <div className="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
          <p className="text-sm text-amber-800 dark:text-amber-200">
            💡 {pathway.disclaimer}
          </p>
        </div>

        {/* Milestones */}
        <div>
          <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-6">
            Recovery Timeline
          </h2>
          <div className="space-y-6">
            {pathway.milestones.map((m, i) => (
              <div key={i} className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <div className="flex items-center gap-3 mb-4">
                  <span className="bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 font-bold px-3 py-1 rounded-full text-sm">
                    Week {m.week}
                  </span>
                </div>
                <ul className="space-y-3">
                  {m.tasks.map((task, ti) => (
                    <li key={ti} className="flex gap-3 items-start">
                      <span className="text-green-500 mt-0.5">☐</span>
                      <span className="text-sm text-gray-700 dark:text-gray-300">
                        {task.title_key}
                      </span>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>
        </div>

        {/* Hotlines */}
        {pathway.hotlines.length > 0 && (
          <div>
            <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
              📞 Emergency Resources
            </h2>
            <div className="grid sm:grid-cols-2 gap-3">
              {pathway.hotlines.map((h) => (
                <HotlineCard key={h.slug} hotline={h} />
              ))}
            </div>
          </div>
        )}

        {/* Templates */}
        {pathway.templates && pathway.templates.length > 0 && (
          <div className="bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6">
            <h2 className="text-lg font-bold text-blue-900 dark:text-blue-200 mb-3">
              📄 Template Letters
            </h2>
            <ul className="space-y-2">
              {pathway.templates.map((t, i) => (
                <li key={i}>
                  <a href={`/api/v2/recovery-pathways/${pathway.slug}/templates/${t.kind}`}
                     className="text-blue-600 hover:underline text-sm">
                    {t.kind.replace(/_/g, ' ')} →
                  </a>
                </li>
              ))}
            </ul>
          </div>
        )}
      </section>

      <EmergencyExitButton />
    </>
  );
}
