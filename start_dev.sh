#!/usr/bin/env bash
# ============================================================
# SafePulse – Start Both Dev Servers
# Usage: bash start_dev.sh
# Both servers log to the terminal; Ctrl-C stops both.
# ============================================================
set -e
WORKSPACE="/workspaces/safepulse"

echo "▶ Starting Laravel API on port 8000…"
(cd "$WORKSPACE/backend" && php artisan serve --host=0.0.0.0 --port=8000) &
LARAVEL_PID=$!

sleep 2

echo "▶ Starting Vite dev server on port 5173…"
(cd "$WORKSPACE/frontend" && npm run dev -- --host 0.0.0.0 --port 5173) &
VITE_PID=$!

echo ""
echo "✅  Both servers running."
echo "   Laravel API : http://localhost:8000/api/ping"
echo "   Vite UI     : http://localhost:5173"
echo ""
echo "Press Ctrl+C to stop both."

# Wait for either process to exit
trap "kill $LARAVEL_PID $VITE_PID 2>/dev/null; exit" INT TERM
wait
