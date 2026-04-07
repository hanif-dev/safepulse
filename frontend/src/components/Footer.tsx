import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

export default function Footer() {
  const { t } = useTranslation();
  return (
    <footer className="bg-gray-900 dark:bg-gray-950 text-gray-300 mt-auto">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        {/* Brand */}
        <div>
          <div className="flex items-center gap-2 mb-3">
            <span className="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-sm">SP</span>
            <span className="font-bold text-lg text-white">SafePulse</span>
          </div>
          <p className="text-sm leading-relaxed text-gray-400">{t('footer.tagline')}</p>
        </div>

        {/* Links */}
        <div>
          <h3 className="text-white font-semibold mb-3 text-sm uppercase tracking-wide">{t('footer.links_title')}</h3>
          <ul className="space-y-2 text-sm">
            {[
              ['/products',  t('nav.products')],
              ['/impact',    t('nav.impact')],
              ['/insights',  t('nav.insights')],
              ['/check',     t('nav.checker')],
              ['/report',    t('nav.report')],
              ['/dashboard', t('nav.dashboard')],
            ].map(([to, label]) => (
              <li key={to}>
                <Link to={to} className="hover:text-primary-400 transition-colors">{label}</Link>
              </li>
            ))}
          </ul>
        </div>

        {/* Contact & legal */}
        <div>
          <h3 className="text-white font-semibold mb-3 text-sm uppercase tracking-wide">{t('nav.contact')}</h3>
          <ul className="space-y-2 text-sm">
            <li><Link to="/contact" className="hover:text-primary-400 transition-colors">{t('nav.contact')}</Link></li>
            <li><a href="#" className="hover:text-primary-400 transition-colors">{t('footer.legal')}</a></li>
          </ul>
        </div>
      </div>
      <div className="border-t border-gray-800 py-4 text-center text-xs text-gray-500">
        {t('footer.copyright')}
      </div>
    </footer>
  );
}
