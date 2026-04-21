import { useEffect, useState } from "react";
import { Link, NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";
import i18n from "../i18n";
import { useFontSize } from "../hooks/useFontSize";

interface NavbarProps {
  theme: "light" | "dark";
  toggleTheme: () => void;
  fontSize: ReturnType<typeof useFontSize>;
}

const LANGUAGES = [
  {
    code: "en",
    label: "English",
    nativeLabel: "English",
    flag: "🇬🇧",
    rtl: false,
  },
  {
    code: "id",
    label: "Indonesian",
    nativeLabel: "Bahasa Indonesia",
    flag: "🇮🇩",
    rtl: false,
  },
  {
    code: "ar",
    label: "Arabic",
    nativeLabel: "العربية",
    flag: "🇸🇦",
    rtl: true,
  },
  {
    code: "fr",
    label: "French",
    nativeLabel: "Français",
    flag: "🇫🇷",
    rtl: false,
  },
  {
    code: "de",
    label: "German",
    nativeLabel: "Deutsch",
    flag: "🇩🇪",
    rtl: false,
  },
  {
    code: "es",
    label: "Spanish",
    nativeLabel: "Español",
    flag: "🇪🇸",
    rtl: false,
  },
  {
    code: "zh",
    label: "Chinese (Simplified)",
    nativeLabel: "中文（简体）",
    flag: "🇨🇳",
    rtl: false,
  },
  {
    code: "zh-TW",
    label: "Chinese (Traditional)",
    nativeLabel: "中文（繁體）",
    flag: "🇹🇼",
    rtl: false,
  },
  {
    code: "ru",
    label: "Russian",
    nativeLabel: "Русский",
    flag: "🇷🇺",
    rtl: false,
  },
  {
    code: "ko",
    label: "Korean",
    nativeLabel: "한국어",
    flag: "🇰🇷",
    rtl: false,
  },
  {
    code: "ja",
    label: "Japanese",
    nativeLabel: "日本語",
    flag: "🇯🇵",
    rtl: false,
  },
  {
    code: "jv",
    label: "Javanese",
    nativeLabel: "ꦧꦱꦗꦮ",
    flag: "🏝️",
    rtl: false,
  },
];

const RTL_LANGS = new Set(["ar"]);

export default function Navbar({ theme, toggleTheme, fontSize }: NavbarProps) {
  const { t } = useTranslation();
  const [mobileOpen, setMobileOpen] = useState(false);
  const [langOpen, setLangOpen] = useState(false);

  const applyDir = (lng: string) => {
    const isRTL = RTL_LANGS.has(lng);
    document.documentElement.setAttribute("dir", isRTL ? "rtl" : "ltr");
    document.documentElement.setAttribute("lang", lng);
  };

  useEffect(() => {
    applyDir(i18n.language);
    const handler = (lng: string) => applyDir(lng);
    i18n.on("languageChanged", handler);
    return () => i18n.off("languageChanged", handler);
  }, []);

  const handleLangChange = (code: string) => {
    i18n.changeLanguage(code);
    setLangOpen(false);
    setMobileOpen(false);
  };

  const currentLang =
    LANGUAGES.find((l) => l.code === i18n.language) ?? LANGUAGES[0];

  const navLinkClass = ({ isActive }: { isActive: boolean }) =>
    `text-sm font-medium transition-colors hover:text-primary-600 dark:hover:text-primary-400 ${
      isActive
        ? "text-primary-700 dark:text-primary-400"
        : "text-gray-700 dark:text-gray-300"
    }`;

  return (
    <header className="sticky top-0 z-50 bg-white/90 dark:bg-gray-950/90 backdrop-blur border-b border-gray-100 dark:border-gray-800">
      <nav
        className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
        aria-label="Main navigation"
      >
        {/* Logo */}
        <Link to="/" className="flex items-center gap-2 shrink-0">
          <span className="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-sm">
            SP
          </span>
          <span className="font-bold text-lg text-gray-900 dark:text-white">
            SafePulse
          </span>
        </Link>

        {/* Desktop nav links */}
        <div className="hidden md:flex items-center gap-6" role="menubar">
          <NavLink to="/products" className={navLinkClass} role="menuitem">
            {t("nav.products")}
          </NavLink>
          <NavLink to="/impact" className={navLinkClass} role="menuitem">
            {t("nav.impact")}
          </NavLink>
          <NavLink to="/evidence" className={navLinkClass} role="menuitem">
            {t("nav.evidence")}
          </NavLink>
          <NavLink to="/insights" className={navLinkClass} role="menuitem">
            {t("nav.insights")}
          </NavLink>
          <NavLink to="/dashboard" className={navLinkClass} role="menuitem">
            {t("nav.dashboard")}
          </NavLink>
          <NavLink to="/seo-geo" className={navLinkClass} role="menuitem">
            {t("nav.seo_geo")}
          </NavLink>
          <NavLink to="/contact" className={navLinkClass} role="menuitem">
            {t("nav.contact")}
          </NavLink>
        </div>

        {/* Right controls */}
        <div className="flex items-center gap-1.5">
          {/* Font size */}
          <div
            className="hidden md:flex items-center"
            aria-label="Font size controls"
          >
            <button
              onClick={fontSize.decrease}
              disabled={!fontSize.canDecrease}
              aria-label={t("accessibility.font_decrease")}
              className="w-7 h-7 rounded text-xs font-bold flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-30 transition-colors"
            >
              A-
            </button>
            <button
              onClick={fontSize.increase}
              disabled={!fontSize.canIncrease}
              aria-label={t("accessibility.font_increase")}
              className="w-7 h-7 rounded text-xs font-bold flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-30 transition-colors"
            >
              A+
            </button>
          </div>

          {/* Theme toggle */}
          <button
            onClick={toggleTheme}
            aria-label={t("accessibility.theme_toggle")}
            className="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
          >
            {theme === "dark" ? "☀️" : "🌙"}
          </button>

          {/* Language picker */}
          <div className="relative">
            <button
              onClick={() => setLangOpen((o) => !o)}
              aria-haspopup="listbox"
              aria-expanded={langOpen}
              aria-label={t("nav.global")}
              className="flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-400 transition-colors"
            >
              <span aria-hidden="true" className="text-base">
                {currentLang.flag}
              </span>
              <span
                className="hidden sm:inline max-w-[96px] truncate"
                style={{
                  fontFamily:
                    currentLang.code === "jv"
                      ? '"Noto Sans Javanese", serif'
                      : "inherit",
                }}
              >
                {currentLang.nativeLabel}
              </span>
              <span className="text-gray-400 text-[10px]" aria-hidden="true">
                ▾
              </span>
            </button>

            {langOpen && (
              <>
                <div
                  className="fixed inset-0 z-40"
                  onClick={() => setLangOpen(false)}
                  aria-hidden="true"
                />
                <ul
                  role="listbox"
                  aria-label="Select language"
                  className="absolute right-0 mt-2 w-60 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl py-1 z-50 max-h-[80vh] overflow-y-auto"
                >
                  {[
                    {
                      group: "Latin script",
                      codes: ["en", "id", "fr", "de", "es"],
                    },
                    { group: "Arabic script", codes: ["ar"] },
                    {
                      group: "Asian scripts",
                      codes: ["zh", "zh-TW", "ko", "ja"],
                    },
                    { group: "Cyrillic", codes: ["ru"] },
                    { group: "Aksara Jawa", codes: ["jv"] },
                  ].map(({ group, codes }) => {
                    const groupLangs = LANGUAGES.filter((l) =>
                      codes.includes(l.code),
                    );
                    if (!groupLangs.length) return null;
                    return (
                      <li key={group}>
                        <p className="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                          {group}
                        </p>
                        <ul>
                          {groupLangs.map((lang) => {
                            const isSelected = i18n.language === lang.code;
                            return (
                              <li
                                key={lang.code}
                                role="option"
                                aria-selected={isSelected}
                              >
                                <button
                                  onClick={() => handleLangChange(lang.code)}
                                  dir={lang.rtl ? "rtl" : "ltr"}
                                  className={`w-full flex items-center gap-3 px-3 py-2 text-sm transition-colors ${
                                    isSelected
                                      ? "bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300"
                                      : "text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800"
                                  }`}
                                >
                                  <span
                                    className="text-base shrink-0"
                                    aria-hidden="true"
                                  >
                                    {lang.flag}
                                  </span>
                                  <span className="flex-1 text-left leading-tight">
                                    <span
                                      className="block font-medium"
                                      style={{
                                        fontFamily:
                                          lang.code === "jv"
                                            ? '"Noto Sans Javanese", serif'
                                            : "inherit",
                                      }}
                                    >
                                      {lang.nativeLabel}
                                    </span>
                                    <span className="block text-[11px] text-gray-400 dark:text-gray-500">
                                      {lang.label}
                                    </span>
                                  </span>
                                  {isSelected && (
                                    <span
                                      className="text-primary-600 dark:text-primary-400 text-xs font-bold shrink-0"
                                      aria-hidden="true"
                                    >
                                      ✓
                                    </span>
                                  )}
                                </button>
                              </li>
                            );
                          })}
                        </ul>
                      </li>
                    );
                  })}
                </ul>
              </>
            )}
          </div>

          {/* CTA desktop */}
          <Link
            to="/check"
            className="hidden md:inline-flex items-center gap-1 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors"
          >
            {t("nav.checker")}
          </Link>

          {/* Mobile hamburger */}
          <button
            className="md:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
            onClick={() => setMobileOpen((o) => !o)}
            aria-label="Open navigation menu"
            aria-expanded={mobileOpen}
          >
            <span className="text-xl">{mobileOpen ? "✕" : "☰"}</span>
          </button>
        </div>
      </nav>

      {/* Mobile menu */}
      {mobileOpen && (
        <div className="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950 px-4 pb-6">
          {[
            ["/products", t("nav.products")],
            ["/impact", t("nav.impact")],
            ["/evidence", t("nav.evidence")],
            ["/insights", t("nav.insights")],
            ["/dashboard", t("nav.dashboard")],
            ["/seo-geo", t("nav.seo_geo")],
            ["/contact", t("nav.contact")],
            ["/check", t("nav.checker")],
            ["/report", t("nav.report")],
          ].map(([to, label]) => (
            <NavLink
              key={to}
              to={to}
              onClick={() => setMobileOpen(false)}
              className={({ isActive }) =>
                `block py-2.5 text-sm font-medium border-b border-gray-50 dark:border-gray-800/50 ${
                  isActive
                    ? "text-primary-600"
                    : "text-gray-700 dark:text-gray-300"
                }`
              }
            >
              {label}
            </NavLink>
          ))}

          {/* Language grid */}
          <div className="pt-5">
            <p className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">
              {t("nav.global")}
            </p>
            <div className="grid grid-cols-2 gap-2">
              {LANGUAGES.map((lang) => {
                const isSelected = i18n.language === lang.code;
                return (
                  <button
                    key={lang.code}
                    onClick={() => handleLangChange(lang.code)}
                    dir={lang.rtl ? "rtl" : "ltr"}
                    className={`flex items-center gap-2 px-3 py-2 rounded-xl text-sm transition-colors ${
                      isSelected
                        ? "bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 font-semibold ring-1 ring-primary-400/30"
                        : "bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    }`}
                  >
                    <span aria-hidden="true">{lang.flag}</span>
                    <span
                      className="truncate leading-tight"
                      style={{
                        fontFamily:
                          lang.code === "jv"
                            ? '"Noto Sans Javanese", serif'
                            : "inherit",
                      }}
                    >
                      {lang.nativeLabel}
                    </span>
                  </button>
                );
              })}
            </div>
          </div>
        </div>
      )}
    </header>
  );
}
