import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import SectionHero from '../components/SectionHero';

// Contact form is purely frontend (no backend endpoint needed for MVP).
// Wire up to a mail service (Resend, Mailgun, Formspree) in production.

export default function Contact() {
  const { t } = useTranslation();
  const [form, setForm]       = useState({ name: '', email: '', subject: '', message: '' });
  const [submitted, setSubmitted] = useState(false);

  const set = (k: keyof typeof form) => (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // TODO: integrate with your preferred email service
    setSubmitted(true);
  };

  const inputClass =
    'w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none';

  return (
    <>
      <SectionHero title={t('contact.title')} subtitle={t('contact.subtitle')} />

      <section className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-16">
        {/* Info panel */}
        <div>
          <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-6">Get in touch</h2>
          <div className="space-y-6 text-sm text-gray-600 dark:text-gray-400">
            {[
              { icon: '🤝', title: 'Partnerships', desc: 'NGO, government, or corporate partnerships — reach our policy team.' },
              { icon: '📰', title: 'Media & Press', desc: 'Press kits, expert commentary, and data requests for journalists.' },
              { icon: '🔬', title: 'Research Collaboration', desc: 'Academic or institutional research partnerships on digital safety.' },
              { icon: '🛠️', title: 'Technical Support', desc: 'API access, integration questions, or bug reports.' },
            ].map((item) => (
              <div key={item.title} className="flex gap-4">
                <span className="text-2xl shrink-0" aria-hidden="true">{item.icon}</span>
                <div>
                  <p className="font-semibold text-gray-800 dark:text-gray-200">{item.title}</p>
                  <p>{item.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Form */}
        <div>
          {submitted ? (
            <div className="text-center py-12">
              <span className="text-5xl block mb-4">✉️</span>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">{t('contact.success')}</h3>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-5" aria-label="Contact form" noValidate>
              <div className="grid sm:grid-cols-2 gap-4">
                <div>
                  <label htmlFor="name" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    {t('contact.label_name')} <span aria-hidden className="text-danger-500">*</span>
                  </label>
                  <input id="name" type="text" required value={form.name} onChange={set('name')} className={inputClass} />
                </div>
                <div>
                  <label htmlFor="email" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    {t('contact.label_email')} <span aria-hidden className="text-danger-500">*</span>
                  </label>
                  <input id="email" type="email" required value={form.email} onChange={set('email')} className={inputClass} />
                </div>
              </div>

              <div>
                <label htmlFor="subject" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                  {t('contact.label_subject')} <span aria-hidden className="text-danger-500">*</span>
                </label>
                <input id="subject" type="text" required value={form.subject} onChange={set('subject')} className={inputClass} />
              </div>

              <div>
                <label htmlFor="message" className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                  {t('contact.label_message')} <span aria-hidden className="text-danger-500">*</span>
                </label>
                <textarea
                  id="message"
                  rows={6}
                  required
                  value={form.message}
                  onChange={set('message')}
                  className={inputClass + ' resize-y'}
                />
              </div>

              <button
                type="submit"
                className="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition-colors"
              >
                {t('contact.submit')}
              </button>
            </form>
          )}
        </div>
      </section>
    </>
  );
}
