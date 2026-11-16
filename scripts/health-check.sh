#!/usr/bin/env bash
# ============================================================
# Libya Marketplace — Health Check
# ============================================================
# Verifies: DB, storage, queue, cache, HTTP, HTTPS cert
# Run: ./scripts/health-check.sh
# ============================================================

set -uo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "${APP_DIR}"

FAILED=0
ok()   { echo "  [OK]   $1"; }
fail() { echo "  [FAIL] $1"; FAILED=$((FAILED+1)); }

echo "==> Health Check — Libya Marketplace"
echo ""

echo "--- 1. Database connection"
if php artisan db:show >/dev/null 2>&1; then
    ok "DB reachable"
else
    fail "DB connection failed"
fi

echo "--- 2. Migrations"
PENDING=$(php artisan migrate:status 2>/dev/null | grep -c "Pending" || true)
if [ "${PENDING}" -eq 0 ]; then
    ok "All migrations applied"
else
    fail "${PENDING} pending migrations"
fi

echo "--- 3. Storage writable"
if [ -w "storage/app/public" ] && [ -w "storage/logs" ]; then
    ok "storage/ writable"
else
    fail "storage/ not writable"
fi

echo "--- 4. Storage symlink"
if [ -L "public/storage" ]; then
    ok "public/storage linked"
else
    fail "public/storage symlink missing"
fi

echo "--- 5. Cache driver"
if php artisan cache:clear >/dev/null 2>&1; then
    ok "Cache store reachable"
else
    fail "Cache store unreachable"
fi

echo "--- 6. Queue status"
PENDING_JOBS=$(php artisan tinker --execute="echo DB::table('jobs')->count();" 2>/dev/null | tail -1 || echo "0")
ok "Pending jobs: ${PENDING_JOBS}"

echo "--- 7. HTTP response"
APP_URL=$(grep "^APP_URL=" .env | cut -d'=' -f2- | tr -d '"')
if curl -sf -o /dev/null -w "%{http_code}" "${APP_URL}/up" | grep -q "200"; then
    ok "HTTP ${APP_URL}/up returns 200"
else
    fail "HTTP ${APP_URL}/up not returning 200"
fi

echo "--- 8. SSL certificate"
DOMAIN=$(echo "${APP_URL}" | sed 's|https://||;s|http://||;s|/.*||')
DAYS_LEFT=$(echo | openssl s_client -servername "${DOMAIN}" -connect "${DOMAIN}:443" 2>/dev/null | openssl x509 -noout -enddate 2>/dev/null | cut -d= -f2 | xargs -I{} date -d {} +%s 2>/dev/null | awk -v now=$(date +%s) '{print int(($1-now)/86400)}' || echo "unknown")
if [ "${DAYS_LEFT}" = "unknown" ]; then
    fail "SSL certificate check failed"
elif [ "${DAYS_LEFT}" -lt 15 ]; then
    fail "SSL expires in ${DAYS_LEFT} days — renew now"
else
    ok "SSL valid for ${DAYS_LEFT} days"
fi

echo ""
if [ "${FAILED}" -eq 0 ]; then
    echo "==> All checks passed ✅"
    exit 0
else
    echo "==> ${FAILED} check(s) failed ❌"
    exit 1
fi