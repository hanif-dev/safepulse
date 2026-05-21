#!/bin/bash
# SafePulse Phase 3 — EC2 Deployment Script
# Run on EC2: bash deploy.sh
set -e

echo "=== SafePulse Phase 3 Deploy ==="

# ── 1. Pull latest code ───────────────────────────────────────────────────
cd /var/www/safepulse
git pull origin main

# ── 2. Laravel migration ──────────────────────────────────────────────────
cd /var/www/safepulse/backend
php artisan migrate --force
php artisan config:clear && php artisan route:clear
sudo systemctl restart php8.2-fpm
echo "✓ Laravel migrated"

# ── 3. Setup safepulse-ai Python service ─────────────────────────────────
cd /var/www/safepulse/safepulse-ai

# Create venv if not exists
if [ ! -d "venv" ]; then
    python3 -m venv venv
fi

source venv/bin/activate
pip install -q -r requirements.txt
deactivate

# Copy .env if not exists
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "⚠️  Created .env — fill in GROQ_API_KEY and DB credentials!"
fi

echo "✓ Python dependencies installed"

# ── 4. Install systemd service ────────────────────────────────────────────
sudo cp /var/www/safepulse/docs/safepulse-ai.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable safepulse-ai
sudo systemctl restart safepulse-ai
echo "✓ safepulse-ai service started"

# ── 5. Update Nginx ───────────────────────────────────────────────────────
# Check if /ai/ block already in nginx config
if ! grep -q "location /ai/" /etc/nginx/sites-available/safepulse; then
    echo ""
    echo "⚠️  Add this block inside your server {} in /etc/nginx/sites-available/safepulse:"
    cat /var/www/safepulse/docs/nginx-ai.conf
    echo ""
else
    echo "✓ Nginx /ai/ block already configured"
fi

sudo nginx -t && sudo systemctl reload nginx

# ── 6. Smoke tests ───────────────────────────────────────────────────────
sleep 3
echo ""
echo "=== Smoke Tests ==="

echo -n "Health check: "
curl -sf https://safepulse.duckdns.org/ai/health | python3 -m json.tool | grep status || echo "FAIL"

echo -n "Chat endpoint: "
TOKEN=$(curl -sf -X POST https://safepulse.duckdns.org/ai/session/start \
  -H "Content-Type: application/json" \
  -d '{"locale":"en"}' | python3 -c "import sys,json; print(json.load(sys.stdin)['session_token'])")
echo "session token: $TOKEN"

echo ""
echo "=== Phase 3 deployed! ==="
echo "Widget appears on all SafePulse pages except /check /report /admin"
echo "Admin ingest: POST /ai/ingest with X-Admin-Token header"
echo "Manual crawl: GET /ai/crawl/run with X-Admin-Token header"
