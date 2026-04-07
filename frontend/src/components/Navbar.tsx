import { useState } from 'react';
import { Link, NavLink } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import i18n from '../i18n';
import { useFontSize } from '../hooks/useFontSize';

interface NavbarProps {
  theme: 'light' | 'dark';
  toggleTheme: () => void;
  fontSize: ReturnType<typeof useFontSize>;
}

const LANGUAGES = [
  { code: 'en', label: 'English' },
  { code: 'id', label: 'Bahasa Indonesia' },
  // Add { code: 'ja', label: '日本語' } when ready
  // Add { code: 'de', label: 'Deutsch' } when ready
];

export default function Navbar({ theme, toggleTheme, fontSize }: NavbarProps) {
  const { t } = useTranslation();
  const [mobileOpen, setMobileOpen] = useState(false);
  const [langOpen, setLangOpen]     = useState(false);

  const navLinkClass = ({ isActive }: { isActive: boolean }) =>
    `text-sm font-medium transition-colors hover:text-primary-600 dark:hover:text-primary-400 ${
      isActive ? 'text-primary-700 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300'
    }`;

  return (
    <header className="sticky top-0 z-50 bg-white/90 dark:bg-gray-950/90 backdrop-blur border-b border-gray-100 dark:border-gray-800">
      <nav
        className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
        aria-label="Main navigation"
      >
        {/* Logo */}
        <Link to="/" className="flex items-center gap-2 shrink-0">
          <span className="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-sm">SP</span>
          <span className="font-bold text-lg text-gray-900 dark:text-white">SafePulse</span>
        </Link>

        {/* Desktop links */}
        <div className="hidden md:flex items-center gap-6" role="menubar">
          <NavLink to="/products"  className={navLinkClass} role="menuitem">{t('nav.products')}</NavLink>
          <NavLink to="/impact"    className={navLinkClass} role="menuitem">{t('nav.impact')}</NavLink>
          <NavLink to="/evidence"  className={navLinkClass} role="menuitem">{t('nav.evidence')}</NavLink>
          <NavLink to="/insights"  className={navLinkClass} role="menuitem">{t('nav.insights')}</NavLink>
          <NavLink to="/dashboard" className={navLinkClass} role="menuitem">{t('nav.dashboard')}</NavLink>
          <NavLink to="/contact"   className={navLinkClass} role="menuitem">{t('nav.contact')}</NavLink>
        </div>

        {/* Right controls */}
        <div className="flex items-center gap-2">
          {/* Font size controls */}
          <div className="hidden md:flex items-center gap-1" aria-label="Font size controls">
            <button
              onClick={fontSize.decrease}
              disabled={!fontSize.canDecrease}
              aria-label={t('accessibility.font_decrease')}
              className="w-7 h-7 rounded flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-30"
            >A-</button>
            <button
              onClick={fontSize.increase}
              disabled={!fontSize.canIncrease}
              aria-label={t('accessibility.font_increase')}
              className="w-7 h-7 rounded flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-30"
            >A+</button>
          </div>

          {/* Theme toggle */}
          <button
            onClick={toggleTheme}
            aria-label={t('accessibility.theme_toggle')}
            className="w-9 h-9 rounded-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
          >
            {theme === 'dark' ? '☀️' : '🌙'}
          </button>

          {/* Language picker */}
          <div className="relative">
            <button
              onClick={() => setLangOpen((o) => !o)}
              aria-haspopup="listbox"
              aria-expanded={langOpen}
              className="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-2 py-1 rounded"
            >
              🌐 {t('nav.global')}
            </button>
            {langOpen && (
              <ul
                role="listbox"
                aria-label="Select language"
                className="absolute right-0 mt-1 w-44 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1 z-50"
              >
                {LANGUAGES.map((lang) => (
                  <li key={lang.code} role="option" aria-selected={i18n.language === lang.code}>
                    <button
                      onClick={() => { i18n.changeLanguage(lang.code); setLangOpen(false); }}
                      className={`w-full text-left px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 ${
                        i18n.language === lang.code ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'
                      }`}
                    >
                      {lang.label}
                    </button>
                  </li>
                ))}
              </ul>
            )}
          </div>

          {/* CTA */}
          <Link
            to="/check"
            className="hidden md:inline-flex items-center gap-1 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors"
          >
            {t('nav.checker')}
          </Link>

          {/* Mobile menu button */}
          <button
            className="md:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
            onClick={() => setMobileOpen((o) => !o)}
            aria-label="Open navigation menu"
            aria-expanded={mobileOpen}
          >
            <span className="text-xl">{mobileOpen ? '✕' : '☰'}</span>
          </button>
        </div>
      </nav>

      {/* Mobile menu */}
      {mobileOpen && (
        <div className="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950 px-4 pb-4">
          {[
            ['/products',  t('nav.products')],
            ['/impact',    t('nav.impact')],
            ['/evidence',  t('nav.evidence')],
            ['/insights',  t('nav.insights')],
            ['/dashboard', t('nav.dashboard')],
            ['/contact',   t('nav.contact')],
            ['/check',     t('nav.checker')],
            ['/report',    t('nav.report')],
          ].map(([to, label]) => (
            <NavLink
              key={to}
              to={to}
              onClick={() => setMobileOpen(false)}
              className={({ isActive }) =>
                `block py-2 text-sm font-medium border-b border-gray-50 dark:border-gray-800 ${
                  isActive ? 'text-primary-600' : 'text-gray-700 dark:text-gray-300'
                }`
              }
            >
              {label}
            </NavLink>
          ))}
        </div>
      )}
    </header>
  );
}
