import SectionHero from '../components/SectionHero';

export default function Privacy() {

  return (
    <>
      <SectionHero
        title="Privacy & Data Policy"
        subtitle="SafePulse is built with privacy-conscious design inspired by GDPR principles — your safety is our priority, your data is not our product."
      />

      {/* GDPR banner */}
      <div className="bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800">
        <div className="max-w-3xl mx-auto px-4 py-3 flex items-center gap-3 text-sm text-green-700 dark:text-green-300">
          <span>🇪🇺</span>
          <span>
            <strong>GDPR-Inspired Architecture</strong> — SafePulse applies GDPR principles of data minimisation,
            purpose limitation, and privacy-by-default. We do not claim full GDPR compliance but consciously
            follow its spirit for all users.
          </span>
        </div>
      </div>

      <article className="max-w-3xl mx-auto px-4 sm:px-6 py-12 space-y-10">

        {/* Core principles */}
        <section>
          <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">Core Privacy Principles</h2>
          <div className="grid sm:grid-cols-3 gap-4">
            {[
              { icon:'🔒', title:'Data Minimisation', desc:'We collect the minimum data needed for each feature. The Scam Checker stores nothing. Incident reports store only category and country — no name, email, or IP address.' },
              { icon:'🎯', title:'Purpose Limitation', desc:'Incident data is used only for aggregated public-health dashboard visualisation. We do not sell, share, or re-purpose any data for commercial use.' },
              { icon:'🕵️', title:'Anonymous by Default', desc:'All reporting features are anonymous by design. No account is required. No tracking cookies are used. You are a community member, not a data point.' },
            ].map((p) => (
              <div key={p.title} className="bg-gray-50 dark:bg-gray-800/60 rounded-2xl p-5">
                <span className="text-3xl block mb-3" aria-hidden="true">{p.icon}</span>
                <h3 className="font-bold text-gray-900 dark:text-white mb-2">{p.title}</h3>
                <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{p.desc}</p>
              </div>
            ))}
          </div>
        </section>

        {/* What we collect */}
        <section className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-8">
          <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-6">What Data We Collect — and What We Don't</h2>

          <div className="space-y-6">
            <div>
              <h3 className="font-semibold text-red-600 dark:text-red-400 mb-3 flex items-center gap-2">
                <span>🔍</span> Scam Checker Tool
              </h3>
              <div className="grid sm:grid-cols-2 gap-4 text-sm">
                <div className="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                  <p className="font-semibold text-green-700 dark:text-green-300 mb-2">✅ What happens:</p>
                  <ul className="text-green-700 dark:text-green-400 space-y-1">
                    <li>• Content is analysed in memory only</li>
                    <li>• Mistral AI processes it ephemerally</li>
                    <li>• Result is returned to you</li>
                    <li>• Everything is discarded immediately</li>
                  </ul>
                </div>
                <div className="bg-red-50 dark:bg-red-900/20 rounded-xl p-4">
                  <p className="font-semibold text-red-700 dark:text-red-300 mb-2">❌ What we do NOT store:</p>
                  <ul className="text-red-700 dark:text-red-400 space-y-1">
                    <li>• Your message or email content</li>
                    <li>• URLs you submit</li>
                    <li>• Phone numbers or account IDs</li>
                    <li>• Your IP address or device info</li>
                  </ul>
                </div>
              </div>
            </div>

            <div>
              <h3 className="font-semibold text-blue-600 dark:text-blue-400 mb-3 flex items-center gap-2">
                <span>📋</span> Incident Reporter
              </h3>
              <div className="overflow-x-auto">
                <table className="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                  <thead>
                    <tr className="bg-gray-900 dark:bg-gray-800 text-white">
                      <th className="px-4 py-2 text-left">Field</th>
                      <th className="px-4 py-2 text-left">Stored?</th>
                      <th className="px-4 py-2 text-left">Purpose</th>
                    </tr>
                  </thead>
                  <tbody>
                    {[
                      ['Incident category', '✅ Yes', 'Dashboard aggregation (e.g. "phishing")'],
                      ['Country', '✅ Yes', 'Geographic threat map (country-level only)'],
                      ['Age group', '✅ Yes (optional)', 'Demographic trend analysis'],
                      ['Description', '✅ Yes', 'Research review (never published verbatim)'],
                      ['Financial loss estimate', '✅ Yes (optional)', 'Economic impact aggregation'],
                      ['Your name', '❌ Never collected', 'N/A — anonymous by design'],
                      ['Your email', '❌ Never collected', 'N/A — anonymous by design'],
                      ['Your IP address', '❌ Never logged', 'N/A — privacy by default'],
                    ].map(([field, stored, purpose], i) => (
                      <tr key={field} className={i % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800/40'}>
                        <td className="px-4 py-2 font-medium text-gray-800 dark:text-gray-200">{field}</td>
                        <td className={`px-4 py-2 font-semibold ${stored.startsWith('✅') ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`}>{stored}</td>
                        <td className="px-4 py-2 text-gray-600 dark:text-gray-400">{purpose}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        {/* French framework references */}
        <section className="bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-8">
          <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-2">
            🇫🇷 French & International Framework References
          </h2>
          <p className="text-sm text-gray-500 dark:text-gray-400 mb-6">
            SafePulse's privacy architecture is inspired by these French and European standards.
          </p>
          <div className="space-y-4">
            {[
              {
                org:'ANSSI — Agence Nationale de la Sécurité des Systèmes d\'Information',
                country:'🇫🇷 Paris, France',
                ref:'SecNumCloud qualification framework (citizen-grade cybersecurity)',
                how:'SafePulse\'s zero-retention policy and ephemeral analysis follow ANSSI\'s transparency-first guidance for civilian digital tools.',
              },
              {
                org:'INTERPOL I-GRIP — Global Rapid Intervention of Payments',
                country:'🇫🇷 Lyon, France',
                ref:'Anti-fraud incident taxonomy and reporting standards',
                how:'SafePulse\'s incident categories (phishing, investment fraud, romance scam, money mule, radicalisation) are aligned with INTERPOL\'s I-GRIP threat classification.',
              },
              {
                org:'European General Data Protection Regulation (GDPR)',
                country:'🇪🇺 European Union',
                ref:'Articles 5, 25 — Data minimisation, purpose limitation, privacy-by-design',
                how:'SafePulse is GDPR-inspired. No personal identifiers are collected. All data processing has a defined, limited purpose. Users are anonymous by default.',
              },
            ].map((ref) => (
              <div key={ref.org} className="bg-white/70 dark:bg-gray-900/50 rounded-xl p-4">
                <div className="flex items-start gap-3">
                  <span className="text-lg mt-0.5">{ref.country.split(' ')[0]}</span>
                  <div>
                    <p className="font-semibold text-gray-900 dark:text-white text-sm">{ref.org}</p>
                    <p className="text-xs text-blue-600 dark:text-blue-400 mb-2">{ref.ref}</p>
                    <p className="text-sm text-gray-600 dark:text-gray-400">{ref.how}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </section>

        {/* Digital forensics */}
        <section className="bg-gray-50 dark:bg-gray-800/60 rounded-2xl p-8">
          <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
            🔎 Digital Forensics Evidence Log
          </h2>
          <p className="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
            Anonymous incident reports submitted through SafePulse are stored in a structured format
            compatible with digital forensics handover to law-enforcement and consumer-protection partners.
            Each report includes a timestamp, threat category, country, and description — sufficient
            for pattern analysis and case correlation without compromising reporter anonymity.
          </p>
          <div className="flex flex-wrap gap-3">
            {['Timestamp preserved','Category + country only','No reporter identity','Law-enforcement compatible structure','Aggregated for public dashboard'].map((f) => (
              <span key={f} className="text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full">
                ✓ {f}
              </span>
            ))}
          </div>
        </section>

        {/* Contact */}
        <section className="text-center py-6 border-t border-gray-100 dark:border-gray-800">
          <p className="text-gray-500 dark:text-gray-400 text-sm">
            Questions about data handling? Contact us at{' '}
            <a href="mailto:@gmail.com" className="text-primary-600 dark:text-primary-400 hover:underline">
              @gmail.com
            </a>
          </p>
          <p className="text-xs text-gray-400 mt-2">
            This policy was last updated: May 2026 · Version 1.0
          </p>
        </section>
      </article>
    </>
  );
}
