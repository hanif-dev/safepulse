# SafePulse Mega-Update — Deployment Guide

This update brings four major improvements:

1. **Massively expanded scam detection** — Indonesian patterns (J&T, BRI, BPJS, Shopee), brand impersonation logic, APK URL detection, OTP-harvesting detection, multilingual keywords (EN/ID/FR/AR/TL/VI/TH)
2. **Smart incident reporter** — after submit, returns personalised advice + local Indonesian resources + action checklist + knowledge-base references + optional Mistral AI contextual response
3. **Admin knowledge-base panel** — developer-only via X-Admin-Token; view/add/disable documents that feed RAG-style answers; status dashboard
4. **4 new ASEAN languages** — Thai (th), Vietnamese (vi), Filipino/Tagalog (tl), Khmer (km) — total now **16 languages**

---

## 1. Copy files into your repo

```bash
# In Codespaces / local dev
cp -r safepulse-mega/backend/*   /workspaces/safepulse/backend/
cp -r safepulse-mega/frontend/*  /workspaces/safepulse/frontend/
```

Files added:
- `backend/app/Http/Controllers/Api/ScamCheckerController.php`  *(replaced — much bigger)*
- `backend/app/Http/Controllers/Api/IncidentController.php`     *(replaced — smart response)*
- `backend/app/Http/Controllers/Api/KnowledgeController.php`    *(new)*
- `backend/app/Models/KnowledgeDocument.php`                    *(new)*
- `backend/database/migrations/2024_05_16_000000_create_knowledge_documents_table.php` *(new)*
- `backend/database/seeders/KnowledgeSeeder.php`                *(new — 14 trusted docs)*
- `backend/routes/api.php`                                       *(replaced — adds admin routes)*
- `backend/config/services.php`                                  *(replaced — adds admin config)*
- `frontend/src/pages/IncidentReport.tsx`                        *(replaced — AI advice page)*
- `frontend/src/pages/Admin.tsx`                                 *(new)*
- `frontend/src/services/api.ts`                                 *(replaced — new types + admin client)*
- `frontend/src/i18n/index.ts`                                   *(replaced — 16 languages)*
- `frontend/src/i18n/locales/{th,vi,tl,km}.json`                *(new — 4 ASEAN locales)*

---

## 2. Wire up `/admin` route in App.tsx

Open `frontend/src/App.tsx` and add **two lines**:

```tsx
// Add to imports:
import Admin from "./pages/Admin";

// Add inside <Routes>:
<Route path="/admin" element={<Admin />} />
```

---

## 3. Commit & push

```bash
cd /workspaces/safepulse
git add .
git commit -m "feat: expand scam detection + smart incident advisor + admin knowledge base + 4 ASEAN languages"
git push origin main
```

---

## 4. Deploy on EC2

```bash
# SSH to EC2
ssh ubuntu@safepulse.duckdns.org

cd /var/www/safepulse/backend

# Pull latest code
git pull origin main

# Add admin token to .env
nano .env
# Add this line (generate a random strong token):
ADMIN_TOKEN=safepulse-admin-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Save (Ctrl+X → Y → Enter)

# Run migration for knowledge_documents table
php artisan migrate --force

# Seed initial knowledge base (14 trusted documents)
php artisan db:seed --class=KnowledgeSeeder --force

# Clear and rebuild config cache
php artisan config:clear
php artisan config:cache

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

---

## 5. Smoke tests

```bash
# 1. Test scam detection — Indonesian courier scam
curl -s -X POST https://safepulse.duckdns.org/api/check-scam \
  -H 'Content-Type: application/json' \
  -d '{"message_text":"Halo kak, saya dari kurir J&T Express. Ada paket atas nama Budi. Mohon dicek foto fisik paket melalui aplikasi pelacak resmi.","url":"https://jnt-tracking-resi.apk-download.test/cek-paket"}' \
  | python3 -m json.tool

# Expected: score 70+, level High, multiple findings including "J&T Express courier impersonation"

# 2. Test scam detection — BRI fake fee
curl -s -X POST https://safepulse.duckdns.org/api/check-scam \
  -H 'Content-Type: application/json' \
  -d '{"message_text":"PENGUMUMAN PERUBAHAN TARIF REKENING BRI. Auto-debit Rp 150.000/bulan. Klik link untuk pembatalan tarif normal.","url":"https://bri-tarif-perubahan.login-akses-akun.net/auth"}' \
  | python3 -m json.tool

# Expected: score 80+, multiple BRI/Indonesia patterns flagged

# 3. Test smart incident response
curl -s -X POST https://safepulse.duckdns.org/api/incidents \
  -H 'Content-Type: application/json' \
  -d '{"category":"radicalization","country":"Indonesia","description":"My younger brother joined a private Telegram group that gradually shifted to extremist content. He uses new vocabulary I do not recognize.","health_impact_level":"high"}' \
  | python3 -m json.tool

# Expected: response includes "analysis" with advice steps, BNPT resource, action checklist

# 4. Test admin panel (use ADMIN_TOKEN you just set)
curl -s https://safepulse.duckdns.org/api/admin/knowledge/status \
  -H 'X-Admin-Token: YOUR_TOKEN_HERE' \
  | python3 -m json.tool

# Expected: { system, version, total_docs: 14, active_docs: 14, by_topic, mistral_ready, rag_status }

# 5. Test admin auth rejection (no token)
curl -s https://safepulse.duckdns.org/api/admin/knowledge/status | python3 -m json.tool

# Expected: { error: "Unauthorized", message: "Admin token required..." }
```

---

## 6. Frontend (Amplify will auto-deploy after push)

After Amplify build finishes (~2 min):

1. Open https://main.d1f2msb859ksi1.amplifyapp.com
2. Switch language to **Thai / Vietnamese / Filipino / Khmer** in the language selector
3. Submit a test incident report → see the new advice page with steps, resources, checklist
4. Visit `/admin` → log in with your ADMIN_TOKEN → see knowledge base management UI

---

## 7. What the jury will see

| Page | New capability |
|------|----------------|
| `/check` | Catches Indonesian scams: J&T, BRI, BPJS, Shopee, BPJS, OJK impersonation, APK lures, OTP harvesting, brand-in-subdomain abuse |
| `/report` | After submission, displays a full advice page with steps, BNPT/OJK/PPATK contacts, knowledge-base references |
| `/admin` | Developer-only knowledge management with status panel showing RAG readiness |
| Language switcher | 16 languages now — including Thai, Vietnamese, Filipino, Khmer for full ASEAN coverage |

---

## 8. Mistral AI activation (when API key resolved)

Currently: rule engine works fully; Mistral is gracefully skipped if `MISTRAL_API_KEY` is invalid.
When Mistral key works (regenerated from console.mistral.ai with valid billing):
- Both `/api/check-scam` and `/api/incidents` will include a `🤖 Mistral:` line in responses
- `powered_by` will show "SafePulse + Mistral AI (🇫🇷 Paris)"
- No code changes needed — just set the working key in `.env` and `config:cache`
