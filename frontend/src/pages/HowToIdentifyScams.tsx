// frontend/src/pages/HowToIdentifyScams.tsx
// SEO-optimised article page — URL: /insights/how-to-identify-online-scams
// Features: JSON-LD injection, Answer Capsule, FAQPage schema, internal links
// Add route: <Route path="/insights/how-to-identify-online-scams" element={<HowToIdentifyScams />} />

import { useEffect } from "react";
import { Link } from "react-router-dom";

/* ── JSON-LD schema injected into <head> ── */
const SCHEMA = {
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      headline: "How to Identify Online Scams in Southeast Asia",
      description:
        "Online scams in SEA follow five key patterns. Learn to identify phishing, investment fraud, romance scams, money mule recruitment, and radicalisation — and what to do if targeted.",
      author: { "@type": "Person", name: "SafePulse Team" },
      publisher: {
        "@type": "Organization",
        name: "SafePulse",
        url: "https://safepulse.duckdns.org",
        logo: {
          "@type": "ImageObject",
          url: "https://main.d1f2msb859ksi1.amplifyapp.com/logo.png",
        },
      },
      datePublished: "2024-01-01",
      dateModified: "2025-01-01",
      inLanguage: "en",
      about: "online scam detection",
      url: "https://main.d1f2msb859ksi1.amplifyapp.com/insights/how-to-identify-online-scams",
    },
    {
      "@type": "FAQPage",
      mainEntity: [
        {
          "@type": "Question",
          name: "What is the most common online scam in Southeast Asia?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Phishing via SMS or WhatsApp is the most prevalent scam in SEA, followed by investment fraud promising guaranteed returns and romance scams requesting money transfers.",
          },
        },
        {
          "@type": "Question",
          name: "How do I report an online scam in SEA?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "You can report scams anonymously through SafePulse at /report, or contact your national cybercrime authority. In Indonesia, report to Bareskrim Polri. In Malaysia, use the CCID portal.",
          },
        },
        {
          "@type": "Question",
          name: "What are the warning signs of an investment scam?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Guaranteed high returns with no risk, pressure to invest quickly, unregistered platforms, requests for crypto payments, and inability to withdraw funds are major red flags.",
          },
        },
        {
          "@type": "Question",
          name: "What is a money mule?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "A money mule is someone recruited — often unknowingly — to transfer illegally obtained money through their bank account. Recruiters pose as employers offering easy remote work.",
          },
        },
        {
          "@type": "Question",
          name: "Can young people be targeted by online scams?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Yes. Youth aged 15–30 are frequently targeted through social media, gaming platforms, and job boards. Romance scams and fake investment apps are particularly common vectors.",
          },
        },
      ],
    },
  ],
};

/* ── Warning sign item ── */
function WarnSign({ icon, title, desc }: { icon: string; title: string; desc: string }) {
  return (
    <div className="flex gap-4 items-start p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-amber-700/50 transition-colors">
      <span className="text-2xl shrink-0">{icon}</span>
      <div>
        <h3 className="font-semibold text-white mb-1">{title}</h3>
        <p className="text-sm text-slate-400 leading-relaxed">{desc}</p>
      </div>
    </div>
  );
}

