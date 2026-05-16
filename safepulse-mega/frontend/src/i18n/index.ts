import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

// Existing 12 languages
import en    from './locales/en.json';
import id    from './locales/id.json';
import ar    from './locales/ar.json';
import fr    from './locales/fr.json';
import de    from './locales/de.json';
import es    from './locales/es.json';
import zh    from './locales/zh.json';
import zhTW  from './locales/zh-TW.json';
import ru    from './locales/ru.json';
import ko    from './locales/ko.json';
import ja    from './locales/ja.json';
import jv    from './locales/jv.json';

// 4 new ASEAN languages
import th    from './locales/th.json';
import vi    from './locales/vi.json';
import tl    from './locales/tl.json';
import km    from './locales/km.json';

export const SUPPORTED_LANGUAGES = [
  // Latin-script
  { code: 'en',     name: 'English',          flag: '🇬🇧', family: 'latin'   },
  { code: 'id',     name: 'Bahasa Indonesia', flag: '🇮🇩', family: 'latin'   },
  { code: 'fr',     name: 'Français',         flag: '🇫🇷', family: 'latin'   },
  { code: 'de',     name: 'Deutsch',          flag: '🇩🇪', family: 'latin'   },
  { code: 'es',     name: 'Español',          flag: '🇪🇸', family: 'latin'   },
  { code: 'tl',     name: 'Filipino',         flag: '🇵🇭', family: 'latin'   },
  { code: 'vi',     name: 'Tiếng Việt',       flag: '🇻🇳', family: 'latin'   },

  // Arabic
  { code: 'ar',     name: 'العربية',          flag: '🇸🇦', family: 'arabic',  rtl: true },

  // East/SE-Asian
  { code: 'zh',     name: '简体中文',          flag: '🇨🇳', family: 'asian'   },
  { code: 'zh-TW',  name: '繁體中文',          flag: '🇹🇼', family: 'asian'   },
  { code: 'ja',     name: '日本語',            flag: '🇯🇵', family: 'asian'   },
  { code: 'ko',     name: '한국어',            flag: '🇰🇷', family: 'asian'   },
  { code: 'th',     name: 'ไทย',              flag: '🇹🇭', family: 'asian'   },
  { code: 'km',     name: 'ខ្មែរ',             flag: '🇰🇭', family: 'asian'   },

  // Cyrillic
  { code: 'ru',     name: 'Русский',          flag: '🇷🇺', family: 'cyrillic' },

  // Aksara Jawa
  { code: 'jv',     name: 'ꦠꦏ꧀ꦱꦏꦂꦗꦮ',   flag: '🇮🇩', family: 'aksara'  },
];

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    resources: {
      en:      { translation: en },
      id:      { translation: id },
      ar:      { translation: ar },
      fr:      { translation: fr },
      de:      { translation: de },
      es:      { translation: es },
      zh:      { translation: zh },
      'zh-TW': { translation: zhTW },
      ru:      { translation: ru },
      ko:      { translation: ko },
      ja:      { translation: ja },
      jv:      { translation: jv },
      th:      { translation: th },
      vi:      { translation: vi },
      tl:      { translation: tl },
      km:      { translation: km },
    },
    fallbackLng: 'en',
    interpolation: { escapeValue: false },
    detection: {
      order: ['localStorage','navigator'],
      caches: ['localStorage'],
    },
  });

export default i18n;
