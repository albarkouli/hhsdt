#!/usr/bin/env bash
# ============================================================
# Libya Marketplace — Deploy Script
# ============================================================
# Runs on the production server after `git pull`.
# Usage: ./scripts/deploy.sh
# ============================================================

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "${APP_DIR}"

echo "==> Deploying Libya Marketplace"
echo "    Directory: ${APP_DIR}"
echo "    Branch:    $(git rev-parse --abbrev-ref HEAD)"
echo "    Commit:    $(git rev-parse --short HEAD)"
echo ""

# === Pre-checks ===
if [ ! -f ".env" ]; then
    echo "ERROR: .env file not found. Copy .env.production.example to .env first."
    exit 1
fi

if ! grep -q "APP_ENV=production" .env; then
    echo "ERROR: APP_ENV is not 'production' in .env"
    exit 1
fi

# === Maintenance mode ===
echo "==> Enabling maintenance mode"
php artisan down --render="errors::503" --retry=60 || true

# === Dependencies ===
echo "==> Installing composer dependencies"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# === Frontend build ===
echo "==> Building frontend assets"
if [ -f "package.json" ]; then
    npm ci --no-audit --no-fund
    npm run build
fi

# === Database migrations ===
echo "==> Running migrations"
php artisan migrate --force

# === Storage link ===
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

# === Optimization ===
echo "==> Optimizing"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# === Queue restart (signals workers to reload code) ===
echo "==> Restarting queue workers"
php artisan queue:restart

# === Bring back online ===
echo "==> Disabling maintenance mode"
php artisan up

echo ""
echo "==> Deploy complete!"
echo "    Commit: $(git rev-parse --short HEAD)"
echo "    Time:   $(date)"