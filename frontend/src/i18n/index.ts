import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';
import en from './locales/en.json';
import id from './locales/id.json';

// To add Japanese (ja) or German (de) later:
// 1. Create src/i18n/locales/ja.json and de.json
// 2. Import them here
// 3. Add to the `resources` object below

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    resources: {
      en: { translation: en },
      id: { translation: id },
      // ja: { translation: ja },
      // de: { translation: de },
    },
    fallbackLng: 'en',
    supportedLngs: ['en', 'id'],
    interpolation: { escapeValue: false },
    detection: {
      order: ['localStorage', 'navigator'],
      caches: ['localStorage'],
    },
  });

export default i18n;