/* ── Scam type card ── */
function ScamCard({
  icon, type, signs, color,
}: {
  icon: string; type: string; signs: string[]; color: string;
}) {
  return (
    <div className={`rounded-2xl border ${color} p-5`}>
      <div className="flex items-center gap-2 mb-3">
        <span className="text-2xl">{icon}</span>
        <h3 className="font-bold text-white">{type}</h3>
      </div>
      <ul className="space-y-1.5">
        {signs.map((s, i) => (
          <li key={i} className="flex gap-2 text-sm text-slate-300">
            <span className="text-amber-500 mt-0.5 shrink-0">▸</span>
            {s}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default function HowToIdentifyScams() {
  /* inject JSON-LD into <head> */
  useEffect(() => {
    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.textContent = JSON.stringify(SCHEMA);
    script.id = "seo-geo-schema";
    document.head.appendChild(script);

    /* set SEO meta tags */
    document.title = "How to Identify Online Scams in Southeast Asia | SafePulse";
    const setMeta = (name: string, content: string) => {
      let el = document.querySelector<HTMLMetaElement>(`meta[name="${name}"]`);
      if (!el) {
        el = document.createElement("meta");
        el.name = name;
        document.head.appendChild(el);
      }
      el.content = content;
    };
    setMeta(
      "description",
      "Protect yourself from phishing, investment fraud, and romance scams across SEA. Spot the 5 warning signs now — free, anonymous scam-check tool included."
    );
    setMeta("keywords", "online scam warning signs, phishing scam detection, investment fraud red flags, digital safety tips, romance scam recovery, money mule signs, SEA online scams");

    return () => {
      document.getElementById("seo-geo-schema")?.remove();
    };
  }, []);

  return (
    <div className="min-h-screen bg-slate-950 text-white">
      {/* ── HERO ── */}
      <article itemScope itemType="https://schema.org/Article">
        <header className="max-w-3xl mx-auto px-6 pt-20 pb-10">
          {/* breadcrumb */}
          <nav className="flex items-center gap-1.5 text-xs text-slate-500 mb-6 font-mono">
            <Link to="/" className="hover:text-emerald-400 transition-colors">Home</Link>
            <span>/</span>
            <Link to="/insights" className="hover:text-emerald-400 transition-colors">Insights</Link>
            <span>/</span>
            <span className="text-slate-400">How to Identify Online Scams</span>
          </nav>

          <h1
            itemProp="headline"
            className="text-4xl sm:text-5xl font-black leading-tight mb-6"
          >
            Scams, Fraud &amp; Online Threats:{" "}
            <span className="text-amber-400">What Every Person in SEA Needs to Know</span>
          </h1>

          <div className="flex flex-wrap items-center gap-4 text-sm text-slate-400 mb-8">
            <span itemProp="author">By <strong className="text-white">SafePulse Team</strong></span>
            <span>·</span>
            <time itemProp="datePublished" dateTime="2024-01-01">Jan 1, 2024</time>
            <span>·</span>
            <span>8 min read</span>
            <span>·</span>
            <span className="text-emerald-400">12 languages available</span>
          </div>

          {/* ── ANSWER CAPSULE (GEO) ── */}
          <div className="rounded-2xl border border-teal-700/50 bg-teal-950/30 p-6 mb-6">
            <div className="flex items-center gap-2 mb-3">
              <span className="text-teal-400 text-lg">🤖</span>
              <span className="text-xs font-mono text-teal-500 uppercase tracking-widest">Answer Capsule · GEO-Ready</span>
            </div>
            <p className="text-slate-200 leading-relaxed" itemProp="description">
              Online scams in Southeast Asia follow five key patterns: phishing via SMS or WhatsApp,
              investment fraud promising guaranteed returns, romance scams requesting money transfers,
              money mule recruitment through job ads, and radicalisation through extremist content.
              Recognising these patterns — and reporting them — is the first step to staying safe.
            </p>
          </div>

          {/* quick-action CTA */}
          <div className="flex flex-wrap gap-3">
            <Link
              to="/check"
              className="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors"
            >
              🛡️ Check a suspicious link
            </Link>
            <Link
              to="/report"
              className="inline-flex items-center gap-2 border border-slate-600 hover:border-slate-400 text-slate-300 px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors"
            >
              📋 Report an incident
            </Link>
          </div>
        </header>

        {/* ── MAIN CONTENT ── */}
        <div className="max-w-3xl mx-auto px-6 pb-16 space-y-12" itemProp="articleBody">

          {/* What is an online scam? */}
          <section id="what-is-an-online-scam">
            <h2 className="text-2xl font-bold mb-4">What Is an Online Scam?</h2>
            <p className="text-slate-300 leading-relaxed mb-4">
              Every day, someone in Southeast Asia clicks a link that empties their savings. You don't have to be
              the next victim — and this guide will show you exactly how to spot the trap before it springs.
            </p>
            <p className="text-slate-300 leading-relaxed mb-6">
              An online scam is any deceptive digital communication designed to steal money, personal data,
              or access to your accounts. Scammers in SEA are increasingly sophisticated, combining social
              engineering with technology to bypass your instincts.
            </p>

            {/* scam types */}
            <h3 className="text-xl font-semibold mb-4 text-white">The 5 Scam Types You Must Know</h3>
            <div className="grid sm:grid-cols-2 gap-4">
              <ScamCard
                icon="📱"
                type="Phishing & SMS Fraud"
                color="border-blue-800/40 bg-blue-950/10"
                signs={[
                  "Fake bank or government SMS",
                  "Links that mimic official domains",
                  "WhatsApp groups with fake prizes",
                  "Urgency: 'your account is frozen'",
                ]}
              />
              <ScamCard
                icon="📈"
                type="Investment & Crypto Scams"
                color="border-amber-800/40 bg-amber-950/10"
                signs={[
                  "Guaranteed daily/weekly returns",
                  "Celebrity endorsements (fake)",
                  "Unregistered platforms",
                  "Can't withdraw your funds",
                ]}
              />
              <ScamCard
                icon="💔"
                type="Romance Scams"
                color="border-pink-800/40 bg-pink-950/10"
                signs={[
                  "Never meets in person or on video",
                  "Builds trust over weeks",
                  "Emergency money request",
                  "Military / overseas professional",
                ]}
              />
              <ScamCard
                icon="💼"
                type="Money Mule Recruitment"
                color="border-orange-800/40 bg-orange-950/10"
                signs={[
                  "Job ad: easy remote work, high pay",
                  "Asked to receive & forward funds",
                  "No clear job description",
                  "Uses your personal bank account",
                ]}
              />
            </div>
          </section>

          {/* Warning signs checklist */}
          <section id="warning-signs">
            <h2 className="text-2xl font-bold mb-6">Warning Signs — A Practical Checklist</h2>
            <div className="space-y-3">
              <WarnSign icon="⚡" title="Urgency & Pressure" desc="Legitimate businesses don't demand you act in the next 10 minutes. If someone is rushing you, that's the scam." />
              <WarnSign icon="🔗" title="Suspicious URLs" desc="Hover over links before clicking. Look for misspellings like 'g00gle.com' or unfamiliar domains ending in .xyz, .top, .click." />
              <WarnSign icon="💰" title="Requests for Prepayment" desc="If you need to pay to receive a prize, job, or loan — it's a scam. Legitimate opportunities don't charge you first." />
              <WarnSign icon="🔒" title="Asks for OTP or Passwords" desc="No bank, government office, or legitimate company will ever ask for your one-time password over the phone or chat." />
              <WarnSign icon="🎯" title="Too Good to Be True Returns" desc="Any investment promising 10%+ monthly returns with zero risk is operating outside economic reality. Investigate before investing." />
              <WarnSign icon="📷" title="Won't Appear on Video" desc="Romance scammers use stolen photos. If someone refuses video calls after weeks of contact, reverse image search their photos." />
            </div>
          </section>

          {/* Immediate steps */}
          <section id="immediate-steps">
            <h2 className="text-2xl font-bold mb-6">Immediate Steps If You've Been Targeted</h2>
            <div className="space-y-3">
              {[
                { n: "1", title: "Stop all contact immediately", desc: "Do not reply, transfer more money, or click further links. Scammers escalate when they sense hesitation." },
                { n: "2", title: "Secure your accounts", desc: "Change passwords on email, banking, and social media. Enable two-factor authentication. Check for unauthorised access." },
                { n: "3", title: "Document everything", desc: "Screenshot all conversations, transaction IDs, account numbers, and URLs. This evidence is critical for your report." },
                { n: "4", title: "Contact your bank immediately", desc: "If you've transferred money, call your bank's fraud hotline. Transactions can sometimes be reversed within 24 hours." },
                { n: "5", title: "Report anonymously via SafePulse", desc: "Your incident data helps protect others in the region. All reports are anonymous and contribute to the public threat map." },
              ].map((step) => (
                <div key={step.n} className="flex gap-4 items-start p-4 rounded-xl border border-slate-800 bg-slate-900/50">
                  <span className="shrink-0 w-8 h-8 rounded-full bg-emerald-900/50 border border-emerald-700/50 text-emerald-400 font-black flex items-center justify-center text-sm">
                    {step.n}
                  </span>
                  <div>
                    <h3 className="font-semibold text-white mb-1">{step.title}</h3>
                    <p className="text-sm text-slate-400 leading-relaxed">{step.desc}</p>
                  </div>
                </div>
              ))}
            </div>

            {/* CTA */}
            <div className="mt-6 p-5 rounded-xl border border-emerald-800/50 bg-emerald-950/20 flex flex-col sm:flex-row items-center gap-4">
              <div className="flex-1">
                <div className="font-bold text-white mb-1">Report a scam incident anonymously</div>
                <div className="text-sm text-slate-400">Your report protects 2.4M+ people across 8 countries.</div>
              </div>
              <Link
                to="/report"
                className="shrink-0 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition-colors"
              >
                Report Now →
              </Link>
            </div>
          </section>

          {/* Regional resources */}
          <section id="regional-resources">
            <h2 className="text-2xl font-bold mb-6">Regional Resources &amp; Reporting</h2>
            <div className="grid sm:grid-cols-2 gap-3">
              {[
                { country: "🇮🇩 Indonesia", agency: "Bareskrim Polri", url: "https://patrolisiber.id" },
                { country: "🇲🇾 Malaysia", agency: "CCID (PDRM)", url: "https://ccid.rmp.gov.my" },
                { country: "🇸🇬 Singapore", agency: "ScamShield", url: "https://scamshield.org.sg" },
                { country: "🇵🇭 Philippines", agency: "NBI Cybercrime", url: "https://cybercrime.ph" },
                { country: "🇹🇭 Thailand", agency: "PCT (Thailand)", url: "https://www.thaipoliceonline.go.th" },
                { country: "🌏 SEA (All)", agency: "SafePulse Report", url: "/report" },
              ].map((r, i) => (
                <a
                  key={i}
                  href={r.url}
                  target={r.url.startsWith("http") ? "_blank" : undefined}
                  rel="noopener noreferrer"
                  className="flex justify-between items-center p-3 rounded-lg border border-slate-800 hover:border-emerald-700/50 hover:bg-slate-800/50 transition-all group"
                >
                  <div>
                    <div className="font-medium text-white text-sm">{r.country}</div>
                    <div className="text-xs text-slate-500">{r.agency}</div>
                  </div>
                  <span className="text-emerald-600 group-hover:text-emerald-400 transition-colors">↗</span>
                </a>
              ))}
            </div>
          </section>

          {/* FAQ section */}
          <section id="faq" itemScope itemType="https://schema.org/FAQPage">
            <h2 className="text-2xl font-bold mb-6">FAQ — Common Questions Answered</h2>
            <div className="space-y-4">
              {SCHEMA["@graph"][1].mainEntity.map((q, i) => (
                <details
                  key={i}
                  className="group rounded-xl border border-slate-800 bg-slate-900/50 overflow-hidden"
                  itemScope
                  itemProp="mainEntity"
                  itemType="https://schema.org/Question"
                >
                  <summary
                    itemProp="name"
                    className="flex justify-between items-center p-5 cursor-pointer font-semibold text-white hover:text-emerald-400 transition-colors list-none"
                  >
                    {q.name}
                    <span className="text-slate-500 group-open:rotate-180 transition-transform text-lg">▼</span>
                  </summary>
                  <div
                    className="px-5 pb-5 text-slate-400 text-sm leading-relaxed"
                    itemScope
                    itemProp="acceptedAnswer"
                    itemType="https://schema.org/Answer"
                  >
                    <span itemProp="text">{q.acceptedAnswer.text}</span>
                  </div>
                </details>
              ))}
            </div>
          </section>

          {/* internal link footer */}
          <div className="pt-4 border-t border-slate-800">
            <p className="text-sm text-slate-500 mb-4">Explore more SafePulse tools:</p>
            <div className="flex flex-wrap gap-2">
              {[
                { to: "/check", label: "🛡️ Scam Checker" },
                { to: "/dashboard", label: "🗺️ Threat Map" },
                { to: "/insights", label: "📰 Threat Library" },
                { to: "/evidence", label: "📁 Case Studies" },
                { to: "/products", label: "🕊️ Youth Peace Hub" },
              ].map((l) => (
                <Link
                  key={l.to}
                  to={l.to}
                  className="text-sm border border-slate-700 hover:border-emerald-700/50 hover:text-emerald-400 text-slate-400 px-4 py-2 rounded-lg transition-all"
                >
                  {l.label}
                </Link>
              ))}
            </div>
          </div>
        </div>
      </article>
    </div>
  );
}
