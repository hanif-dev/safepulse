// frontend/src/pages/SeoGeo.tsx
// SEO · GEO · GenAI — SafePulse Portfolio Page
// Route: /seo-geo

import { useEffect, useRef, useState } from "react";

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
      { threshold: 0.3 }
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, [target, duration]);

  return { value, ref };
}

function AnimStat({ value, suffix, label }: { value: number; suffix: string; label: string }) {
  const { value: v, ref } = useCounter(value);
  return (
    <div ref={ref} className="text-center">
      <div className="text-4xl font-black text-emerald-400 tabular-nums">
        {v.toLocaleString()}{suffix}
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

function KwRow({ kw, vol, intent }: { kw: string; vol: string; intent: string }) {
  const intentColor =
    intent === "Informational"
      ? "bg-blue-900/60 text-blue-300"
      : "bg-amber-900/60 text-amber-300";
  return (
    <tr className="border-b border-slate-800 hover:bg-slate-800/40 transition-colors">
      <td className="py-2.5 px-3 text-slate-200 text-sm font-mono">{kw}</td>
      <td className="py-2.5 px-3 text-center">
        <span className={`text-xs px-2 py-0.5 rounded-full ${
          vol === "High" ? "bg-emerald-900/60 text-emerald-300" :
          vol === "Medium" ? "bg-yellow-900/60 text-yellow-300" :
          "bg-slate-700 text-slate-400"
        }`}>{vol}</span>
      </td>
      <td className="py-2.5 px-3 text-center">
        <span className={`text-xs px-2 py-0.5 rounded-full ${intentColor}`}>{intent}</span>
      </td>
    </tr>
  );
}

function GeoCard({ icon, title, items, color, titleColor }: {
  icon: string; title: string; items: string[]; color: string; titleColor: string;
}) {
  return (
    <div className={`rounded-2xl border ${color} p-6`}>
      <div className="flex items-center gap-2 mb-4">
        <span className="text-2xl">{icon}</span>
        <h3 className={`font-black text-lg tracking-widest ${titleColor}`}>{title}</h3>
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

function EthicCard({ icon, title, items, color, titleColor }: {
  icon: string; title: string; items: string[]; color: string; titleColor: string;
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

export default function SeoGeo() {
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
            Digital Safety Platform · Anti-Scam · Southeast Asia
          </div>

          <h1 className="text-5xl sm:text-6xl font-black tracking-tight mb-4">
            <span className="text-emerald-400">SEO</span>
            <span className="text-slate-500 mx-2">·</span>
            <span className="text-teal-300">GEO</span>
            <span className="text-slate-500 mx-2">·</span>
            <span className="text-white">GenAI</span>
          </h1>
          <p className="text-xl text-slate-300 max-w-2xl mx-auto mb-3">
            Content Strategy untuk SafePulse
          </p>
          <p className="text-sm text-slate-500 mb-10 max-w-2xl mx-auto">
            Bagaimana keyword strategy, GEO entity-outcome thinking, dan AI-assisted workflow
            diterapkan secara nyata pada platform anti-scam untuk komunitas rentan di Asia Tenggara.
          </p>

          <div className="grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-3xl mx-auto">
            <AnimStat value={2400000} suffix="+" label="People Protected" />
            <AnimStat value={14} suffix="" label="Articles Published" />
            <AnimStat value={12} suffix="" label="Languages" />
            <AnimStat value={8} suffix="" label="Countries Covered" />
          </div>
        </div>
      </section>

      {/* ── APPROACH ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>Pendekatan Strategi</SectionLabel>
        <h2 className="text-3xl font-bold mb-4">Tiga Lapisan Strategi Konten</h2>
        <p className="text-slate-400 mb-10 max-w-2xl">
          SafePulse membutuhkan konten yang bisa dibaca manusia, ditemukan search engine,
          dan dikutip oleh AI language model. Ketiga lapisan ini dibangun secara bersamaan.
        </p>

        <div className="grid sm:grid-cols-3 gap-6">
          {[
            {
              icon: "🔑",
              title: "Keyword Strategy",
              color: "border-blue-800/50 bg-blue-950/10",
              titleColor: "text-blue-300",
              desc: "Pemetaan keyword cluster berbasis search intent — dari keyword primer hingga long-tail queries yang relevan untuk audiens SEA.",
              tags: ["Keyword Clustering", "Intent Mapping", "On-Page Structure"],
            },
            {
              icon: "🌍",
              title: "GEO Thinking",
              color: "border-emerald-800/50 bg-emerald-950/10",
              titleColor: "text-emerald-300",
              desc: "Framework Entity–Outcome–Clarity agar konten dapat dikutip oleh AI search engine seperti Perplexity, ChatGPT, dan Google AI Overview.",
              tags: ["Entity Mapping", "Answer Capsule", "AI-Citability"],
            },
            {
              icon: "🤖",
              title: "GenAI Workflow",
              color: "border-violet-800/50 bg-violet-950/10",
              titleColor: "text-violet-300",
              desc: "AI mempercepat ideasi dan drafting — manusia memegang keputusan editorial, akurasi fakta, dan sensitivitas budaya.",
              tags: ["AI-Assisted Drafting", "Human Editing", "FK 60–70"],
            },
          ].map((col, i) => (
            <div key={i} className={`rounded-2xl border ${col.color} p-6 flex flex-col gap-4`}>
              <div className="flex items-center gap-3">
                <span className="text-3xl">{col.icon}</span>
                <h3 className={`font-bold text-lg ${col.titleColor}`}>{col.title}</h3>
              </div>
              <p className="text-sm text-slate-400 leading-relaxed flex-1">{col.desc}</p>
              <div className="flex flex-wrap gap-2">
                {col.tags.map((t) => (
                  <span key={t} className="text-xs bg-slate-800 text-slate-400 px-2.5 py-1 rounded-full">
                    {t}
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
          <h2 className="text-3xl font-bold mb-8">Keyword Cluster &amp; Search Intent Mapping</h2>

          <div className="grid md:grid-cols-2 gap-8">
            <div className="rounded-2xl border border-emerald-800/60 bg-emerald-950/30 p-6">
              <div className="text-xs font-mono text-emerald-500 uppercase tracking-widest mb-2">
                Primary Keyword
              </div>
              <div className="text-lg font-bold text-white mb-3 font-mono">
                "how to identify online scams southeast asia"
              </div>
              <div className="flex gap-3 flex-wrap mb-5">
                <span className="text-xs bg-blue-900/50 text-blue-300 px-3 py-1 rounded-full">
                  Informational Intent
                </span>
                <span className="text-xs bg-emerald-900/50 text-emerald-300 px-3 py-1 rounded-full">
                  Protective How-to
                </span>
              </div>
              <div className="space-y-2 text-sm text-slate-400 border-t border-emerald-900/50 pt-4">
                {[
                  "Title Tag 62 chars — keyword di posisi pertama",
                  "H1 human-first, bukan keyword-stuffed",
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
                    <th className="text-left py-2.5 px-3">Supporting Keywords</th>
                    <th className="py-2.5 px-3">Vol</th>
                    <th className="py-2.5 px-3">Intent</th>
                  </tr>
                </thead>
                <tbody>
                  <KwRow kw="online scam warning signs" vol="High" intent="Informational" />
                  <KwRow kw="phishing scam detection" vol="High" intent="Informational" />
                  <KwRow kw="investment fraud red flags" vol="High" intent="Investigative" />
                  <KwRow kw="digital safety tips youth" vol="Medium" intent="Informational" />
                  <KwRow kw="romance scam recovery" vol="Medium" intent="Informational" />
                  <KwRow kw="money mule recruitment signs" vol="Medium" intent="Investigative" />
                  <KwRow kw="radicalization warning signs" vol="Medium" intent="Investigative" />
                  <KwRow kw="safe internet practices SEA" vol="Low" intent="Informational" />
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      {/* ── GEO THINKING ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>GEO Thinking</SectionLabel>
        <h2 className="text-3xl font-bold mb-3">Entity · Outcome · Clarity Framework</h2>
        <p className="text-slate-400 mb-8 max-w-2xl">
          GEO (Generative Engine Optimisation) memastikan konten SafePulse dapat dikutip oleh
          AI search engines — bukan hanya ditemukan oleh Google.
        </p>

        <div className="grid md:grid-cols-3 gap-6">
          <GeoCard
            icon="🏷️"
            title="ENTITY"
            color="border-blue-700/40 bg-blue-950/20"
            titleColor="text-blue-300"
            items={[
              "SafePulse Platform (digital safety tool)",
              "Online Scams: phishing, investment, romance",
              "Radikalisasi (online extremism)",
              "Money Mule Networks",
              "Southeast Asia (geographic context)",
              "Youth & Vulnerable Communities",
            ]}
          />
          <GeoCard
            icon="🎯"
            title="OUTCOME"
            color="border-emerald-700/40 bg-emerald-950/20"
            titleColor="text-emerald-300"
            items={[
              "User mengenali 5+ jenis scam berdasarkan pola",
              "User tahu langkah darurat jika jadi korban",
              "Halaman ranking untuk protective queries",
              "Konten dikutip oleh AI search engines",
              "Komunitas melaporkan insiden secara anonim",
              "NGO memanfaatkan data SafePulse",
            ]}
          />
          <GeoCard
            icon="💡"
            title="CLARITY"
            color="border-amber-700/40 bg-amber-950/20"
            titleColor="text-amber-300"
            items={[
              "Satu halaman = satu dominant intent",
              "URL: /insights/how-to-identify-online-scams",
              "H1 menjawab keyword primer secara langsung",
              "Answer Capsule di atas fold untuk AI extraction",
              "FAQ schema mencakup long-tail questions",
              "Konten diverifikasi oleh peneliti kesehatan publik",
            ]}
          />
        </div>
      </section>

      {/* ── ON-PAGE STRUCTURE ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>On-Page Structure</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">Struktur Halaman &amp; Internal Link Web</h2>

          <div className="grid md:grid-cols-2 gap-6">
            <div className="space-y-4">
              <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div className="text-xs font-mono text-slate-500 mb-2">TITLE TAG · 62 chars</div>
                <div className="font-mono text-emerald-300 text-sm leading-relaxed">
                  How to Identify Online Scams in Southeast Asia | SafePulse
                </div>
              </div>
              <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div className="text-xs font-mono text-slate-500 mb-2">H1 · Human-first</div>
                <div className="font-semibold text-white text-sm leading-relaxed">
                  Scams, Fraud &amp; Online Threats: What Every Person in SEA Needs to Know
                </div>
              </div>
              <div className="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div className="text-xs font-mono text-slate-500 mb-3">H2 / H3 OUTLINE</div>
                <div className="space-y-2 text-sm">
                  {[
                    { tag: "H2", text: "What Is an Online Scam? (Answer Capsule)" },
                    { tag: "H3", text: "Phishing & SMS Fraud", indent: true },
                    { tag: "H3", text: "Investment & Crypto Scams", indent: true },
                    { tag: "H3", text: "Romance Scam Patterns", indent: true },
                    { tag: "H2", text: "Warning Signs — A Practical Checklist" },
                    { tag: "H2", text: "Immediate Steps If Targeted" },
                    { tag: "H2", text: "Regional Resources & Reporting" },
                    { tag: "H2", text: "FAQ — Common Questions Answered" },
                  ].map((item, i) => (
                    <div key={i} className={`flex gap-2 items-center ${item.indent ? "ml-4" : ""}`}>
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
              <div className="text-xs font-mono text-slate-500 mb-4">INTERNAL LINK WEB</div>
              <div className="space-y-3">
                {[
                  { path: "/check", label: "Scam Checker Tool", desc: "Live URL analysis" },
                  { path: "/report", label: "Report an Incident", desc: "Anonymous submission" },
                  { path: "/dashboard", label: "Threat Map", desc: "Real-time SEA dashboard" },
                  { path: "/evidence", label: "Case Studies", desc: "Documented scam evidence" },
                  { path: "/insights", label: "Threat Library", desc: "14 articles · 12 languages" },
                  { path: "/products", label: "Youth Peace Hub", desc: "Prevention programs" },
                ].map((link, i) => (
                  <div
                    key={i}
                    className="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-800 transition-colors"
                  >
                    <code className="text-xs text-emerald-400 font-mono whitespace-nowrap">
                      {link.path}
                    </code>
                    <div className="min-w-0">
                      <div className="text-sm text-white font-medium truncate">{link.label}</div>
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
        <h2 className="text-3xl font-bold mb-3">Before / After — AI-Assisted Content Refinement</h2>
        <p className="text-slate-400 mb-8 max-w-2xl">
          AI digunakan untuk menghasilkan opsi awal. Keputusan akhir — tone, framing, akurasi —
          selalu ada di tangan manusia.
        </p>

        <div className="space-y-5">
          {[
            {
              element: "Title Tag",
              before: "Online Scams Southeast Asia — SafePulse",
              after: "How to Identify Online Scams in Southeast Asia | SafePulse",
              note: "Ditambahkan action verb 'How to' + dipertahankan ≤65 chars",
            },
            {
              element: "Meta Description",
              before: "SafePulse helps you avoid online scams. Learn about phishing and more.",
              after:
                "Protect yourself and your family from phishing, investment fraud, and romance scams across SEA. Spot the 5 warning signs now — free, anonymous tool included.",
              note: "150–160 chars, benefit-led, CTA disertakan",
            },
            {
              element: "H1",
              before: "How to Identify Online Scams Southeast Asia",
              after: "Scams, Fraud & Online Threats: What Every Person in SEA Needs to Know",
              note: "Bahasa natural, relevansi emosional, keyword primer tertanam organik",
            },
            {
              element: "Blog Intro — Flesch-Kincaid Score 64",
              before:
                "Online scams are a prevalent issue in Southeast Asia, affecting millions annually across various digital channels.",
              after:
                "Every day, someone in Southeast Asia clicks a link that empties their savings. You don't have to be the next victim — this guide shows you exactly how to spot the trap before it springs.",
              note: "FK Score 64 · Narrative-driven · Problem/solution framing dipilih vs. dua opsi lain",
            },
          ].map((item, i) => (
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
                    <span className="text-xs text-red-400 font-mono">BEFORE</span>
                  </div>
                  <p className="text-sm text-slate-400 leading-relaxed">{item.before}</p>
                </div>
                <div className="p-5">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="w-2 h-2 rounded-full bg-emerald-500" />
                    <span className="text-xs text-emerald-400 font-mono">AFTER</span>
                  </div>
                  <p className="text-sm text-white leading-relaxed">{item.after}</p>
                  <p className="text-xs text-slate-500 mt-2 italic">{item.note}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* ── ANSWER CAPSULE + SCHEMA ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>Schema &amp; Structured Data</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">Answer Capsule · JSON-LD · Trust Signals</h2>

          <div className="grid md:grid-cols-2 gap-6">
            <div className="rounded-2xl border border-teal-800/50 bg-teal-950/20 p-6">
              <div className="flex items-center gap-2 mb-4">
                <span className="text-xl">🤖</span>
                <div>
                  <div className="text-xs font-mono text-teal-500 uppercase tracking-widest">
                    Answer Capsule
                  </div>
                  <div className="text-sm text-white font-semibold">53 kata · GEO-ready · AI-extractable</div>
                </div>
              </div>
              <div className="bg-slate-900 rounded-xl p-4 border border-slate-800">
                <p className="text-sm text-slate-300 leading-relaxed italic">
                  "Online scams in Southeast Asia follow five key patterns: phishing via SMS or WhatsApp,
                  investment fraud promising guaranteed returns, romance scams requesting money transfers,
                  money mule recruitment through job ads, and radicalisation through extremist content.
                  Recognising these patterns — and reporting them — is the first step to staying safe."
                </p>
              </div>
              <p className="text-xs text-slate-500 mt-3">
                Ditempatkan di atas fold — dirancang agar dikutip oleh Google AI Overview,
                Perplexity, dan ChatGPT Search.
              </p>
            </div>

            <div className="rounded-2xl border border-slate-700 bg-slate-900/50 p-6">
              <div className="text-xs font-mono text-slate-500 uppercase tracking-widest mb-4">
                Trust &amp; Identity Signals
              </div>
              <div className="space-y-3">
                {[
                  { icon: "✅", label: "Author", value: "SafePulse Team (named person entity)" },
                  { icon: "🏢", label: "Publisher", value: "Organization schema dengan logo URL" },
                  { icon: "📅", label: "Dates", value: "datePublished + dateModified" },
                  { icon: "🌐", label: "URL", value: "Canonical URL sesuai halaman" },
                  { icon: "🔤", label: "Language", value: "inLanguage: 'en' (per artikel)" },
                  { icon: "📋", label: "About", value: "Named topic entity untuk GEO clarity" },
                  { icon: "❓", label: "FAQPage", value: "5 Q&A tersarang dalam @graph" },
                  { icon: "🔍", label: "Validated", value: "Google Rich Results Test: Pass ✅" },
                ].map((sig, i) => (
                  <div key={i} className="flex gap-3 items-start text-sm">
                    <span className="text-lg leading-none">{sig.icon}</span>
                    <div>
                      <span className="text-slate-500 font-mono text-xs">{sig.label}:</span>{" "}
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
                  JSON-LD Schema — Article + FAQPage (diterapkan di halaman artikel)
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
        <SectionLabel>Penggunaan AI yang Bertanggung Jawab</SectionLabel>
        <h2 className="text-3xl font-bold mb-8">AI Digunakan — Keputusan Tetap di Tangan Manusia</h2>

        <div className="grid sm:grid-cols-3 gap-6">
          <EthicCard
            icon="✅"
            title="AI Mengerjakan"
            color="border-blue-700/40"
            titleColor="text-blue-300"
            items={[
              "Menghasilkan 3 opsi judul per elemen",
              "Membuat draft blog awal (~900 kata)",
              "Menyarankan 5 edit pengetatan untuk direview",
              "Mengusulkan struktur JSON-LD schema",
              "Menghasilkan terjemahan 12 bahasa",
              "Membuat ide FAQ dari keyword research",
            ]}
          />
          <EthicCard
            icon="🧑"
            title="Manusia Memutuskan"
            color="border-emerald-700/40"
            titleColor="text-emerald-300"
            items={[
              "Memilih judul terbaik dari 3 opsi AI",
              "Mengedit intro — memilih gaya problem/solution",
              "Memverifikasi klaim faktual ke sumber resmi",
              "Menghapus frasa tidak sensitif budaya di AR/JV",
              "Validasi manual JSON-LD di Google Rich Results",
              "Menetapkan keyword density — tidak dipaksakan",
            ]}
          />
          <EthicCard
            icon="🚫"
            title="AI Tidak Boleh"
            color="border-red-800/40"
            titleColor="text-red-300"
            items={[
              "Publish tanpa review manusia",
              "Membuat statistik atau sitasi palsu",
              "Menyalin konten kompetitor",
              "Mengabaikan brand voice SafePulse",
              "Memilih prioritas bahasa secara sepihak",
              "Menentukan framework GEO entity",
            ]}
          />
        </div>

        <div className="mt-6 text-center text-sm text-slate-500 italic">
          Prinsip: AI mempercepat ideasi dan drafting — manusia memegang strategi,
          kualitas editorial, akurasi, dan sensitivitas budaya.
        </div>
      </section>

      {/* ── SKILLS ── */}
      <section className="bg-slate-900/40 py-16">
        <div className="max-w-5xl mx-auto px-6">
          <SectionLabel>Skills yang Diterapkan</SectionLabel>
          <h2 className="text-3xl font-bold mb-8">Kemampuan Teknis &amp; Strategis</h2>

          <div className="grid sm:grid-cols-2 gap-4 mb-10">
            {[
              "Keyword cluster strategy dengan intent classification",
              "GEO entity + outcome framework pada produk nyata",
              "AI-assisted on-page refinement dengan kontrol editorial penuh",
              "Kalibrasi readability FK 60–70 di 12 bahasa",
              "JSON-LD schema (Article + FAQPage) dengan trust signals",
              "Full-stack deployment: Codespaces → EC2 → Amplify + HTTPS",
              "Data insiden anonim → public health dashboard",
              "WCAG 2.1 AA accessibility + dukungan RTL (Arabic)",
            ].map((skill, i) => (
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
            <div className="text-xs font-mono text-slate-500 uppercase tracking-widest mb-4">Tech Stack</div>
            <div className="flex flex-wrap gap-2">
              {[
                "Laravel 10", "PHP 8.2", "React 18", "TypeScript",
                "TailwindCSS", "WCAG 2.1 AA", "SQLite / MySQL",
                "GitHub Codespaces", "AWS EC2", "AWS Amplify", "DuckDNS HTTPS",
                "react-i18next", "JSON-LD Schema", "Google Rich Results",
              ].map((t) => (
                <span key={t} className="text-xs bg-slate-800 text-slate-400 px-3 py-1.5 rounded-full border border-slate-700">
                  {t}
                </span>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── LINKS ── */}
      <section className="max-w-5xl mx-auto px-6 py-16">
        <SectionLabel>Lihat Langsung</SectionLabel>
        <h2 className="text-3xl font-bold mb-8">Platform &amp; Source Code</h2>

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
              label: "Artikel SEO",
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
          SafePulse · Anti-Scam &amp; Digital Resilience Platform · Southeast Asia
        </p>
      </section>
    </div>
  );
}
