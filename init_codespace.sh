#!/usr/bin/env bash
# ============================================================
# SafePulse – Codespaces Bootstrap Script
# Runs automatically via postCreateCommand
# ============================================================
set -e

WORKSPACE="/workspaces/safepulse"
BACKEND="$WORKSPACE/backend"
FRONTEND="$WORKSPACE/frontend"

echo "════════════════════════════════════════"
echo "  SafePulse – Codespace Initialisation"
echo "════════════════════════════════════════"

# ── Backend ────────────────────────────────────────────────────────────────────
echo ""
echo "▶  [1/5] Installing Composer dependencies..."
cd "$BACKEND"
composer install --no-interaction --prefer-dist

echo ""
echo "▶  [2/5] Creating .env from .env.example..."
if [ ! -f "$BACKEND/.env" ]; then
  cp "$BACKEND/.env.example" "$BACKEND/.env"
fi

echo ""
echo "▶  [3/5] Generating Laravel application key..."
php artisan key:generate

echo ""
echo "▶  [4/5] Running database migrations and seeders..."
# SQLite file is created automatically if missing
touch "$BACKEND/database/database.sqlite"
php artisan migrate --force
php artisan db:seed --force

# ── Frontend ───────────────────────────────────────────────────────────────────
echo ""
echo "▶  [5/5] Installing Node dependencies..."
cd "$FRONTEND"
npm install

# Create frontend .env if missing
if [ ! -f "$FRONTEND/.env" ]; then
  cp "$FRONTEND/.env.example" "$FRONTEND/.env"
fi

echo ""
echo "════════════════════════════════════════"
echo "  ✅  Setup complete!"
echo ""
echo "  Start Laravel:   cd backend && php artisan serve --host=0.0.0.0 --port=8000"
echo "  Start Vite:      cd frontend && npm run dev -- --host 0.0.0.0 --port 5173"
echo ""
echo "  Ports forwarded: 8000 (API)  5173 (UI)"
echo "════════════════════════════════════════"
