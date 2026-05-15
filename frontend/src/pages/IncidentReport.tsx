import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { reportIncident } from '../services/api';
import SectionHero from '../components/SectionHero';

const COUNTRIES = [
  'Indonesia','Philippines','Malaysia','Vietnam','Thailand','Singapore',
  'Myanmar','Cambodia','Laos','Brunei','Timor-Leste','France','Other',
];

export default function IncidentReport() {
  const { t } = useTranslation();

  const [form, setForm] = useState({
    category:'', country:'', age_group:'',
    description:'', health_impact_level:'', financial_loss_estimate:'',
  });
  const [loading, setLoading]         = useState(false);
  const [success, setSuccess]         = useState(false);
  const [error, setError]             = useState('');
  const [fieldErrors, setFieldErrors] = useState<Record<string,string>>({});

  const set = (k: keyof typeof form) =>
    (e: React.ChangeEvent<HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement>) =>
      setForm((f) => ({ ...f, [k]: e.target.value }));

  const validate = () => {
    const errs: Record<string,string> = {};
    if (!form.category)              errs.category            = 'Required';
    if (!form.country)               errs.country             = 'Required';
    if (form.description.length < 20) errs.description        = 'Please provide at least 20 characters.';
    if (!form.health_impact_level)   errs.health_impact_level = 'Required';
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
      await reportIncident({
        ...form,
        financial_loss_estimate: form.financial_loss_estimate
          ? parseFloat(form.financial_loss_estimate)
          : undefined,
      });
      setSuccess(true);
    } catch {
      setError(t('common.error'));
    } finally {
      setLoading(false);
    }
  };

  const inputCls = (key: string) =>
    `w-full border ${fieldErrors[key]?'border-red-400':'border-gray-200 dark:border-gray-700'} bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none`;

  if (success) {
    return (
      <div className="max-w-xl mx-auto px-4 py-24 text-center">
        <span className="text-6xl block mb-6">✅</span>
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-3">{t('incident.success')}</h2>
        <p className="text-gray-500 mb-4">
          Your anonymous report helps map digital threats and protect communities.
        </p>
        <div className="text-xs text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-xl p-4 mb-8 text-left space-y-1">
          <p>🔒 <strong>What was stored:</strong> category, country, age group (optional), description, impact level, financial estimate (optional)</p>
          <p>🚫 <strong>What was NOT stored:</strong> your name, email, IP address, or any identifying information</p>
          <p>📊 <strong>How it's used:</strong> aggregated into the public health dashboard only</p>
          <p>🇫🇷 <strong>Framework:</strong> GDPR-inspired data minimisation · INTERPOL I-GRIP aligned taxonomy</p>
        </div>
        <button
          onClick={() => { setSuccess(false); setForm({category:'',country:'',age_group:'',description:'',health_impact_level:'',financial_loss_estimate:''}); }}
          className="bg-primary-600 text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-primary-700 transition-colors"
        >
          Submit another report
        </button>
      </div>
    );
  }

  const FieldError = ({ k }: { k: string }) =>
    fieldErrors[k] ? <p role="alert" className="text-xs text-red-600 mt-1">{fieldErrors[k]}</p> : null;

  return (
    <>
      <SectionHero title={t('incident.title')} subtitle={t('incident.subtitle')} />

      <section className="max-w-2xl mx-auto px-4 sm:px-6 py-12">

        {/* Privacy + forensics notice */}
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
              <p>Data minimisation (ANSSI/GDPR) · Threat taxonomy matches INTERPOL I-GRIP (Lyon, France).</p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 dark:text-gray-200 mb-1">🔎 Digital forensics ready</p>
              <p>Structured for potential handover to law-enforcement and consumer-protection partners.</p>
            </div>
            <div>
              <p className="font-semibold text-gray-800 dark:text-gray-200 mb-1">📊 Public dashboard only</p>
              <p>Incident data feeds the aggregated public health dashboard — never shared commercially.</p>
            </div>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6" aria-label="Incident report form" noValidate>

          {/* Category */}
          <div>
            <label htmlFor="cat" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
              {t('incident.label_category')} <span aria-hidden className="text-red-500">*</span>
              <span className="ml-2 text-xs font-normal text-gray-400">INTERPOL I-GRIP aligned</span>
            </label>
            <select id="cat" value={form.category} onChange={set('category')} className={inputCls('category')} required>
              <option value="">— Select —</option>
              {[
                ['phishing',        t('incident.categories.phishing')],
                ['investment',      t('incident.categories.investment')],
                ['romance',         t('incident.categories.romance')],
                ['radicalization',  t('incident.categories.radicalization')],
                ['money_laundering',t('incident.categories.money_laundering')],
                ['other',           t('incident.categories.other')],
              ].map(([k,v]) => <option key={k} value={k}>{v}</option>)}
            </select>
            <FieldError k="category" />
          </div>

          {/* Country + Age */}
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

          {/* Description */}
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

          {/* Impact + Loss */}
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
              <input
                id="loss" type="number" min="0" step="0.01"
                value={form.financial_loss_estimate} onChange={set('financial_loss_estimate')}
                placeholder="0.00"
                className={inputCls('financial_loss_estimate')}
              />
            </div>
          </div>

          {error && <p role="alert" className="text-sm text-red-600 font-medium">{error}</p>}

          <button type="submit" disabled={loading}
            className="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-colors"
          >
            {loading ? 'Submitting…' : t('incident.submit')}
          </button>

          <p className="text-xs text-center text-gray-400">
            🔒 Anonymous · GDPR-inspired · INTERPOL I-GRIP aligned ·{' '}
            <Link to="/privacy" className="underline hover:text-gray-600">Full privacy policy</Link>
          </p>
        </form>
      </section>
    </>
  );
}
