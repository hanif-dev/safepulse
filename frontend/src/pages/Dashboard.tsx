import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer,
  LineChart, Line, PieChart, Pie, Cell, Legend,
} from 'recharts';
import { fetchStats, StatsOverview } from '../services/api';
import SectionHero from '../components/SectionHero';
import StatCard from '../components/StatCard';

const COLORS = ['#22a8f5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#f97316'];

function fmt(n: number): string {
  if (n >= 1_000_000) return `${(n / 1_000_000).toFixed(1)}M`;
  if (n >= 1_000)     return `${(n / 1_000).toFixed(1)}K`;
  return String(n);
}

export default function Dashboard() {
  const { t } = useTranslation();
  const [stats, setStats]     = useState<StatsOverview | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStats()
      .then(setStats)
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <>
        <SectionHero title={t('dashboard.title')} subtitle={t('dashboard.subtitle')} />
        <div className="max-w-7xl mx-auto px-4 py-20 text-center text-gray-400">{t('common.loading')}</div>
      </>
    );
  }

  if (!stats) return null;

  // Prepare chart data
  const categoryData = Object.entries(stats.by_category).map(([name, value]) => ({
    name: name.replace('_', ' '),
    value,
  }));

  const countryData = Object.entries(stats.by_country)
    .slice(0, 10)
    .map(([name, value]) => ({ name, value }));

  const monthlyData = Object.entries(stats.monthly).map(([month, value]) => ({
    month: month.slice(5),   // "2024-03" → "03"
    incidents: value,
  }));

  return (
    <>
      <SectionHero title={t('dashboard.title')} subtitle={t('dashboard.subtitle')} />

      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {/* ── Summary cards ──────────────────────────────────────────────── */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-12">
          <StatCard highlight value={fmt(stats.summary.people_protected)} label={t('dashboard.total')} icon="👥" />
          <StatCard value={stats.summary.high_impact}           label={t('dashboard.high_impact')}    icon="🚨" />
          <StatCard value={`$${fmt(stats.summary.total_financial_loss)}`} label={t('dashboard.financial_loss')} icon="💸" />
          <StatCard value={stats.summary.countries_affected}    label={t('dashboard.countries')}      icon="🌏" />
        </div>

        {/* ── Charts grid ────────────────────────────────────────────────── */}
        <div className="grid lg:grid-cols-2 gap-8 mb-8">

          {/* Category pie */}
          <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
            <h2 className="font-bold text-gray-900 dark:text-white mb-6">{t('dashboard.by_category')}</h2>
            <ResponsiveContainer width="100%" height={280}>
              <PieChart>
                <Pie
                  data={categoryData}
                  dataKey="value"
                  nameKey="name"
                  cx="50%"
                  cy="50%"
                  outerRadius={100}
                  label={({ name, percent }) => `${name} ${Math.round((percent ?? 0) * 100)}%`}
                  labelLine={false}
                >
                  {categoryData.map((_, i) => (
                    <Cell key={i} fill={COLORS[i % COLORS.length]} />
                  ))}
                </Pie>
                <Tooltip formatter={(v: number) => [v, 'Incidents']} />
                <Legend />
              </PieChart>
            </ResponsiveContainer>
          </div>

          {/* Monthly trend line */}
          <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
            <h2 className="font-bold text-gray-900 dark:text-white mb-6">{t('dashboard.monthly')}</h2>
            <ResponsiveContainer width="100%" height={280}>
              <LineChart data={monthlyData} margin={{ top: 5, right: 10, left: 0, bottom: 5 }}>
                <CartesianGrid strokeDasharray="3 3" stroke="#e5e7eb" />
                <XAxis dataKey="month" tick={{ fontSize: 12 }} />
                <YAxis tick={{ fontSize: 12 }} allowDecimals={false} />
                <Tooltip />
                <Line
                  type="monotone"
                  dataKey="incidents"
                  stroke="#22a8f5"
                  strokeWidth={2.5}
                  dot={{ r: 4, fill: '#22a8f5' }}
                  activeDot={{ r: 6 }}
                />
              </LineChart>
            </ResponsiveContainer>
          </div>
        </div>

        {/* Country bar */}
        <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
          <h2 className="font-bold text-gray-900 dark:text-white mb-6">{t('dashboard.by_country')}</h2>
          <ResponsiveContainer width="100%" height={260}>
            <BarChart data={countryData} margin={{ top: 5, right: 10, left: 0, bottom: 5 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="#e5e7eb" />
              <XAxis dataKey="name" tick={{ fontSize: 11 }} />
              <YAxis tick={{ fontSize: 12 }} allowDecimals={false} />
              <Tooltip />
              <Bar dataKey="value" name="Incidents" radius={[4, 4, 0, 0]}>
                {countryData.map((_, i) => (
                  <Cell key={i} fill={COLORS[i % COLORS.length]} />
                ))}
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </div>

        <p className="text-xs text-gray-400 text-center mt-6">
          Data aggregated from anonymous incident reports. Updated in real-time.
        </p>
      </section>
    </>
  );
}
