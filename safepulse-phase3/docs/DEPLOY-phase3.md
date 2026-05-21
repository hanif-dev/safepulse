# SafePulse Phase 3 — Deployment Guide

## What This Adds

- `safepulse-ai/` — FastAPI service (port 8001, Groq + FAISS)
- `frontend/src/components/AIAssistant/` — floating chat widget
- `backend/database/migrations/2026_08_01_*` — ai_ tables
- Nginx proxy `/ai/` → port 8001

---

## Step 1 — Copy Files to Repo (Codespaces)

```bash
# Copy safepulse-ai service
cp -r safepulse-phase3/safepulse-ai/ /workspaces/safepulse/

# Copy migration
cp safepulse-phase3/backend/database/migrations/*.php \
   /workspaces/safepulse/backend/database/migrations/

# Copy frontend widget
cp -r safepulse-phase3/frontend/src/components/AIAssistant \
   /workspaces/safepulse/frontend/src/components/

# Copy docs
cp safepulse-phase3/docs/* /workspaces/safepulse/docs/
```

---

## Step 2 — Add Widget to App.tsx

Add import at top:
```tsx
import AIAssistant from './components/AIAssistant';
```

Add inside the `<BrowserRouter>` or layout wrapper, AFTER `<Routes>`:
```tsx
<AIAssistant />
```

---

## Step 3 — Commit and Push

```bash
cd /workspaces/safepulse
git add .
git commit -m "feat(phase3): RAG AI assistant — Groq + FAISS + widget"
git push origin main
```

---

## Step 4 — Deploy on EC2

```bash
ssh ubuntu@172.31.38.84
bash /var/www/safepulse/docs/deploy.sh
```

---

## Step 5 — Configure .env

```bash
nano /var/www/safepulse/safepulse-ai/.env
```

Fill in:
- `GROQ_API_KEY` — get free at https://console.groq.com
- `DB_PASS` — your MySQL password (same as Laravel)
- `ADMIN_TOKEN` — same as your existing ADMIN_TOKEN in Laravel .env
- `INTERNAL_TOKEN` — any random string

---

## Step 6 — Seed Initial Documents

```bash
# From EC2, ingest seed documents one by one
BASE="https://safepulse.duckdns.org"
TOKEN="your_admin_token"

# Example: ingest BNPT document
curl -X POST "$BASE/ai/ingest/url" \
  -H "X-Admin-Token: $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://www.bnpt.go.id/storage/app/media/uploaded-files/RAN-PE-2023-2025.pdf",
    "title": "Rencana Aksi Nasional Penanggulangan Ekstremisme 2023-2025",
    "source": "BNPT",
    "organization": "Badan Nasional Penanggulangan Terorisme",
    "domain_tags": ["radicalization_pcve"],
    "language": "id"
  }'

# Trigger first crawl manually
curl "$BASE/ai/crawl/run" -H "X-Admin-Token: $TOKEN"
```

---

## Step 7 — Verify

```bash
# Health
curl https://safepulse.duckdns.org/ai/health

# Test chat
curl -X POST https://safepulse.duckdns.org/ai/session/start \
  -H "Content-Type: application/json" \
  -d '{"locale":"en"}'
# Copy session_token from response

curl -X POST https://safepulse.duckdns.org/ai/chat \
  -H "Content-Type: application/json" \
  -d '{
    "session_token": "YOUR_TOKEN",
    "message": "What is klitih and how does it relate to gang recruitment?",
    "locale": "en"
  }'
```

---

## Memory Note

EC2 t2.micro has 1GB RAM. Monitor after deploy:

```bash
free -h
systemctl status safepulse-ai
```

If memory is tight, upgrade to t3.small (~$17/month, 2GB RAM).
The main memory cost is the embedding model (~300MB on first load).

---

## Seed Documents Priority List

Ingest in this order for maximum coverage:

1. BNPT RAN-PE 2026-2029
2. ICCT — AI and Terrorism (auto-crawled)
3. UNODC — Scam Compounds SEA (auto-crawled)
4. IOM/BP2MI — Pre-Departure Orientation Modules 2022
5. WHO — Psychological First Aid Guide
6. Hedayah — Counter-Narrative Frameworks
7. STDIIS Al-Majalis — Jihadist analysis (Ahlus Sunnah perspective)
8. Ulumuna — ASWAJA and extremism Indonesia
9. YPP — Narasi Mematikan
10. BNPT Family Guidelines

The theological seed dataset (33 entries from classical sources) is
separate — use the `/ai/ingest` endpoint to upload each as a `.txt`
file with `sunni_scholarly=true`.
