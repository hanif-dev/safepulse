# SafePulse Phase 2 — Deployment Guide

This document describes how to incrementally deploy Phase 2 to your existing SafePulse repository on GitHub and AWS EC2/Amplify.

## Prerequisites

- Phase 0 / Phase 1 already deployed and working
- Backend on EC2: Laravel 10 + PHP 8.2 + MySQL at `/var/www/safepulse/backend`
- Frontend on Amplify: React 18 + Vite at `https://main.d1f2msb859ksi1.amplifyapp.com`
- GitHub Codespaces or local dev environment
- 5–10 minutes per sprint

## Folder Structure of This Package

```
safepulse-phase2/
├── backend/
│   ├── app/
│   │   ├── Models/                 (11 new Eloquent models)
│   │   └── Http/Controllers/Api/   (4 new controllers)
│   ├── database/
│   │   ├── migrations/             (14 new migrations)
│   │   └── seeders/                (6 new seeders + master)
│   └── routes/
│       └── api_v2.php              (new — include from api.php)
├── frontend/
│   └── src/
│       ├── components/             (SafetyGate, EmergencyExit, HotlineCard)
│       ├── pages/                  (recovery, adaptive — initial pages)
│       └── services/
│           └── api-v2.ts           (TypeScript API client)
└── docs/
    └── DEPLOY.md                   (this file)
```

## Deployment — 7 Sprints

You can deploy each sprint independently. The platform stays functional after every sprint.

---

### Sprint 2.0 — Foundation (Week 1)

Deploy database schema and skeleton infrastructure. No user-facing changes yet.

```bash
# In Codespaces or local
cd /workspaces/safepulse

# Copy migrations
cp /path/to/safepulse-phase2/backend/database/migrations/*.php \
   backend/database/migrations/

# Copy models
cp /path/to/safepulse-phase2/backend/app/Models/*.php \
   backend/app/Models/

# Copy controllers
cp /path/to/safepulse-phase2/backend/app/Http/Controllers/Api/*.php \
   backend/app/Http/Controllers/Api/

# Copy routes
cp /path/to/safepulse-phase2/backend/routes/api_v2.php \
   backend/routes/

# Register v2 routes in api.php
# Add this single line at the bottom of backend/routes/api.php:
#   Route::prefix('v2')->group(base_path('routes/api_v2.php'));

# Copy seeders
cp /path/to/safepulse-phase2/backend/database/seeders/*.php \
   backend/database/seeders/

# Test locally
cd backend
php artisan migrate
php artisan db:seed --class=Phase2Seeder

# Verify
curl http://localhost:8000/api/v2/recovery-pathways

# Commit
cd /workspaces/safepulse
git add .
git commit -m "feat(phase2): foundation — 14 migrations, 11 models, 4 controllers, 6 seeders"
git push origin main
```

On EC2:

```bash
cd /var/www/safepulse/backend
git pull origin main
php artisan migrate --force
php artisan db:seed --class=Phase2Seeder --force
php artisan config:clear && php artisan config:cache
sudo systemctl restart php8.2-fpm

# Verify
curl https://safepulse.duckdns.org/api/v2/recovery-pathways
```

---

### Sprint 2.1 — Recovery Pathway Frontend (Week 2)

Deploy frontend pages for Recovery Pathway browsing.

```bash
cd /workspaces/safepulse/frontend

# Copy components
mkdir -p src/components
cp /path/to/safepulse-phase2/frontend/src/components/*.tsx src/components/

# Copy services
cp /path/to/safepulse-phase2/frontend/src/services/api-v2.ts src/services/

# Copy pages
mkdir -p src/pages/recovery src/pages/adaptive
cp /path/to/safepulse-phase2/frontend/src/pages/recovery/*.tsx src/pages/recovery/
cp /path/to/safepulse-phase2/frontend/src/pages/adaptive/*.tsx src/pages/adaptive/

# Add routes to App.tsx — add these inside <Routes>:
#   <Route path="/recovery" element={<RecoveryIndex />} />
#   <Route path="/recovery/:slug" element={<RecoveryDetail />} />
#   <Route path="/adaptive/quick" element={<QuickFlow />} />

# Test locally
npm run dev

# Commit
cd /workspaces/safepulse
git add .
git commit -m "feat(phase2): Recovery Pathway + Adaptive Quick Mode UI"
git push origin main
```

