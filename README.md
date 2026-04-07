# SafePulse – Anti-Scam & Digital Resilience Platform

> A full-stack production-ready application: **Laravel 10 API** + **React 18 + Vite + TypeScript + TailwindCSS**  
> Runs zero-config in **GitHub Codespaces** · Deploys to **AWS Amplify** (frontend) + **AWS EC2** (backend)

---

## Table of Contents

1. [Project Structure](#project-structure)
2. [Tech Stack](#tech-stack)
3. [Local Development in Codespaces](#local-development-in-codespaces)
4. [Manual Commands Reference](#manual-commands-reference)
5. [Database Migrations & Seeders](#database-migrations--seeders)
6. [Building for Production](#building-for-production)
7. [Deployment Guide](#deployment-guide)
8. [API Reference](#api-reference)
9. [Adding a New Language](#adding-a-new-language)
10. [Accessibility Features](#accessibility-features)

---

## Project Structure

```
safepulse/
├── .devcontainer/
│   ├── devcontainer.json        # Codespaces config: ports, postCreateCommand
│   └── Dockerfile               # PHP 8.2 + Node LTS + SQLite
├── backend/                     # Laravel 10 (API-only)
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/ # ScamChecker, Incident, Article, Stats
│   │   │   ├── Requests/        # FormRequest validation
│   │   │   └── Resources/       # API Resources (JSON transformers)
│   │   └── Models/              # Article, Incident
│   ├── config/cors.php          # CORS – reads FRONTEND_URL from .env
│   ├── database/
│   │   ├── migrations/          # articles, incidents tables
│   │   └── seeders/             # 14 articles + 80 incidents
│   ├── routes/api.php
│   └── .env.example
├── frontend/                    # React 18 + Vite + TypeScript
│   ├── src/
│   │   ├── components/          # Navbar, Footer, StatCard, ScoreGauge…
│   │   ├── hooks/               # useTheme, useFontSize, useTextToSpeech
│   │   ├── i18n/                # react-i18next; en.json, id.json
│   │   ├── pages/               # Home, Products, Impact, Evidence…
│   │   └── services/api.ts      # Axios service layer
│   ├── vite.config.ts
│   ├── tailwind.config.js
│   └── .env.example
├── init_codespace.sh            # One-shot bootstrap (runs on Codespace create)
├── .gitignore
└── README.md
```

---

## Tech Stack

| Layer       | Technology                                                  |
|-------------|-------------------------------------------------------------|
| Backend     | Laravel 10, PHP 8.2, API-only (no Blade)                    |
| Database    | SQLite (dev) → MySQL/RDS (prod) – swap one `.env` variable  |
| Frontend    | React 18, Vite, TypeScript, TailwindCSS, react-router-dom   |
| i18n        | react-i18next (EN + ID; extensible to JA, DE)               |
| Charts      | Recharts                                                    |
| Markdown    | react-markdown + remark-gfm                                 |
| Auth        | None – fully public API                                     |
| Dev runtime | GitHub Codespaces (devcontainer)                            |
| CI/CD       | AWS Amplify (frontend) + EC2 Free Tier (backend)            |

---

## Local Development in Codespaces

### Step 1 – Create a GitHub Repository

```bash
# On your local machine or via github.com UI:
gh repo create your-org/safepulse --public
git clone https://github.com/your-org/safepulse
# Copy all project files into the repo root
git add . && git commit -m "Initial SafePulse commit" && git push
```

### Step 2 – Open in Codespaces

1. On the GitHub repository page, click **Code → Codespaces → Create codespace on main**.
2. GitHub will build the devcontainer using `.devcontainer/Dockerfile`.
3. `postCreateCommand` automatically runs `init_codespace.sh` which:
   - Runs `composer install` in `/backend`
   - Creates `backend/.env` from `.env.example`
   - Runs `php artisan key:generate`
   - Creates `database/database.sqlite`
   - Runs `php artisan migrate --force`
   - Runs `php artisan db:seed --force`
   - Runs `npm install` in `/frontend`
   - Creates `frontend/.env` from `.env.example`

> ⏱ First build takes ~3–4 minutes. Subsequent opens are instant.

### Step 3 – Start the Servers

Open **two terminals** in VS Code:

**Terminal 1 – Laravel API:**
```bash
cd /workspaces/safepulse/backend
php artisan serve --host=0.0.0.0 --port=8000
```

**Terminal 2 – Vite Dev Server:**
```bash
cd /workspaces/safepulse/frontend
npm run dev -- --host 0.0.0.0 --port 5173
```

### Step 4 – View the App

In the **Ports** panel (bottom of VS Code), you will see:
- Port **5173** → Vite dev server → click 🌐 to open the app
- Port **8000** → Laravel API → used internally by the Vite proxy

> **CORS & Vite Proxy:** In development, all `/api/*` requests from the React app are proxied by Vite to `http://localhost:8000`. This means CORS is handled automatically and `VITE_API_BASE_URL=/api` is correct.

---

## Manual Commands Reference

```bash
# Re-run full setup from scratch
bash /workspaces/safepulse/init_codespace.sh

# Laravel
cd backend
php artisan migrate:fresh --seed    # Wipe & reseed DB
php artisan tinker                  # REPL
php artisan route:list              # View API routes

# Frontend
cd frontend
npm run dev         # Start Vite dev server
npm run build       # Production build → dist/
npm run lint        # ESLint check
```

---

## Database Migrations & Seeders

```bash
cd /workspaces/safepulse/backend

# Run all pending migrations
php artisan migrate

# Run migrations + all seeders (creates 14 articles + 80 incidents)
php artisan migrate --seed

# Wipe database and re-seed from scratch
php artisan migrate:fresh --seed

# Run only the article seeder
php artisan db:seed --class=ArticleSeeder
```

### Switching to MySQL

In `backend/.env`, change:

```dotenv
# Remove or comment out SQLite:
# DB_CONNECTION=sqlite

# Add MySQL:
DB_CONNECTION=mysql
DB_HOST=your-rds-endpoint.amazonaws.com
DB_PORT=3306
DB_DATABASE=safepulse
DB_USERNAME=admin
DB_PASSWORD=your_secure_password
```

No migration changes required — all migrations are DB-agnostic.

---

## Building for Production

### Frontend (React → Static Files)

```bash
cd frontend

# Set the production API URL in .env:
echo "VITE_API_BASE_URL=https://api.yourdomain.com/api" > .env

# Build
npm run build
# Output: frontend/dist/  (static HTML/JS/CSS — deploy to Amplify)
```

### Backend (Laravel)

```bash
cd backend
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Deployment Guide

### Frontend → AWS Amplify (Static Hosting, No Auth)

1. Push your repo to GitHub.
2. Go to **AWS Amplify Console → New App → Host web app**.
3. Connect your GitHub repo; select the `main` branch.
4. **Build settings:**
   ```yaml
   version: 1
   frontend:
     phases:
       preBuild:
         commands:
           - cd frontend && npm ci
       build:
         commands:
           - cd frontend && npm run build
     artifacts:
       baseDirectory: frontend/dist
       files:
         - '**/*'
     cache:
       paths:
         - frontend/node_modules/**/*
   ```
5. Add environment variable in Amplify console:
   `VITE_API_BASE_URL` = `https://api.yourdomain.com/api`
6. Add a **Rewrites & Redirects** rule for SPA routing:
   - Source: `</^[^.]+$|\.(?!(css|gif|ico|jpg|js|png|txt|svg|woff|woff2|ttf|map|json)$)([^.]+$)/>`
   - Target: `/index.html`
   - Type: `200 (Rewrite)`

### Backend → AWS EC2 Free Tier (t2.micro / t3.micro)

```bash
# On your EC2 instance (Ubuntu 22.04):

# 1. Install dependencies
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
  php8.2-mbstring php8.2-xml php8.2-zip php8.2-curl \
  nginx mysql-client git composer

# 2. Clone & configure
git clone https://github.com/your-org/safepulse.git /var/www/safepulse
cd /var/www/safepulse/backend
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env: set APP_URL, DB_*, FRONTEND_URL
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force

# 3. Set permissions
sudo chown -R www-data:www-data /var/www/safepulse/backend/storage
sudo chown -R www-data:www-data /var/www/safepulse/backend/bootstrap/cache

# 4. Nginx config (/etc/nginx/sites-available/safepulse-api)
# server {
#   listen 80;
#   server_name api.yourdomain.com;
#   root /var/www/safepulse/backend/public;
#   index index.php;
#   location / { try_files $uri $uri/ /index.php?$query_string; }
#   location ~ \.php$ {
#     fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
#     include fastcgi_params;
#     fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
#   }
# }

sudo ln -s /etc/nginx/sites-available/safepulse-api /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

---

## API Reference

All endpoints are under `/api/`.

| Method | Endpoint              | Description                                    |
|--------|-----------------------|------------------------------------------------|
| GET    | `/api/ping`           | Health check                                   |
| POST   | `/api/check-scam`     | Analyse message/URL/phone/account for fraud    |
| POST   | `/api/incidents`      | Submit anonymous incident report               |
| GET    | `/api/articles`       | List articles (supports `?category&language&search&page`) |
| GET    | `/api/articles/{slug}`| Get single article with full markdown body     |
| GET    | `/api/stats/overview` | Aggregated incident stats for dashboard        |

**POST `/api/check-scam`** – Request body:
```json
{
  "message_text": "You have won $1,000,000! Click here now!",
  "url": "http://bit.ly/claim-prize",
  "phone_number": "+44 700 1234567",
  "bank_account": "0x742d35Cc6634C0532925a3b844Bc454e4438f44e"
}
```

**Response:**
```json
{
  "score": 85,
  "level": "High",
  "reasons": [
    "High-risk phrase detected: \"you have won\"",
    "URL uses insecure HTTP instead of HTTPS.",
    "URL uses a shortener service – destination is hidden.",
    "Phone prefix \"+44 70\" is associated with premium-rate scams.",
    "Input matches a cryptocurrency wallet address – verify recipient carefully."
  ]
}
```

---

## Adding a New Language

1. Create `frontend/src/i18n/locales/ja.json` (copy `en.json` and translate).
2. In `frontend/src/i18n/index.ts`:
   ```ts
   import ja from './locales/ja.json';
   // ...
   resources: { en: ..., id: ..., ja: { translation: ja } },
   supportedLngs: ['en', 'id', 'ja'],
   ```
3. In `frontend/src/components/Navbar.tsx`, add to `LANGUAGES` array:
   ```ts
   { code: 'ja', label: '日本語' },
   ```

---

## Accessibility Features

| Feature | Implementation |
|---------|----------------|
| Skip-to-content link | `<a href="#main-content">` in `index.html`, visually hidden until focused |
| Focus styles | `*:focus-visible` ring via Tailwind in `index.css` |
| ARIA labels | All interactive controls have `aria-label` or associated `<label>` |
| Dark/high-contrast mode | `useTheme` hook toggles `dark` class on `<html>` |
| Font-size control | `useFontSize` hook cycles `font-size-md/lg/xl` on `<html>` |
| Text-to-speech | `useTextToSpeech` hook wraps `SpeechSynthesis` API; available on article detail pages |
| Keyboard navigation | Full tab order; dropdowns have `aria-expanded`, `role="listbox"` |
| Semantic HTML | `<header>`, `<main>`, `<nav>`, `<article>`, `<section>`, `<footer>` throughout |
| WCAG colour contrast | Primary palette tested at AA for text on white and dark backgrounds |

---

## Environment Variables Summary

### `backend/.env`
| Variable | Default | Notes |
|----------|---------|-------|
| `APP_KEY` | *(generated)* | Run `php artisan key:generate` |
| `DB_CONNECTION` | `sqlite` | Change to `mysql` for production |
| `DB_DATABASE` | `/workspaces/…/database.sqlite` | Absolute path for SQLite |
| `FRONTEND_URL` | `http://localhost:5173` | Used by CORS config |

### `frontend/.env`
| Variable | Default | Notes |
|----------|---------|-------|
| `VITE_API_BASE_URL` | `/api` | Proxied by Vite in dev; set full URL in prod |
