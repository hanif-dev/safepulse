import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import { fetchArticle, Article } from '../services/api';
import { useTextToSpeech } from '../hooks/useTextToSpeech';

const CATEGORY_COLORS: Record<string, string> = {
  scam:               'bg-danger-100 text-danger-700',
  radicalization:     'bg-orange-100 text-orange-700',
  money_laundering:   'bg-purple-100 text-purple-700',
  digital_resilience: 'bg-primary-100 text-primary-700',
  youth_peace:        'bg-accent-100 text-accent-700',
};

export default function InsightDetail() {
  const { slug } = useParams<{ slug: string }>();
  const { t }    = useTranslation();
  const tts      = useTextToSpeech();

  const [article, setArticle] = useState<Article | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError]     = useState(false);

  useEffect(() => {
    if (!slug) return;
    setLoading(true);
    setError(false);
    fetchArticle(slug)
      .then((res) => setArticle(res.data))
      .catch(() => setError(true))
      .finally(() => setLoading(false));
  }, [slug]);

  // TTS: strip markdown and read plain text
  const handleTTS = () => {
    if (!article) return;
    if (tts.speaking) {
      tts.stop();
    } else {
      const plain = (article.body_markdown ?? '')
        .replace(/#+\s/g, '')
        .replace(/\*\*/g, '')
        .replace(/\*/g, '')
        .replace(/\[([^\]]+)\]\([^)]+\)/g, '$1')
        .replace(/\n{2,}/g, '. ');
      const lang = article.language === 'id' ? 'id-ID' : 'en-US';
      tts.speak(plain, lang);
    }
  };

  if (loading) {
    return (
      <div className="max-w-3xl mx-auto px-4 py-20 text-center text-gray-400">{t('common.loading')}</div>
    );
  }

  if (error || !article) {
    return (
      <div className="max-w-3xl mx-auto px-4 py-20 text-center">
        <p className="text-gray-500 mb-4">{t('common.error')}</p>
        <Link to="/insights" className="text-primary-600 hover:underline">← {t('common.back')}</Link>
      </div>
    );
  }

  return (
    <article className="max-w-3xl mx-auto px-4 sm:px-6 py-12">
      {/* Back link */}
      <Link
        to="/insights"
        className="inline-flex items-center gap-1 text-sm text-primary-600 dark:text-primary-400 hover:underline mb-8"
      >
        ← {t('common.back')} to Insights
      </Link>

      {/* Meta */}
      <div className="flex flex-wrap items-center gap-3 mb-4">
        <span className={`text-xs font-semibold px-2.5 py-1 rounded-full ${CATEGORY_COLORS[article.category] ?? 'bg-gray-100 text-gray-600'}`}>
          {t(`insights.categories.${article.category}`, { defaultValue: article.category })}
        </span>
        <span className="text-xs text-gray-400 uppercase">{article.language}</span>
        <span className="text-xs text-gray-400">
          {t('common.published')}: {new Date(article.published_at).toLocaleDateString()}
        </span>
      </div>

      {/* Title */}
      <h1 className="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
        {article.title}
      </h1>

      {/* Summary */}
      <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-8 border-l-4 border-primary-400 pl-4 italic">
        {article.summary}
      </p>

      {/* TTS button */}
      {tts.supported && (
        <div className="mb-8">
          <button
            onClick={handleTTS}
            aria-label={tts.speaking ? t('accessibility.tts_stop') : t('accessibility.tts_play')}
            className={`inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg border transition-colors ${
              tts.speaking
                ? 'bg-danger-50 border-danger-300 text-danger-700 hover:bg-danger-100'
                : 'bg-primary-50 border-primary-300 text-primary-700 hover:bg-primary-100 dark:bg-primary-900/30 dark:border-primary-700 dark:text-primary-300'
            }`}
          >
            {tts.speaking ? '⏹ ' + t('accessibility.tts_stop') : '🔊 ' + t('accessibility.tts_play')}
          </button>
        </div>
      )}

      {/* Body */}
      <div className="markdown-body">
        <ReactMarkdown remarkPlugins={[remarkGfm]}>
          {article.body_markdown ?? ''}
        </ReactMarkdown>
      </div>

      {/* Footer nav */}
      <div className="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
        <Link
          to="/insights"
          className="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-semibold hover:underline"
        >
          ← Browse all Insights
        </Link>
      </div>
    </article>
  );
}