Amplify auto-deploys on push.

---

### Sprint 2.2 — Adaptive Deep Mode + Migrant Education

Implement the remaining frontend pages from the roadmap. The backend endpoints already exist.

Files to create (templates in the roadmap document):

```
frontend/src/pages/adaptive/DeepFlow.tsx
frontend/src/pages/adaptive/ResolvedPlan.tsx
frontend/src/pages/migrant/CurriculumIndex.tsx
frontend/src/pages/migrant/ModulePlayer.tsx
frontend/src/pages/migrant/PreAssessment.tsx
frontend/src/pages/migrant/PostAssessment.tsx
```

---

### Sprint 2.3 — Workshop Integration

Frontend pages for CyberShield ASEAN 2.0 workshop tooling:

```
frontend/src/pages/workshop/FacilitatorDashboard.tsx
frontend/src/pages/workshop/ParticipantJoin.tsx
frontend/src/pages/workshop/CertificateView.tsx
```

---

### Sprint 2.4 — Community Healing

```
frontend/src/pages/community/SurvivorStories.tsx
frontend/src/pages/community/MentorRequest.tsx
```

---

### Sprint 2.5 — De-radicalization (Family-side)

```
frontend/src/pages/deradicalization/FamilyAssessment.tsx
frontend/src/pages/deradicalization/ApproachScripts.tsx
frontend/src/pages/deradicalization/CounterNarrativeLibrary.tsx
```

---

### Sprint 2.6 — PFA Resources & Multilingual Content

Add the 16 localization JSON files (en, id, fr, ar, de, es, zh, zh-TW, ru, ko, ja, jv, th, vi, tl, km) with namespace keys: `recovery.*`, `adaptive.*`, `migrant.*`, `workshop.*`, `pfa.*`, `safety_gate.*`.

## Smoke Tests (after Sprint 2.0)

```bash
# Hotlines
curl https://safepulse.duckdns.org/api/v2/recovery-pathways
# → expect 4 pathways: romance-scam-recovery, tppo-recovery-cambodia-myanmar,
#   phishing-financial-recovery, family-radicalization-concern

curl 'https://safepulse.duckdns.org/api/v2/recovery-pathways/romance-scam-recovery?lang=id'
# → expect Indonesian title + 4 weekly milestones + 4 hotlines

curl -X POST https://safepulse.duckdns.org/api/v2/adaptive/quick \
  -H 'Content-Type: application/json' \
  -d '{"role":"victim","domain":"romance_scam","country":"ID","locale":"id"}'
# → expect top_actions[] + emergency_hotlines[] with patrolisiber, ojk_157, sapa_129

curl 'https://safepulse.duckdns.org/api/v2/migrant/curriculum?to=MY&sector=palm_oil&lang=id'
# → expect 7 modules: M1_VERIFIKASI through M7_VE_AWARE

curl 'https://safepulse.duckdns.org/api/v2/legal-aid?province=DKI%20Jakarta'
# → expect LBH Jakarta + LBH Pers
```

## Rollback

Each migration has a `down()` method. To rollback the entire Phase 2:

```bash
php artisan migrate:rollback --step=14
```

To rollback only the latest migration:

```bash
php artisan migrate:rollback --step=1
```

## Notes on Privacy & Trauma-Informed Care

- **All Phase 2 endpoints are anonymous by default.** No PII required.
- **Audit logs strip PII automatically** via `AuditLog::record()` helper.
- **30-day auto-purge** of UserProfile records (set via `expires_at`).
- **Survivor stories require 6-monthly consent renewal** (`consent_review_due`).
- **No 24/7 mental health support is offered directly** — all routing goes to existing verified hotlines (LISA, SEJIWA 119, SAPA 129).

## What Is NOT Included

- Translation files for 16 languages — these need to be generated separately (Lokalise workflow recommended).
- Template PDFs/DOCX files (bank letter, police report, LBH intake) — these need to be drafted with a lawyer and placed in `storage/templates/{lang}/`.
- Survivor story videos — these need to be filmed with informed consent.
- Counter-narrative content — SafePulse hosts none; it only links to Hedayah, PeaceGen, YPP, AMAN, YLP.

## Reference

The full Phase 2 roadmap with rationale, citations, and success metrics is in the previous Compass artifact in this conversation.
