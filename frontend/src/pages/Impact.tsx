import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { fetchStats, StatsOverview } from '../services/api';
import SectionHero from '../components/SectionHero';
import StatCard from '../components/StatCard';

const MILESTONES = [
  { year: '2021', event: 'SafePulse pilot launched in Indonesia with 3 NGO partners.' },
  { year: '2022', event: 'Platform expanded to Philippines and Malaysia; 5,000 incidents mapped.' },
  { year: '2023', event: 'Youth Peace Hub curriculum adopted by 120 schools across Southeast Asia.' },
  { year: '2024', event: 'Public dashboard launched; 2.4 million people reached through awareness campaigns.' },
];

function fmt(n: number): string {
  if (n >= 1_000_000) return `${(n / 1_000_000).toFixed(1)}M`;
  if (n >= 1_000)     return `${(n / 1_000).toFixed(1)}K`;
  return String(n);
}

export default function Impact() {
  const { t } = useTranslation();
  const [stats, setStats]     = useState<StatsOverview | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStats()
      .then(setStats)
      .finally(() => setLoading(false));
  }, []);

  return (
    <>
      <SectionHero title={t('impact.title')} subtitle={t('impact.subtitle')} />

      {/* ── Live stats ──────────────────────────────────────────────────── */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" aria-label="Impact statistics">
        {loading ? (
          <p className="text-center text-gray-400 py-12">{t('common.loading')}</p>
        ) : stats ? (
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <StatCard highlight value={fmt(stats.summary.people_protected)} label={t('impact.people_protected')} icon="🛡️" />
            <StatCard value={stats.summary.total_incidents}       label={t('impact.incidents_mapped')} icon="📋" />
            <StatCard value={stats.summary.countries_affected}    label={t('impact.countries')}        icon="🌏" />
            <StatCard value={`$${fmt(stats.summary.total_financial_loss)}`} label="Total Loss Reported" icon="💸" />
          </div>
        ) : null}

        {/* ── Category breakdown ─────────────────────────────────────────── */}
        {stats && (
          <div className="mb-16">
            <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">Incidents by Threat Type</h2>
            <div className="space-y-3">
              {Object.entries(stats.by_category).map(([cat, count]) => {
                const total = stats.summary.total_incidents || 1;
                const pct = Math.round((count / total) * 100);
                return (
                  <div key={cat} className="flex items-center gap-4">
                    <span className="w-36 text-sm font-medium text-gray-600 dark:text-gray-300 capitalize shrink-0">
                      {cat.replace('_', ' ')}
                    </span>
                    <div className="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full h-4 overflow-hidden">
                      <div
                        className="bg-primary-600 h-full rounded-full transition-all duration-700"
                        style={{ width: `${pct}%` }}
                        role="progressbar"
                        aria-valuenow={pct}
                        aria-valuemin={0}
                        aria-valuemax={100}
                        aria-label={`${cat}: ${pct}%`}
                      />
                    </div>
                    <span className="text-sm font-semibold text-gray-700 dark:text-gray-200 w-14 text-right">{count} ({pct}%)</span>
                  </div>
                );
              })}
            </div>
          </div>
        )}

        {/* ── Timeline ──────────────────────────────────────────────────── */}
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-8">Our Journey</h2>
        <ol className="relative border-l border-primary-200 dark:border-primary-800 space-y-8 pl-6">
          {MILESTONES.map((m) => (
            <li key={m.year} className="relative">
              <span className="absolute -left-3 w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold" aria-hidden="true">●</span>
              <p className="text-xs font-semibold text-primary-600 dark:text-primary-400 mb-1">{m.year}</p>
              <p className="text-gray-700 dark:text-gray-300">{m.event}</p>
            </li>
          ))}
        </ol>
      </section>
    </>
  );
}
