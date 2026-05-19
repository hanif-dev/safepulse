import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { reportIncident, IncidentResult } from '../services/api';
import SectionHero from '../components/SectionHero';

const COUNTRIES = [
  'Indonesia','Philippines','Malaysia','Vietnam','Thailand','Singapore',
  'Myanmar','Cambodia','Laos','Brunei','Timor-Leste','France','Other',
];

const SEVERITY_BG: Record<string, string> = {
  low:    'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200',
  medium: 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-200',
  high:   'bg-red-50   border-red-200   text-red-800   dark:bg-red-900/20   dark:border-red-800   dark:text-red-200',
};

export default function IncidentReport() {
  const { t } = useTranslation();

  const [form, setForm] = useState({
    category:'', country:'', age_group:'',
    description:'', health_impact_level:'', financial_loss_estimate:'',
  });
  const [loading, setLoading]         = useState(false);
  const [response, setResponse]       = useState<IncidentResult | null>(null);
  const [error, setError]             = useState('');
  const [fieldErrors, setFieldErrors] = useState<Record<string,string>>({});

  const set = (k: keyof typeof form) =>
    (e: React.ChangeEvent<HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement>) =>
      setForm((f) => ({ ...f, [k]: e.target.value }));

  const validate = () => {
    const errs: Record<string,string> = {};
    if (!form.category)               errs.category            = 'Required';
    if (!form.country)                errs.country             = 'Required';
    if (form.description.length < 20) errs.description         = 'Please provide at least 20 characters.';
    if (!form.health_impact_level)    errs.health_impact_level = 'Required';
    return errs;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const errs = validate();
    if (Object.keys(errs).length) { setFieldErrors(errs); return; }
    setFieldErrors({});
    setLoading(true);
    setError('');
    try {
      const res = await reportIncident({
        ...form,
        financial_loss_estimate: form.financial_loss_estimate
          ? parseFloat(form.financial_loss_estimate)
          : undefined,
      });
      setResponse(res);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch {
      setError(t('common.error'));
    } finally {
      setLoading(false);
    }
  };

  const inputCls = (key: string) =>
    `w-full border ${fieldErrors[key]?'border-red-400':'border-gray-200 dark:border-gray-700'} bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none`;

  // ── SUCCESS / ADVICE PAGE ──────────────────────────────────────────────
  if (response) {
    const a = response.analysis!;
    return (
      <>
        <SectionHero
          title="Report Received — Here's What to Do"
          subtitle={a.category_label}
        />

        <section className="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">

          {/* Severity banner */}
          <div className={`rounded-2xl border p-5 ${SEVERITY_BG[a.severity] ?? SEVERITY_BG.low}`}>
            <div className="flex items-center gap-3">
              <span className="text-2xl">
                {a.severity === 'high' ? '🚨' : a.severity === 'medium' ? '⚠️' : '✅'}
              </span>
              <div>
                <p className="font-bold capitalize">{a.severity} severity reported</p>
                <p className="text-sm opacity-80">Powered by: {a.powered_by}</p>
              </div>
            </div>
          </div>

          {/* AI contextual response */}
          {a.ai_analysis && (
            <div className="rounded-2xl bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 p-6">
              <p className="text-purple-900 dark:text-purple-100 leading-relaxed text-sm">
                {a.ai_analysis}
              </p>
            </div>
          )}

          {/* Headline advice */}
          <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
              💡 {a.advice.headline}
            </h2>
            <ol className="space-y-3">
              {a.advice.steps.map((step, i) => (
                <li key={i} className="flex gap-3">
                  <span className="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 font-bold text-sm flex items-center justify-center">
                    {i+1}
                  </span>
                  <span className="text-gray-700 dark:text-gray-300 text-sm leading-relaxed pt-0.5">
                    {step}
                  </span>
                </li>
              ))}
            </ol>
          </div>

          {/* Local resources */}
          <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
              🏛️ Local Resources for You
            </h2>
            <div className="grid sm:grid-cols-2 gap-3">
              {a.resources.map((r, i) => (
                <div key={i} className="rounded-xl bg-gray-50 dark:bg-gray-800 p-4 border border-gray-100 dark:border-gray-700">
                  <p className="font-semibold text-gray-900 dark:text-white text-sm">{r.name}</p>
                  <p className="text-primary-600 dark:text-primary-400 text-sm font-mono mt-1">{r.contact}</p>
                  <p className="text-xs text-gray-400 mt-1">{r.type}</p>
                </div>
              ))}
            </div>
          </div>

          {/* Action checklist */}
          <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
              ✅ Action Checklist
            </h2>
            <ul className="space-y-2">
              {a.action_checklist.map((item, i) => (
                <li key={i} className="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                  <span className="mt-0.5 text-green-500">☐</span>
                  <span>{item}</span>
                </li>
              ))}
            </ul>
          </div>

          {/* Knowledge references */}
          {a.knowledge_refs && a.knowledge_refs.length > 0 && (
            <div className="bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6">
              <h2 className="text-lg font-bold text-blue-900 dark:text-blue-200 mb-3">
                📚 Trusted Sources for Further Reading
              </h2>
              <ul className="space-y-2">
                {a.knowledge_refs.map((k, i) => (
                  <li key={i} className="text-sm">
                    <p className="font-semibold text-blue-800 dark:text-blue-200">{k.title}</p>
                    <p className="text-xs text-blue-600 dark:text-blue-400">
                      {k.source} · {k.org} · {k.year} · {k.region}
                    </p>
                    {k.url && (
                      <a href={k.url} target="_blank" rel="noopener noreferrer"
                        className="text-xs text-primary-600 hover:underline break-all">
                        {k.url}
                      </a>
                    )}
                  </li>
                ))}
              </ul>
            </div>
          )}

          {/* Privacy reaffirmation */}
          <div className="text-xs bg-gray-50 dark:bg-gray-800 rounded-xl p-4 space-y-1 text-gray-500 dark:text-gray-400">
            <p>🔒 <strong>Stored:</strong> category, country, age group (optional), description, impact level, financial estimate (optional)</p>
            <p>🚫 <strong>NOT stored:</strong> your name, email, IP address, or any identifying information</p>
            <p>🇫🇷 <strong>Framework:</strong> GDPR-inspired data minimisation · INTERPOL I-GRIP aligned taxonomy</p>
          </div>

          <div className="flex flex-wrap gap-3">
            <button
              onClick={() => { setResponse(null); setForm({category:'',country:'',age_group:'',description:'',health_impact_level:'',financial_loss_estimate:''}); }}
              className="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-xl"
            >
              Submit another report
            </button>
            <Link to="/adaptive/quick" className="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2.5 rounded-xl flex items-center gap-2">
                🧭 Get Personalized Recovery Plan
              </Link>
            <Link to="/insights" className="text-primary-600 dark:text-primary-400 font-semibold px-6 py-2.5">
              Browse Threat Library →
            </Link>
            <Link to="/privacy" className="text-gray-500 hover:text-gray-700 dark:text-gray-400 px-6 py-2.5 text-sm">
              Privacy Policy
            </Link>
          </div>
        </section>
      </>
    );
  }

  const FieldError = ({ k }: { k: string }) =>
    fieldErrors[k] ? <p role="alert" className="text-xs text-red-600 mt-1">{fieldErrors[k]}</p> : null;

  // ── FORM PAGE ──────────────────────────────────────────────────────────
  return (
    <>
      <SectionHero title={t('incident.title')} subtitle={t('incident.subtitle')} />

      <section className="max-w-2xl mx-auto px-4 sm:px-6 py-12">

        {/* Privacy notice */}
        <div className="mb-8 rounded-2xl overflow-hidden border border-blue-200 dark:border-blue-800">
          <div className="bg-blue-50 dark:bg-blue-900/20 px-5 py-3 flex items-center gap-2">
            <span>🔒</span>
            <span className="text-sm font-semibold text-blue-800 dark:text-blue-200">Privacy & Data Notice</span>
            <Link to="/privacy" className="ml-auto text-xs text-blue-600 dark:text-blue-400 hover:underline">Full policy →</Link>
          </div>
          <div className="px-5 py-4 bg-white dark:bg-gray-900 text-xs text-gray-600 dark:text-gray-400 grid sm:grid-cols-2 gap-3">
            <div>
              <p className="font-semibold text-gray-800 dark:text-gray-200 mb-1">✅ Anonymous by design</p>
              <p>No name, email, or IP address is collected. Reports cannot be traced back to you.</p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 dark:text-gray-200 mb-1">🇫🇷 GDPR-inspired + I-GRIP aligned</p>
              <p>Data minimisation · INTERPOL Lyon threat taxonomy.</p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 dark:text-gray-200 mb-1">🤖 AI advisor included</p>
              <p>After you submit, SafePulse + Mistral AI will return personalised advice and local resources.</p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 dark:text-gray-200 mb-1">📊 Public dashboard only</p>
              <p>Aggregated for the public health dashboard — never sold.</p>
            </div>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6" aria-label="Incident report form" noValidate>

          <div>
            <label htmlFor="cat" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
              {t('incident.label_category')} <span aria-hidden className="text-red-500">*</span>
              <span className="ml-2 text-xs font-normal text-gray-400">INTERPOL I-GRIP aligned</span>
            </label>
            <select id="cat" value={form.category} onChange={set('category')} className={inputCls('category')} required>
              <option value="">— Select —</option>
              {[
                ['phishing',         t('incident.categories.phishing')],
                ['investment',       t('incident.categories.investment')],
                ['romance',          t('incident.categories.romance')],
                ['radicalization',   t('incident.categories.radicalization')],
                ['money_laundering', t('incident.categories.money_laundering')],
                ['other',            t('incident.categories.other')],
              ].map(([k,v]) => <option key={k} value={k}>{v}</option>)}
            </select>
            <FieldError k="category" />
          </div>

          <div className="grid sm:grid-cols-2 gap-4">
            <div>
              <label htmlFor="country" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('incident.label_country')} <span aria-hidden className="text-red-500">*</span>
              </label>
              <select id="country" value={form.country} onChange={set('country')} className={inputCls('country')}>
                <option value="">— Select —</option>
                {COUNTRIES.map((c) => <option key={c} value={c}>{c}</option>)}
              </select>
              <FieldError k="country" />
            </div>
            <div>
              <label htmlFor="age" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('incident.label_age_group')}
              </label>
              <select id="age" value={form.age_group} onChange={set('age_group')} className={inputCls('age_group')}>
                <option value="">— Prefer not to say —</option>
                {['under_18','18_24','25_34','35_44','45_54','55_64','65_plus'].map((a) => (
                  <option key={a} value={a}>{a.replace('_','–')}</option>
                ))}
              </select>
            </div>
          </div>

          <div>
            <label htmlFor="desc" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
              {t('incident.label_description')} <span aria-hidden className="text-red-500">*</span>
            </label>
            <textarea
              id="desc" rows={5} value={form.description} onChange={set('description')}
              placeholder="Describe what happened — as much detail as you're comfortable sharing. Your report may help identify a scam network."
              className={inputCls('description')+' resize-y'}
            />
            <FieldError k="description" />
            <p className="text-xs text-gray-400 mt-1">{form.description.length} characters (min 20)</p>
          </div>

          <div className="grid sm:grid-cols-2 gap-4">
            <div>
              <label htmlFor="impact" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('incident.label_impact')} <span aria-hidden className="text-red-500">*</span>
              </label>
              <select id="impact" value={form.health_impact_level} onChange={set('health_impact_level')} className={inputCls('health_impact_level')}>
                <option value="">— Select —</option>
                <option value="low">{t('incident.impact_levels.low')}</option>
                <option value="medium">{t('incident.impact_levels.medium')}</option>
                <option value="high">{t('incident.impact_levels.high')}</option>
              </select>
              <FieldError k="health_impact_level" />
            </div>
            <div>
              <label htmlFor="loss" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                {t('incident.label_loss')}
              </label>
              <input id="loss" type="number" min="0" step="0.01"
                value={form.financial_loss_estimate} onChange={set('financial_loss_estimate')}
                placeholder="0.00"
                className={inputCls('financial_loss_estimate')} />
            </div>
          </div>

          {error && <p role="alert" className="text-sm text-red-600 font-medium">{error}</p>}

          <button type="submit" disabled={loading}
            className="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-colors"
          >
            {loading ? 'Submitting & generating advice…' : t('incident.submit')}
          </button>

          <p className="text-xs text-center text-gray-400">
            🔒 Anonymous · 🤖 AI-powered advice · 🇫🇷 GDPR-inspired ·{' '}
            <Link to="/privacy" className="underline hover:text-gray-600">Full privacy policy</Link>
          </p>
        </form>
      </section>
    </>
  );
}
