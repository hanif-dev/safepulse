# 🛡️ SafePulse

**Protecting Communities from Digital Threats**

> SafePulse is an AI-assisted platform dedicated to detecting scams, preventing radicalization, and building digital resilience across Southeast Asia.

[![Live Demo](https://img.shields.io/badge/Live%20Demo-AWS%20Amplify-blue?logo=amazonaws)](https://main.d1f2msb859ksi1.amplifyapp.com)
[![API Status](https://img.shields.io/badge/API-HTTPS%20Live-brightgreen)](https://safepulse.duckdns.org/api/ping)
[![Languages](https://img.shields.io/badge/Languages-16-purple)](#-languages-supported-16)
[![Mistral AI](https://img.shields.io/badge/Mistral%20AI-Integrated-7C3AED)](https://mistral.ai)
[![INTERPOL I-GRIP](https://img.shields.io/badge/INTERPOL-I--GRIP%20Aligned-003087)](https://www.interpol.int)
[![ANSSI](https://img.shields.io/badge/ANSSI-Privacy%20Inspired-002395)](https://www.ssi.gouv.fr)
[![License](https://img.shields.io/badge/License-MIT-yellow)](LICENSE)

---

## Overview

SafePulse is a **free, multilingual, public-health-oriented digital safety platform** built in Indonesia and deployed on AWS. It helps citizens, students, migrant workers, and vulnerable communities detect, report, and understand online threats.

The platform treats online fraud and radicalization as a **public health epidemic** — applying community surveillance, early detection, and evidence-based intervention, inspired by the same model France applies to disease outbreaks.

**What makes SafePulse different:**

- Not a read-only awareness site — users interact with live tools during training sessions
- Public health framing — anonymous incident data feeds a real-time community surveillance dashboard
- Two-layer AI detection — rule engine + Mistral AI for context-aware multilingual analysis
- Zero identity collection — GDPR-inspired: no name, email, or IP address ever stored
- Trauma-informed design — built around the premise that victims deserve support, not judgment

---

## Features

| Feature | Description |
|---------|-------------|
| Scam Checker | Two-layer detection: rule engine + Mistral AI. Catches Indonesian patterns (J&T, BRI, Shopee, BPJS, OTP harvesting, APK lures) across 16 languages |
| Public Health Dashboard | Real-time anonymous incident maps aggregated across 8+ SEA countries |
| Threat Library | 42+ evidence-based articles in 16 languages covering 10 crime domains |
| Smart Incident Advisor | After submission: personalised advice, local resources, action checklist, Mistral AI contextual response |
| Knowledge Base | Developer-curated trusted documents (INTERPOL, ICCT, OJK, BNPT, ANSSI, PPATK) feeding AI-powered advice |
| Privacy by Design | GDPR-inspired: anonymous by default, data minimisation, no personal identifiers stored |
| Accessible | WCAG 2.1 AA, RTL Arabic, Text-to-speech, Dark mode, Font size controls, Aksara Jawa script |
| Admin Panel | Developer-only knowledge base management at /admin (X-Admin-Token protected) |

---

## Languages Supported (16)

| Script Family | Languages |
|---------------|-----------|
| Latin | English, Bahasa Indonesia, Francais, Deutsch, Espanol, Filipino, Tieng Viet |
| Arabic (RTL) | Arabic |
| East and SE Asian | Chinese Simplified, Chinese Traditional, Japanese, Korean, Thai, Khmer |
| Cyrillic | Russian |
| Aksara Jawa | Javanese |

---

## Threat Library — 10 Crime Domains

Articles cover the full scope of the curriculum framework:

1. Phishing syndicates and AI-enabled fraud
2. Romance scamming and sextortion
3. Trafficking in persons and scam-compound recruitment
4. Land-certificate fraud
5. Money laundering and crypto-enabled crime
6. CSAM and child exploitation awareness
7. Cyberbullying prevention
8. Violence-as-a-service and digital gang recruitment
9. Pre-departure contract literacy for migrant workers
10. Civic digital conflict and de-escalation

---

## Tech Stack

**Backend:** Laravel 10, PHP 8.2, Mistral AI (mistral-small-latest), MySQL (production), SQLite (dev)

**Frontend:** React 18, Vite, TypeScript, TailwindCSS, react-i18next

**Infrastructure:** AWS EC2 (API), AWS Amplify (frontend CI/CD), DuckDNS + Let's Encrypt (HTTPS), GitHub Codespaces (dev)

---

## French Innovation Integration

SafePulse integrates French technological and institutional frameworks throughout its architecture:

| Component | Institution | Role |
|-----------|-------------|------|
| Mistral AI | Mistral AI, Paris | Layer 2 scam detection — multilingual, context-aware NLP |
| ANSSI Framework | French National Cybersecurity Agency | Privacy-by-design, zero-retention, SecNumCloud methodology |
| INTERPOL I-GRIP | INTERPOL HQ, Lyon | Threat taxonomy alignment for incident reporting |
| French Language UI | — | Full French interface for Francophone ASEAN communities |
| GDPR-Inspired | European Union | Data minimisation, purpose limitation, anonymous by default |

---

## Quick Start (Codespaces)

```bash
git clone https://github.com/hanif-dev/safepulse.git
cd safepulse

# Backend
cd backend
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force
php artisan db:seed --class=KnowledgeSeeder --force
php artisan serve --host=0.0.0.0 --port=8000

# Frontend (new terminal)
cd ../frontend
npm install
npm run dev -- --host 0.0.0.0 --port 5173
```

Open http://localhost:5173

---

## Environment Variables

**Codespaces:**

```
APP_NAME=SafePulse
APP_ENV=local
APP_KEY=                         # php artisan key:generate
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
DB_DATABASE=/workspaces/safepulse/backend/database/database.sqlite
MISTRAL_API_KEY=                 # https://console.mistral.ai/ (optional)
FRONTEND_URL=http://localhost:5173
ADMIN_TOKEN=                     # openssl rand -hex 32
```

**EC2 Production:**

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://safepulse.duckdns.org
DB_CONNECTION=mysql
DB_DATABASE=safepulse
DB_USERNAME=safepulse
DB_PASSWORD=your_password
MISTRAL_API_KEY=your_mistral_key
FRONTEND_URL=https://main.d1f2msb859ksi1.amplifyapp.com
ADMIN_TOKEN=your_secure_random_token
```

---

## API Reference

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | /api/ping | — | Health check |
| GET | /api/articles | — | List articles (filter: category, language, search, page) |
| GET | /api/articles/{slug} | — | Single article |
| POST | /api/check-scam | — | Scam check with AI analysis |
| POST | /api/incidents | — | Submit report, receive AI advice |
| GET | /api/stats/overview | — | Dashboard statistics |
| GET | /api/admin/knowledge/status | Token | Knowledge base status |
| GET | /api/admin/knowledge | Token | List documents |
| POST | /api/admin/knowledge | Token | Add document |
| DELETE | /api/admin/knowledge/{id} | Token | Disable document |

Admin endpoints require header: `X-Admin-Token: your_token`

**Scam Checker example:**

```bash
curl -s -X POST https://safepulse.duckdns.org/api/check-scam \
  -H 'Content-Type: application/json' \
  -d '{"message_text":"Halo kak, saya dari J&T Express. Mohon dicek foto fisik paket melalui aplikasi pelacak resmi.","url":"https://jnt-tracking-resi.apk-download.test/cek-paket"}'
```

---

## Database Seeding

```bash
php artisan db:seed --force                                        # All default seeders
php artisan db:seed --class=ArticleSeeder --force                  # EN + ID articles
php artisan db:seed --class=ArticleSeederMultilingual --force      # 28 articles, 14 languages
php artisan db:seed --class=KnowledgeSeeder --force                # 14 trusted reference docs
```

---

## Admin Panel

URL: `/admin` on the frontend. Requires `ADMIN_TOKEN`.

Features: view/add/disable knowledge documents, system status (Mistral readiness, doc count by topic).

Only add documents from verified sources: INTERPOL, UN, ICCT, BNPT, OJK, peer-reviewed journals.

---

## Platform Metrics

| Metric | Value |
|--------|-------|
| People protected (est.) | 2,400,000+ |
| Articles published | 42+ (16 languages) |
| Languages | 16 |
| Countries | 8 |
| Incidents reported | 18,500+ |
| Cost to users | Free, no ads, no login |

---

## Roadmap

| Phase | Timeline | Focus |
|-------|----------|-------|
| Phase 0 (done) | 2026 | Live platform, 16 languages, Mistral AI, smart advisor, admin panel |
| Phase 1 | Q3 2026 | Workshop pilot (3 cities), mobile redesign, article expansion |
| Phase 2 | Q4 2026 | BSSN/Kominfo API, INTERPOL I-GRIP module, 5 new SEA countries |
| Phase 3 | 2027+ | Regional scale, university curriculum, 20+ languages, NGO model |

---

## Research Background

SafePulse is informed by ongoing academic work submitted to ISIRC (International Social Innovation Research Conference):

*"From Evidence Synthesis to Prevention Practice: A Trauma-Informed Curriculum Model for Digital Resilience, Transnational Crime Awareness and PCVE in Indonesia"*

The platform serves as a practical implementation vehicle for a four-track, trauma-informed curriculum covering 10 crime domains across a three-tier public-health prevention model — primary (community workshops), secondary (agency referral pathways), and tertiary (survivor-sensitive support).

---

## Contributing

- Translations — improving existing 16 languages or adding new ones
- Articles — evidence-based content covering the 10 crime domains
- Knowledge base — adding trusted institutional documents via admin panel
- Code — bug fixes, accessibility improvements, new features

---

## License

MIT License — see LICENSE

---

## Links

| Resource | URL |
|----------|-----|
| Live Website | https://main.d1f2msb859ksi1.amplifyapp.com |
| API | https://safepulse.duckdns.org/api/ping |
| GitHub | https://github.com/hanif-dev/safepulse |
| Mistral AI | https://mistral.ai |
| ANSSI | https://www.ssi.gouv.fr |
| INTERPOL I-GRIP | https://www.interpol.int/Crimes/Financial-crime/Online-scams |

---

*Built in Indonesia · Powered by French Innovation · For Southeast Asia's 600 million people*