import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { fetchArticles, Article } from "../services/api";
import SectionHero from "../components/SectionHero";

const CATEGORIES = [
  "scam",
  "radicalization",
  "money_laundering",
  "digital_resilience",
  "youth_peace",
];
const LANGUAGES = [
  { code: "en", label: "English" },
  { code: "id", label: "Bahasa Indonesia" },
];

const CATEGORY_COLORS: Record<string, string> = {
  scam: "bg-danger-50 text-danger-700 dark:bg-danger-900/30 dark:text-danger-300",
  radicalization:
    "bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300",
  money_laundering:
    "bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300",
  digital_resilience:
    "bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300",
  youth_peace:
    "bg-accent-50 text-accent-700 dark:bg-accent-900/30 dark:text-accent-300",
};

function ArticleCard({ article }: { article: Article }) {
  const { t } = useTranslation();
  return (
    <article className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col hover:shadow-lg hover:-translate-y-0.5 transition-all">
      <div className="p-6 flex-1 flex flex-col">
        <div className="flex items-center gap-2 mb-3">
          <span
            className={`text-xs font-semibold px-2.5 py-1 rounded-full ${CATEGORY_COLORS[article.category] ?? "bg-gray-100 text-gray-600"}`}
          >
            {t(`insights.categories.${article.category}`, {
              defaultValue: article.category,
            })}
          </span>
          <span className="text-xs text-gray-400 uppercase">
            {article.language}
          </span>
        </div>
        <h2 className="font-bold text-gray-900 dark:text-white mb-3 text-lg leading-snug line-clamp-2">
          {article.title}
        </h2>
        <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed flex-1 line-clamp-3">
          {article.summary}
        </p>
      </div>
      <div className="px-6 pb-6 flex items-center justify-between">
        <span className="text-xs text-gray-400">
          {t("common.published")}:{" "}
          {new Date(article.published_at).toLocaleDateString()}
        </span>
        <Link
          to={`/insights/${article.slug}`}
          className="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline"
          aria-label={`Read more about ${article.title}`}
        >
          {t("insights.read_more")} →
        </Link>
      </div>
    </article>
  );
}

export default function Insights() {
  const { t } = useTranslation();
  const [articles, setArticles] = useState<Article[]>([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");
  const [category, setCategory] = useState("");
  const [language, setLanguage] = useState("");
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);

  useEffect(() => {
    setLoading(true);
    fetchArticles({ search, category, language, page })
      .then((res) => {
        setArticles(res.data);
        setTotal(res.meta.total);
        setLastPage(res.meta.last_page);
      })
      .finally(() => setLoading(false));
  }, [search, category, language, page]);

  const handleFilter = (setter: (v: string) => void) => (v: string) => {
    setter(v);
    setPage(1);
  };

  return (
    <>
      <SectionHero
        title={t("insights.title")}
        subtitle={t("insights.subtitle")}
      />

      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* ── Filters ─────────────────────────────────────────────────── */}
        <div className="flex flex-col sm:flex-row gap-4 mb-10">
          <label className="sr-only" htmlFor="search-input">
            {t("insights.search_placeholder")}
          </label>
          <input
            id="search-input"
            type="search"
            value={search}
            onChange={(e) => {
              setSearch(e.target.value);
              setPage(1);
            }}
            placeholder={t("insights.search_placeholder")}
            className="flex-1 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
          />

          <label className="sr-only" htmlFor="category-select">
            {t("insights.filter_category")}
          </label>
          <select
            id="category-select"
            value={category}
            onChange={(e) => handleFilter(setCategory)(e.target.value)}
            className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
          >
            <option value="">{t("insights.all_categories")}</option>
            {CATEGORIES.map((c) => (
              <option key={c} value={c}>
                {t(`insights.categories.${c}`, { defaultValue: c })}
              </option>
            ))}
          </select>

          <label className="sr-only" htmlFor="language-select">
            {t("insights.filter_language")}
          </label>
          <select
            id="language-select"
            value={language}
            onChange={(e) => handleFilter(setLanguage)(e.target.value)}
            className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
          >
            <option value="">{t("insights.all_languages")}</option>
            {LANGUAGES.map((l) => (
              <option key={l.code} value={l.code}>
                {l.label}
              </option>
            ))}
          </select>
        </div>

        {/* ── Results count ───────────────────────────────────────────── */}
        {!loading && (
          <p className="text-sm text-gray-500 dark:text-gray-400 mb-6">
            {total} article{total !== 1 ? "s" : ""} found
          </p>
        )}

        {/* ── Featured static card ────────────────────────────────────── */}
        <div className="mb-6">
          <Link to="/insights/how-to-identify-online-scams">
            <div className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all p-6">
              <div className="flex items-center gap-2 mb-3">
                <span
                  className={`text-xs font-semibold px-2.5 py-1 rounded-full ${CATEGORY_COLORS["scam"]}`}
                >
                  {t("insights.categories.scam", { defaultValue: "scam" })}
                </span>
                <span className="text-xs text-gray-400 uppercase">EN</span>
              </div>
              <h3 className="font-bold text-gray-900 dark:text-white mb-3 text-lg leading-snug">
                How to Identify Online Scams in Southeast Asia
              </h3>
              <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                Spot phishing, investment fraud, romance scams, and more — with
                our 5-pattern framework.
              </p>
            </div>
          </Link>
        </div>

        {/* ── Grid ────────────────────────────────────────────────────── */}
        {loading ? (
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {[...Array(6)].map((_, i) => (
              <div
                key={i}
                className="bg-gray-100 dark:bg-gray-800 rounded-2xl h-64 animate-pulse"
              />
            ))}
          </div>
        ) : articles.length === 0 ? (
          <p className="text-center text-gray-400 py-16">
            {t("insights.no_results")}
          </p>
        ) : (
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {articles.map((a) => (
              <ArticleCard key={a.id} article={a} />
            ))}
          </div>
        )}

        {/* ── Pagination ──────────────────────────────────────────────── */}
        {lastPage > 1 && (
          <div className="flex justify-center gap-2 mt-10">
            {[...Array(lastPage)].map((_, i) => (
              <button
                key={i}
                onClick={() => setPage(i + 1)}
                aria-current={page === i + 1 ? "page" : undefined}
                className={`w-10 h-10 rounded-lg text-sm font-semibold transition-colors ${
                  page === i + 1
                    ? "bg-primary-600 text-white"
                    : "bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                }`}
              >
                {i + 1}
              </button>
            ))}
          </div>
        )}
      </section>
    </>
  );
}
