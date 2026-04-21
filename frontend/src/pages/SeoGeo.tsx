// frontend/src/pages/SeoGeo.tsx
// SEO · GEO · GenAI — SafePulse Portfolio Page
// Route: /seo-geo

import { useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";

/* ─── animated counter ─── */
function useCounter(target: number, duration = 1800) {
  const [value, setValue] = useState(0);
  const ref = useRef<HTMLDivElement | null>(null);
  const started = useRef(false);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting && !started.current) {
          started.current = true;
          const start = performance.now();
          const tick = (now: number) => {
            const p = Math.min((now - start) / duration, 1);
            setValue(Math.floor(p * target));
            if (p < 1) requestAnimationFrame(tick);
          };
          requestAnimationFrame(tick);
        }
      },
      { threshold: 0.3 },
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, [target, duration]);

  return { value, ref };
}

function AnimStat({
  value,
  suffix,
  label,
}: {
  value: number;
  suffix: string;
  label: string;
}) {
  const { value: v, ref } = useCounter(value);
  return (
    <div ref={ref} className="text-center">
      <div className="text-4xl font-black text-emerald-400 tabular-nums">
        {v.toLocaleString()}
        {suffix}
      </div>
      <div className="text-sm text-slate-400 mt-1">{label}</div>
    </div>
  );
}

function SectionLabel({ children }: { children: React.ReactNode }) {
  return (
    <div className="text-xs font-mono text-emerald-500 uppercase tracking-[0.2em] mb-2">
      {children}
    </div>
  );
}

function KwRow({
  kw,
  vol,
  intent,
}: {
  kw: string;
  vol: string;
  intent: string;
}) {
  const intentColor =
    intent === "Informational"
      ? "bg-blue-900/60 text-blue-300"
      : "bg-amber-900/60 text-amber-300";
  return (
    <tr className="border-b border-slate-800 hover:bg-slate-800/40 transition-colors">
      <td className="py-2.5 px-3 text-slate-200 text-sm font-mono">{kw}</td>
      <td className="py-2.5 px-3 text-center">
        <span
          className={`text-xs px-2 py-0.5 rounded-full ${
            vol === "High"
              ? "bg-emerald-900/60 text-emerald-300"
              : vol === "Medium"
                ? "bg-yellow-900/60 text-yellow-300"
                : "bg-slate-700 text-slate-400"
          }`}
        >
          {vol}
        </span>
      </td>
      <td className="py-2.5 px-3 text-center">
        <span className={`text-xs px-2 py-0.5 rounded-full ${intentColor}`}>
          {intent}
        </span>
      </td>
    </tr>
  );
}

function GeoCard({
  icon,
  title,
  items,
  color,
  titleColor,
}: {
  icon: string;
  title: string;
  items: string[];
  color: string;
  titleColor: string;
}) {
  return (
    <div className={`rounded-2xl border ${color} p-6`}>
      <div className="flex items-center gap-2 mb-4">
        <span className="text-2xl">{icon}</span>
        <h3 className={`font-black text-lg tracking-widest ${titleColor}`}>
          {title}
        </h3>
      </div>
      <ul className="space-y-2">
        {items.map((it, i) => (
          <li key={i} className="flex gap-2 text-sm text-slate-300">
            <span className="text-slate-600 mt-0.5 shrink-0">•</span>
            <span>{it}</span>
          </li>
        ))}
      </ul>
    </div>
  );
}

function EthicCard({
  icon,
  title,
  items,
  color,
  titleColor,
}: {
  icon: string;
  title: string;
  items: string[];
  color: string;
  titleColor: string;
}) {
  return (
    <div className={`rounded-2xl border ${color} bg-slate-900/30 p-6`}>
      <div className="flex items-center gap-2 mb-4">
        <span className="text-2xl">{icon}</span>
        <h3 className={`font-bold ${titleColor}`}>{title}</h3>
      </div>
      <ul className="space-y-2">
        {items.map((it, i) => (
          <li key={i} className="flex gap-2 text-sm text-slate-300">
            <span className="text-slate-600 mt-0.5 shrink-0 text-xs">—</span>
            <span>{it}</span>
          </li>
        ))}
      </ul>
    </div>
  );
}

/* ═══════════════════════════════════════════════════════════════════════════ */

