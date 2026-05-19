import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { adaptiveQuick, QuickResponse } from '../../services/api-v2';
import HotlineCard from '../../components/HotlineCard';
import EmergencyExitButton from '../../components/EmergencyExitButton';

const ROLES = [
  { value: 'victim',       label: 'I am the affected person' },
  { value: 'family',       label: 'A family member / friend is affected' },
  { value: 'professional', label: 'I work in support / social services' },
  { value: 'researcher',   label: 'I am a researcher' },
];

const DOMAINS = [
  { value: 'phishing',         label: 'Phishing / fake messages' },
  { value: 'romance_scam',     label: 'Romance scam' },
  { value: 'investment_fraud', label: 'Investment fraud' },
  { value: 'tppo',             label: 'Human trafficking (TPPO)' },
  { value: 'radicalization',   label: 'Online radicalization concern' },
  { value: 'csam',             label: 'Image-based abuse / CSAM' },
  { value: 'cyberbullying',    label: 'Cyberbullying' },
  { value: 'money_laundering', label: 'Money laundering / mule' },
];

export default function QuickFlow() {
  const { i18n } = useTranslation();
  const [role, setRole] = useState('');
  const [domain, setDomain] = useState('');
  const [country, setCountry] = useState('ID');
  const [response, setResponse] = useState<QuickResponse | null>(null);
  const [loading, setLoading] = useState(false);

  const submit = async () => {
    if (!role || !domain) return;
    setLoading(true);
    try {
      const r = await adaptiveQuick(role, domain, country, i18n.language);
      setResponse(r);
    } finally {
      setLoading(false);
    }
  };

  if (response) {
    return (
      <>
        <section className="max-w-2xl mx-auto px-4 sm:px-6 py-10 space-y-6">
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
            Your Quick Action Plan
          </h1>

          <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 className="font-bold text-lg mb-4 text-gray-900 dark:text-white">
              📋 Top {response.top_actions.length} Steps Right Now
            </h2>
            <ol className="space-y-3">
              {response.top_actions.map((a, i) => (
                <li key={a.key} className="flex gap-3">
                  <span className="w-7 h-7 rounded-full bg-primary-600 text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">
                    {i + 1}
                  </span>
                  <div>
                    <p className="font-semibold text-gray-900 dark:text-white text-sm">{a.title}</p>
                    <p className="text-xs text-gray-600 dark:text-gray-400">{a.description}</p>
                  </div>
                </li>
              ))}
            </ol>
          </div>

          {response.emergency_hotlines.length > 0 && (
            <div>
              <h2 className="font-bold text-lg mb-3 text-gray-900 dark:text-white">📞 Emergency Hotlines</h2>
              <div className="grid sm:grid-cols-2 gap-3">
                {response.emergency_hotlines.map(h => <HotlineCard key={h.slug} hotline={h} />)}
              </div>
            </div>
          )}

          <div className="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-5 border border-blue-200 dark:border-blue-800">
            <p className="font-bold text-blue-900 dark:text-blue-200 mb-2">
              {response.upgrade_invitation.title}
            </p>
            <p className="text-sm text-blue-700 dark:text-blue-300 mb-4">
              {response.upgrade_invitation.message}
            </p>
            <a href="/adaptive/deep"
               className="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl">
              {response.upgrade_invitation.cta} →
            </a>
          </div>

          <button
            onClick={() => setResponse(null)}
            className="text-sm text-gray-500 hover:text-primary-600 underline"
          >
            Start over
          </button>
        </section>
        <EmergencyExitButton />
      </>
    );
  }

  return (
    <>
      <section className="max-w-xl mx-auto px-4 sm:px-6 py-10 space-y-6">
        <header className="text-center">
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            Quick Action Plan
          </h1>
          <p className="text-sm text-gray-600 dark:text-gray-400">
            Three questions, immediate next steps. Anonymous. Free.
          </p>
        </header>

        <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 space-y-5">
          {/* Role */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              1. Who are you?
            </label>
            <select value={role} onChange={(e) => setRole(e.target.value)}
                    className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm">
              <option value="">— Select —</option>
              {ROLES.map(r => <option key={r.value} value={r.value}>{r.label}</option>)}
            </select>
          </div>

          {/* Domain */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              2. What is the situation?
            </label>
            <select value={domain} onChange={(e) => setDomain(e.target.value)}
                    className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm">
              <option value="">— Select —</option>
              {DOMAINS.map(d => <option key={d.value} value={d.value}>{d.label}</option>)}
            </select>
          </div>

          {/* Country */}
          <div>
            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
              3. Country
            </label>
            <select value={country} onChange={(e) => setCountry(e.target.value)}
                    className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm">
              <option value="ID">Indonesia</option>
              <option value="MY">Malaysia</option>
              <option value="SG">Singapore</option>
              <option value="TH">Thailand</option>
              <option value="VN">Vietnam</option>
              <option value="PH">Philippines</option>
              <option value="KH">Cambodia</option>
              <option value="MM">Myanmar</option>
            </select>
          </div>

          <button
            onClick={submit}
            disabled={!role || !domain || loading}
            className="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold py-3 rounded-xl"
          >
            {loading ? 'Generating plan…' : 'Get My Action Plan'}
          </button>
        </div>

        <p className="text-xs text-center text-gray-400">
          🔒 No personal data stored. No login required.
        </p>
      </section>

      <EmergencyExitButton />
    </>
  );
}
