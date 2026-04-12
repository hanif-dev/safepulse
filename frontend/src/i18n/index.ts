import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import LanguageDetector from "i18next-browser-languagedetector";
import en from "./locales/en.json";
import id from "./locales/id.json";
import ar from "./locales/ar.json";
import fr from "./locales/fr.json";
import de from "./locales/de.json";
import es from "./locales/es.json";
import zh from "./locales/zh.json"; // Mandarin Sederhana
import zhTW from "./locales/zh-TW.json"; // Mandarin Tradisional
import ru from "./locales/ru.json";
import ko from "./locales/ko.json";
import ja from "./locales/ja.json";
import jv from "./locales/jv.json"; // Bahasa Jawa – Aksara Jawa

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
      ar: { translation: ar },
      fr: { translation: fr },
      de: { translation: de },
      es: { translation: es },
      zh: { translation: zh },
      "zh-TW": { translation: zhTW },
      ru: { translation: ru },
      ko: { translation: ko },
      ja: { translation: ja },
      jv: { translation: jv },
    },
    fallbackLng: "en",
    supportedLngs: [
      "en",
      "id",
      "ar",
      "fr",
      "de",
      "es",
      "zh",
      "zh-TW",
      "ru",
      "ko",
      "ja",
      "jv",
    ],
    interpolation: { escapeValue: false },
    detection: {
      order: ["localStorage", "navigator"],
      caches: ["localStorage"],
    },
  });

export default i18n;
