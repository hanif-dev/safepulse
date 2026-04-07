import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { checkScam, ScamCheckResult } from '../services/api';
import SectionHero from '../components/SectionHero';
import ScoreGauge from '../components/ScoreGauge';

export default function ScamChecker() {
  const { t } = useTranslation();

  const [fields, setFields] = useState({
    message_text: '',
    url:          '',
    phone_number: '',
    bank_account: '',
  });
  const [loading, setLoading] = useState(false);
  const [result, setResult]   = useState<ScamCheckResult | null>(null);
  const [error, setError]     = useState('');

  const handleChange = (key: keyof typeof fields) => (
    e: React.ChangeEvent<HTMLTextAreaElement | HTMLInputElement>
  ) => setFields((f) => ({ ...f, [key]: e.target.value }));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const nonEmpty = Object.fromEntries(
      Object.entries(fields).filter(([, v]) => v.trim() !== '')
    );
    if (Object.keys(nonEmpty).length === 0) {
      setError('Please fill in at least one field.');
      return;
    }
    setError('');
    setLoading(true);
    try {
      const res = await checkScam(nonEmpty);
      setResult(res);
    } catch {
      setError(t('common.error'));
    } finally {
      setLoading(false);
    }
  };

  const LEVEL_ADVICE: Record<string, string> = {
    Low:    '✅ No major red flags detected. Always stay cautious and verify the source directly.',
    Medium: '⚠️ Some suspicious signals. Do not click links, send money, or share personal data until you verify.',
    High:   '🚨 Multiple high-risk signals detected. This is very likely a scam. Do NOT engage further.',
  };

  return (
    <>
      <SectionHero title={t('checker.title')} subtitle={t('checker.subtitle')} />

      <section className="max-w-3xl mx-auto px-4 sm:px-6 py-12">
        <form onSubmit={handleSubmit} className="space-y-6" aria-label="Scam checker form" noValidate>

          {/* Message text */}
          <div>
            <label htmlFor="msg" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
              {t('checker.label_message')}
            </label>
            <textarea
              id="msg"
              rows={5}
              value={fields.message_text}
              onChange={handleChange('message_text')}
              placeholder={t('checker.placeholder_message')}
              className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none resize-y"
            />
          </div>

          <div className="grid sm:grid-cols-3 gap-4">
            {/* URL */}
            <div className="sm:col-span-3">
              <label htmlFor="url" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('checker.label_url')}
              </label>
              <input
                id="url"
                type="url"
                value={fields.url}
                onChange={handleChange('url')}
                placeholder={t('checker.placeholder_url')}
                className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
              />
            </div>

            {/* Phone */}
            <div>
              <label htmlFor="phone" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('checker.label_phone')}
              </label>
              <input
                id="phone"
                type="tel"
                value={fields.phone_number}
                onChange={handleChange('phone_number')}
                placeholder={t('checker.placeholder_phone')}
                className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
              />
            </div>

            {/* Account */}
            <div className="sm:col-span-2">
              <label htmlFor="account" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('checker.label_account')}
              </label>
              <input
                id="account"
                type="text"
                value={fields.bank_account}
                onChange={handleChange('bank_account')}
                placeholder={t('checker.placeholder_account')}
                className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
              />
            </div>
          </div>

          {error && (
            <p role="alert" className="text-sm text-danger-600 dark:text-danger-400 font-medium">{error}</p>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-colors"
          >
            {loading ? t('checker.loading') : t('checker.submit')}
          </button>
        </form>

        {/* ── Result ──────────────────────────────────────────────────────── */}
        {result && (
          <div className="mt-10 space-y-6" role="region" aria-label="Scam check result">
            <h2 className="text-xl font-bold text-gray-900 dark:text-white">{t('checker.result_title')}</h2>

            <ScoreGauge score={result.score} level={result.level} />

            {/* Advice */}
            <div className="rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 px-5 py-4 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
              {LEVEL_ADVICE[result.level]}
            </div>

            {/* Findings */}
            <div>
              <h3 className="font-semibold text-gray-800 dark:text-gray-200 mb-3">{t('checker.reasons_title')}</h3>
              <ul className="space-y-2">
                {result.reasons.map((r, i) => (
                  <li key={i} className="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span className="text-primary-500 font-bold shrink-0 mt-0.5">›</span>
                    {r}
                  </li>
                ))}
              </ul>
            </div>

            {/* Reset */}
            <button
              onClick={() => { setResult(null); setFields({ message_text: '', url: '', phone_number: '', bank_account: '' }); }}
              className="text-sm text-gray-500 hover:text-primary-600 underline"
            >
              Check another
            </button>
          </div>
        )}
      </section>
    </>
  );
}