export default function SeoGeo() {
  const { t } = useTranslation();

  // ── Static data — technical content intentionally stays in English ────────

  const GEO_ENTITIES = [
    "SafePulse Platform (digital safety tool)",
    "Online Scams: phishing, investment, romance",
    "Radicalization (online extremism)",
    "Money Mule Networks",
    "Southeast Asia (geographic context)",
    "Youth & Vulnerable Communities",
  ];

  const GEO_OUTCOMES = [
    "User recognises 5+ scam types by pattern",
    "User knows emergency steps if targeted",
    "Pages rank for protective queries",
    "Content cited by AI search engines",
    "Community reports incidents anonymously",
    "NGOs leverage SafePulse data",
  ];

  const GEO_CLARITY = [
    "One page = one dominant intent",
    "URL: /insights/how-to-identify-online-scams",
    "H1 directly answers the primary keyword",
    "Answer Capsule above fold for AI extraction",
    "FAQ schema covers long-tail questions",
    "Content verified by public health researchers",
  ];

  const AI_DOES = [
    "Generates 3 headline options per element",
    "Produces initial blog draft (~900 words)",
    "Suggests 5 tightening edits for review",
    "Proposes JSON-LD schema structure",
    "Generates translations for 12 languages",
    "Creates FAQ ideas from keyword research",
  ];

  const HUMAN_DECIDES = [
    "Chooses best headline from 3 AI options",
    "Edits intro — selects problem/solution style",
    "Verifies factual claims against official sources",
    "Removes culturally insensitive phrases (AR/JV)",
    "Manual JSON-LD validation in Google Rich Results",
    "Sets keyword density — never forced",
  ];

  const AI_NEVER = [
    "Publish without human review",
    "Fabricate statistics or citations",
    "Copy competitor content",
    "Ignore SafePulse brand voice",
    "Unilaterally decide language priorities",
    "Define the GEO entity framework",
  ];

  const SKILLS = [
    "Keyword cluster strategy with intent classification",
    "GEO entity + outcome framework on a real product",
    "AI-assisted on-page refinement with full editorial control",
    "Readability calibration FK 60–70 across 12 languages",
    "JSON-LD schema (Article + FAQPage) with trust signals",
    "Full-stack deployment: Codespaces → EC2 → Amplify + HTTPS",
    "Anonymous incident data → public health dashboard",
    "WCAG 2.1 AA accessibility + RTL support (Arabic)",
  ];

  const BEFORE_AFTER = [
    {
      element: "Title Tag",
      before: "Online Scams Southeast Asia — SafePulse",
      after: "How to Identify Online Scams in Southeast Asia | SafePulse",
      note: "Added action verb 'How to' + kept ≤65 chars",
    },
    {
      element: "Meta Description",
      before:
        "SafePulse helps you avoid online scams. Learn about phishing and more.",
      after:
        "Protect yourself and your family from phishing, investment fraud, and romance scams across SEA. Spot the 5 warning signs now — free, anonymous tool included.",
      note: "150–160 chars, benefit-led, CTA included",
    },
    {
      element: "H1",
      before: "How to Identify Online Scams Southeast Asia",
      after:
        "Scams, Fraud & Online Threats: What Every Person in SEA Needs to Know",
      note: "Natural language, emotional relevance, primary keyword embedded organically",
    },
    {
      element: "Blog Intro — Flesch-Kincaid Score 64",
      before:
        "Online scams are a prevalent issue in Southeast Asia, affecting millions annually across various digital channels.",
      after:
        "Every day, someone in Southeast Asia clicks a link that empties their savings. You don't have to be the next victim — this guide shows you exactly how to spot the trap before it springs.",
      note: "FK Score 64 · Narrative-driven · Problem/solution framing chosen over two other options",
    },
  ];

  // ──────────────────────────────────────────────────────────────────────────

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      {/* ── HERO ── */}
      <section className="relative overflow-hidden">
        <div
          className="absolute inset-0 opacity-[0.04]"
          style={{
            backgroundImage:
              "linear-gradient(#10b981 1px, transparent 1px), linear-gradient(90deg, #10b981 1px, transparent 1px)",
            backgroundSize: "40px 40px",
          }}
        />
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-emerald-500/10 blur-[120px] pointer-events-none" />

        <div className="relative max-w-5xl mx-auto px-6 pt-24 pb-20 text-center">
          <div className="inline-flex items-center gap-2 text-xs font-mono bg-slate-900 border border-slate-700 text-slate-400 px-4 py-1.5 rounded-full mb-6">
            <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
            {t("seo_geo.badge")}
          </div>

          <h1 className="text-5xl sm:text-6xl font-black tracking-tight mb-4">
            <span className="text-emerald-400">SEO</span>
            <span className="text-slate-500 mx-2">·</span>
            <span className="text-teal-300">GEO</span>
            <span className="text-slate-500 mx-2">·</span>
            <span className="text-white">GenAI</span>
          </h1>

          <p className="text-xl text-slate-300 max-w-2xl mx-auto mb-3">
            {t("seo_geo.page_title")}
          </p>
          <p className="text-sm text-slate-500 mb-10 max-w-2xl mx-auto">
            {t("seo_geo.description")}
          </p>

          <div className="grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-3xl mx-auto">
            <AnimStat
              value={2400000}
              suffix="+"
              label={t("seo_geo.stat_protected")}
            />
            <AnimStat value={14} suffix="" label={t("seo_geo.stat_articles")} />
            <AnimStat
              value={12}
              suffix=""
              label={t("seo_geo.stat_languages")}
            />
            <AnimStat value={8} suffix="" label={t("seo_geo.stat_countries")} />
          </div>
        </div>
      </section>

      {/* ── APPROACH ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>{t("seo_geo.approach_title")}</SectionLabel>
        <h2 className="text-3xl font-bold mb-4">
          {t("seo_geo.approach_subtitle")}
        </h2>
        <p className="text-slate-400 mb-10 max-w-2xl">
          {t("seo_geo.approach_desc")}
        </p>

        <div className="grid sm:grid-cols-3 gap-6">
          {[
            {
              icon: "🔑",
              titleKey: "keyword_title",
              descKey: "keyword_desc",
              color: "border-blue-800/50 bg-blue-950/10",
              titleColor: "text-blue-300",
              tags: [
                "Keyword Clustering",
                "Intent Mapping",
                "On-Page Structure",
              ],
            },
            {
              icon: "🌍",
              titleKey: "geo_title",
              descKey: "geo_desc",
              color: "border-emerald-800/50 bg-emerald-950/10",
              titleColor: "text-emerald-300",
              tags: ["Entity Mapping", "Answer Capsule", "AI-Citability"],
            },
            {
              icon: "🤖",
              titleKey: "genai_title",
              descKey: "genai_desc",
              color: "border-violet-800/50 bg-violet-950/10",
              titleColor: "text-violet-300",
              tags: ["AI-Assisted Drafting", "Human Editing", "FK 60–70"],
            },
          ].map((col) => (
            <div
              key={col.titleKey}
              className={`rounded-2xl border ${col.color} p-6 flex flex-col gap-4`}
            >
              <div className="flex items-center gap-3">
                <span className="text-3xl">{col.icon}</span>
                <h3 className={`font-bold text-lg ${col.titleColor}`}>
                  {t(`seo_geo.${col.titleKey}`)}
                </h3>
              </div>
              <p className="text-sm text-slate-400 leading-relaxed flex-1">
                {t(`seo_geo.${col.descKey}`)}
              </p>
              <div className="flex flex-wrap gap-2">
                {col.tags.map((tag) => (
                  <span
                    key={tag}
                    className="text-xs bg-slate-800 text-slate-400 px-2.5 py-1 rounded-full"
                  >
                    {tag}
                  </span>
                ))}
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* ── KEYWORD STRATEGY ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>Keyword Strategy</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">
            Keyword Cluster &amp; Search Intent Mapping
          </h2>

          <div className="grid md:grid-cols-2 gap-8">
            <div className="rounded-2xl border border-emerald-800/60 bg-emerald-950/30 p-6">
              <div className="text-xs font-mono text-emerald-500 uppercase tracking-widest mb-2">
                {t("seo_geo.primary_keyword")}
              </div>
              <div className="text-lg font-bold text-white mb-3 font-mono">
                "how to identify online scams southeast asia"
              </div>
              <div className="flex gap-3 flex-wrap mb-5">
                <span className="text-xs bg-blue-900/50 text-blue-300 px-3 py-1 rounded-full">
                  {t("seo_geo.informational_intent")}
                </span>
                <span className="text-xs bg-emerald-900/50 text-emerald-300 px-3 py-1 rounded-full">
                  Protective How-to
                </span>
              </div>
              <div className="space-y-2 text-sm text-slate-400 border-t border-emerald-900/50 pt-4">
                {[
                  "Title Tag 62 chars — keyword in first position",
                  "H1 human-first, not keyword-stuffed",
                  "URL slug: /insights/how-to-identify-online-scams",
                  "Meta description 150–160 chars, benefit-led",
                ].map((item, i) => (
                  <div key={i} className="flex gap-2">
                    <span className="text-emerald-600 shrink-0">▸</span>
                    <span>{item}</span>
                  </div>
                ))}
              </div>
            </div>

            <div className="rounded-2xl border border-slate-800 bg-slate-900/50 overflow-hidden">
              <table className="w-full">
                <thead>
                  <tr className="border-b border-slate-700 text-xs font-mono text-slate-500 uppercase tracking-wider">
                    <th className="text-left py-2.5 px-3">
                      {t("seo_geo.supporting_keywords")}
                    </th>
                    <th className="py-2.5 px-3">Vol</th>
                    <th className="py-2.5 px-3">Intent</th>
                  </tr>
                </thead>
                <tbody>
                  <KwRow
                    kw="online scam warning signs"
                    vol="High"
                    intent="Informational"
                  />
                  <KwRow
                    kw="phishing scam detection"
                    vol="High"
                    intent="Informational"
                  />
                  <KwRow
                    kw="investment fraud red flags"
                    vol="High"
                    intent="Investigative"
                  />
                  <KwRow
                    kw="digital safety tips youth"
                    vol="Medium"
                    intent="Informational"
                  />
                  <KwRow
                    kw="romance scam recovery"
                    vol="Medium"
                    intent="Informational"
                  />
                  <KwRow
                    kw="money mule recruitment signs"
                    vol="Medium"
                    intent="Investigative"
                  />
                  <KwRow
                    kw="radicalization warning signs"
                    vol="Medium"
                    intent="Investigative"
                  />
                  <KwRow
                    kw="safe internet practices SEA"
                    vol="Low"
                    intent="Informational"
                  />
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      {/* ── GEO THINKING ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>GEO Thinking</SectionLabel>
        <h2 className="text-3xl font-bold mb-3">
          {t("seo_geo.entity_label")} · {t("seo_geo.outcome_label")} ·{" "}
          {t("seo_geo.clarity_label")} Framework
        </h2>
        <p className="text-slate-400 mb-8 max-w-2xl">
          GEO (Generative Engine Optimisation) ensures SafePulse content can be
          cited by AI search engines — not just found by Google.
        </p>

        <div className="grid md:grid-cols-3 gap-6">
          <GeoCard
            icon="🏷️"
            title={t("seo_geo.entity_label").toUpperCase()}
            color="border-blue-700/40 bg-blue-950/20"
            titleColor="text-blue-300"
            items={GEO_ENTITIES}
          />
          <GeoCard
            icon="🎯"
            title={t("seo_geo.outcome_label").toUpperCase()}
            color="border-emerald-700/40 bg-emerald-950/20"
            titleColor="text-emerald-300"
            items={GEO_OUTCOMES}
          />
          <GeoCard
            icon="💡"
            title={t("seo_geo.clarity_label").toUpperCase()}
            color="border-amber-700/40 bg-amber-950/20"
            titleColor="text-amber-300"
            items={GEO_CLARITY}
          />
        </div>
      </section>

      {/* ── ON-PAGE STRUCTURE ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>{t("seo_geo.onpage_title")}</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">
            {t("seo_geo.onpage_subtitle")}
          </h2>

          <div className="grid md:grid-cols-2 gap-6">
            <div className="space-y-4">
              <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div className="text-xs font-mono text-slate-500 mb-2">
                  TITLE TAG · 62 chars
                </div>
                <div className="font-mono text-emerald-300 text-sm leading-relaxed">
                  How to Identify Online Scams in Southeast Asia | SafePulse
                </div>
              </div>
              <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div className="text-xs font-mono text-slate-500 mb-2">
                  H1 · Human-first
                </div>
                <div className="font-semibold text-white text-sm leading-relaxed">
                  Scams, Fraud &amp; Online Threats: What Every Person in SEA
                  Needs to Know
                </div>
              </div>
              <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div className="text-xs font-mono text-slate-500 mb-3">
                  H2 / H3 OUTLINE
                </div>
                <div className="space-y-2 text-sm">
                  {[
                    {
                      tag: "H2",
                      text: "What Is an Online Scam? (Answer Capsule)",
                    },
                    { tag: "H3", text: "Phishing & SMS Fraud", indent: true },
                    {
                      tag: "H3",
                      text: "Investment & Crypto Scams",
                      indent: true,
                    },
                    { tag: "H3", text: "Romance Scam Patterns", indent: true },
                    {
                      tag: "H2",
                      text: "Warning Signs — A Practical Checklist",
                    },
                    { tag: "H2", text: "Immediate Steps If Targeted" },
                    { tag: "H2", text: "Regional Resources & Reporting" },
                    { tag: "H2", text: "FAQ — Common Questions Answered" },
                  ].map((item, i) => (
                    <div
                      key={i}
                      className={`flex gap-2 items-center ${item.indent ? "ml-4" : ""}`}
                    >
                      <span className="text-xs font-mono bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">
                        {item.tag}
                      </span>
                      <span className="text-slate-300">{item.text}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
              <div className="text-xs font-mono text-slate-500 mb-4">
                INTERNAL LINK WEB
              </div>
              <div className="space-y-3">
                {[
                  {
                    path: "/check",
                    label: "Scam Checker Tool",
                    desc: "Live URL analysis",
                  },
                  {
                    path: "/report",
                    label: "Report an Incident",
                    desc: "Anonymous submission",
                  },
                  {
                    path: "/dashboard",
                    label: "Threat Map",
                    desc: "Real-time SEA dashboard",
                  },
                  {
                    path: "/evidence",
                    label: "Case Studies",
                    desc: "Documented scam evidence",
                  },
                  {
                    path: "/insights",
                    label: "Threat Library",
                    desc: "14 articles · 12 languages",
                  },
                  {
                    path: "/products",
                    label: "Youth Peace Hub",
                    desc: "Prevention programs",
                  },
                ].map((link, i) => (
                  <div
                    key={i}
                    className="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-800 transition-colors"
                  >
                    <code className="text-xs text-emerald-400 font-mono whitespace-nowrap">
                      {link.path}
                    </code>
                    <div className="min-w-0">
                      <div className="text-sm text-white font-medium truncate">
                        {link.label}
                      </div>
                      <div className="text-xs text-slate-500">{link.desc}</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── GENAI BEFORE / AFTER ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>GenAI Workflow</SectionLabel>
        <h2 className="text-3xl font-bold mb-3">
          {t("seo_geo.before")} / {t("seo_geo.after")} — AI-Assisted Content
          Refinement
        </h2>
        <p className="text-slate-400 mb-8 max-w-2xl">
          AI generates the first options. Final decisions — tone, framing,
          accuracy — always remain with the human.
        </p>

        <div className="space-y-5">
          {BEFORE_AFTER.map((item, i) => (
            <div
              key={i}
              className="rounded-2xl border border-slate-800 bg-slate-900/50 overflow-hidden"
            >
              <div className="px-5 py-3 bg-slate-800/50 border-b border-slate-800">
                <span className="text-xs font-mono text-slate-500 uppercase tracking-widest">
                  {item.element}
                </span>
              </div>
              <div className="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-800">
                <div className="p-5">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="w-2 h-2 rounded-full bg-red-500" />
                    <span className="text-xs text-red-400 font-mono">
                      {t("seo_geo.before").toUpperCase()}
                    </span>
                  </div>
                  <p className="text-sm text-slate-400 leading-relaxed">
                    {item.before}
                  </p>
                </div>
                <div className="p-5">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="w-2 h-2 rounded-full bg-emerald-500" />
                    <span className="text-xs text-emerald-400 font-mono">
                      {t("seo_geo.after").toUpperCase()}
                    </span>
                  </div>
                  <p className="text-sm text-white leading-relaxed">
                    {item.after}
                  </p>
                  <p className="text-xs text-slate-500 mt-2 italic">
                    {item.note}
                  </p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* ── ANSWER CAPSULE + SCHEMA ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>{t("seo_geo.schema_title")}</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">
            {t("seo_geo.answer_capsule_label")} · JSON-LD ·{" "}
            {t("seo_geo.trust_signals_label")}
          </h2>

          <div className="grid md:grid-cols-2 gap-6">
            <div className="rounded-2xl border border-teal-800/50 bg-teal-950/20 p-6">
              <div className="flex items-center gap-2 mb-4">
                <span className="text-xl">🤖</span>
                <div>
                  <div className="text-xs font-mono text-teal-500 uppercase tracking-widest">
                    {t("seo_geo.answer_capsule_label")}
                  </div>
                  <div className="text-sm text-white font-semibold">
                    53 words · GEO-ready · AI-extractable
                  </div>
                </div>
              </div>
              <div className="bg-slate-900 rounded-xl p-4 border border-slate-800">
                <p className="text-sm text-slate-300 leading-relaxed italic">
                  "Online scams in Southeast Asia follow five key patterns:
                  phishing via SMS or WhatsApp, investment fraud promising
                  guaranteed returns, romance scams requesting money transfers,
                  money mule recruitment through job ads, and radicalisation
                  through extremist content. Recognising these patterns — and
                  reporting them — is the first step to staying safe."
                </p>
              </div>
              <p className="text-xs text-slate-500 mt-3">
                Placed above fold — designed to be cited by Google AI Overview,
                Perplexity, and ChatGPT Search.
              </p>
            </div>

            <div className="rounded-2xl border border-slate-700 bg-slate-900/50 p-6">
              <div className="text-xs font-mono text-slate-500 uppercase tracking-widest mb-4">
                {t("seo_geo.trust_signals_label")}
              </div>
              <div className="space-y-3">
                {[
                  {
                    icon: "✅",
                    label: "Author",
                    value: "SafePulse Team (named person entity)",
                  },
                  {
                    icon: "🏢",
                    label: "Publisher",
                    value: "Organization schema with logo URL",
                  },
                  {
                    icon: "📅",
                    label: "Dates",
                    value: "datePublished + dateModified",
                  },
                  { icon: "🌐", label: "URL", value: "Canonical URL per page" },
                  {
                    icon: "🔤",
                    label: "Language",
                    value: "inLanguage: 'en' (per article)",
                  },
                  {
                    icon: "📋",
                    label: "About",
                    value: "Named topic entity for GEO clarity",
                  },
                  {
                    icon: "❓",
                    label: "FAQPage",
                    value: "5 Q&A nested in @graph",
                  },
                  {
                    icon: "🔍",
                    label: t("seo_geo.validated"),
                    value: "Google Rich Results Test: Pass ✅",
                  },
                ].map((sig, i) => (
                  <div key={i} className="flex gap-3 items-start text-sm">
                    <span className="text-lg leading-none">{sig.icon}</span>
                    <div>
                      <span className="text-slate-500 font-mono text-xs">
                        {sig.label}:
                      </span>{" "}
                      <span className="text-slate-300">{sig.value}</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            <div className="md:col-span-2 rounded-2xl border border-slate-700 bg-slate-950 overflow-hidden">
              <div className="px-5 py-3 bg-slate-800/60 border-b border-slate-700 flex items-center gap-2">
                <span className="w-3 h-3 rounded-full bg-red-500/80" />
                <span className="w-3 h-3 rounded-full bg-amber-500/80" />
                <span className="w-3 h-3 rounded-full bg-emerald-500/80" />
                <span className="text-xs font-mono text-slate-400 ml-2">
                  JSON-LD Schema — Article + FAQPage (applied on article pages)
                </span>
              </div>
              <pre className="p-5 text-xs text-emerald-300 font-mono overflow-x-auto leading-relaxed whitespace-pre">{`<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "headline": "How to Identify Online Scams in SEA",
      "author": { "@type": "Person", "name": "SafePulse Team" },
      "publisher": {
        "@type": "Organization",
        "name": "SafePulse",
        "url": "https://safepulse.duckdns.org"
      },
      "datePublished": "2024-01-01",
      "inLanguage": "en",
      "about": "online scam detection"
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Most common scam in SEA?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Phishing via SMS/WhatsApp is the most prevalent..."
          }
        }
      ]
    }
  ]
}
</script>`}</pre>
            </div>
          </div>
        </div>
      </section>

      {/* ── ETHICAL AI ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>{t("seo_geo.responsible_ai_title")}</SectionLabel>
        <h2 className="text-3xl font-bold mb-8">
          {t("seo_geo.responsible_ai_subtitle")}
        </h2>

        <div className="grid sm:grid-cols-3 gap-6">
          <EthicCard
            icon="✅"
            title={t("seo_geo.ai_does")}
            color="border-blue-700/40"
            titleColor="text-blue-300"
            items={AI_DOES}
          />
          <EthicCard
            icon="🧑"
            title={t("seo_geo.human_decides")}
            color="border-emerald-700/40"
            titleColor="text-emerald-300"
            items={HUMAN_DECIDES}
          />
          <EthicCard
            icon="🚫"
            title={t("seo_geo.ai_never")}
            color="border-red-800/40"
            titleColor="text-red-300"
            items={AI_NEVER}
          />
        </div>

        <div className="mt-6 text-center text-sm text-slate-500 italic">
          Principle: AI accelerates ideation and drafting — humans hold
          strategy, editorial quality, accuracy, and cultural sensitivity.
        </div>
      </section>

      {/* ── SKILLS ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>{t("seo_geo.skills_title")}</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">
            {t("seo_geo.skills_subtitle")}
          </h2>

          <div className="grid sm:grid-cols-2 gap-4 mb-10">
            {SKILLS.map((skill, i) => (
              <div
                key={i}
                className="flex gap-3 items-start p-4 rounded-xl border border-slate-800 bg-slate-900/50 hover:border-emerald-800/50 transition-colors"
              >
                <span className="text-emerald-500 mt-0.5 shrink-0">✓</span>
                <span className="text-sm text-slate-300">{skill}</span>
              </div>
            ))}
          </div>

          <div className="rounded-2xl border border-slate-700 bg-slate-900/50 p-6">
            <div className="text-xs font-mono text-slate-500 uppercase tracking-widest mb-4">
              Tech Stack
            </div>
            <div className="flex flex-wrap gap-2">
              {[
                "Laravel 10",
                "PHP 8.2",
                "React 18",
                "TypeScript",
                "TailwindCSS",
                "WCAG 2.1 AA",
                "SQLite / MySQL",
                "GitHub Codespaces",
                "AWS EC2",
                "AWS Amplify",
                "DuckDNS HTTPS",
                "react-i18next",
                "JSON-LD Schema",
                "Google Rich Results",
              ].map((tech) => (
                <span
                  key={tech}
                  className="text-xs bg-slate-800 text-slate-400 px-3 py-1.5 rounded-full border border-slate-700"
                >
                  {tech}
                </span>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── LIVE LINKS ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>{t("seo_geo.live_title")}</SectionLabel>
        <h2 className="text-3xl font-bold mb-8">
          {t("seo_geo.live_subtitle")}
        </h2>

        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {[
            {
              icon: "🌐",
              label: "Live Platform",
              desc: "SafePulse Amplify App",
              url: "https://main.d1f2msb859ksi1.amplifyapp.com",
              external: true,
            },
            {
              icon: "📰",
              label: "SEO Article",
              desc: "How to Identify Online Scams",
              url: "/insights/how-to-identify-online-scams",
              external: false,
            },
            {
              icon: "🗺️",
              label: "Threat Dashboard",
              desc: "Real-time SEA Map",
              url: "/dashboard",
              external: false,
            },
            {
              icon: "🛡️",
              label: "Scam Checker",
              desc: "Live URL Analysis Tool",
              url: "/check",
              external: false,
            },
            {
              icon: "💻",
              label: "GitHub Repo",
              desc: "hanif-dev/safepulse",
              url: "https://github.com/hanif-dev/safepulse",
              external: true,
            },
            {
              icon: "⚙️",
              label: "Backend API",
              desc: "EC2 + DuckDNS",
              url: "https://safepulse.duckdns.org/api/ping",
              external: true,
            },
          ].map((ref, i) => (
            <a
              key={i}
              href={ref.url}
              target={ref.external ? "_blank" : undefined}
              rel={ref.external ? "noopener noreferrer" : undefined}
              className="flex gap-4 items-center p-4 rounded-xl border border-slate-800 hover:border-emerald-700/50 hover:bg-slate-800/50 transition-all group"
            >
              <span className="text-2xl">{ref.icon}</span>
              <div>
                <div className="font-semibold text-white text-sm group-hover:text-emerald-300 transition-colors">
                  {ref.label} ↗
                </div>
                <div className="text-xs text-slate-500">{ref.desc}</div>
              </div>
            </a>
          ))}
        </div>
      </section>

      {/* ── FOOTER ── */}
      <section className="border-t border-slate-800 py-10 text-center">
        <p className="text-slate-600 text-xs font-mono">
          SafePulse · Anti-Scam &amp; Digital Resilience Platform · Southeast
          Asia
        </p>
      </section>
    </div>
  );
}
