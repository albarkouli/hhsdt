#!/usr/bin/env bash
# ============================================================
# Libya Marketplace — Backup Script
# ============================================================
# Backs up: PostgreSQL database + uploaded files (storage/app/public)
# Retains: 30 daily backups, 12 weekly
# Schedule: cron (daily at 03:00)
#   0 3 * * * /home/libya/libya-marketplace/scripts/backup.sh >> /var/log/libya-backup.log 2>&1
# ============================================================

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="${APP_DIR}/storage/backups"
DATE="$(date +%Y%m%d_%H%M%S)"
RETENTION_DAYS=30

mkdir -p "${BACKUP_DIR}"

# Load DB credentials from .env
if [ -f "${APP_DIR}/.env" ]; then
    export $(grep -E '^(DB_DATABASE|DB_USERNAME|DB_PASSWORD|DB_HOST|DB_PORT)=' "${APP_DIR}/.env" | xargs)
fi

DB_FILE="${BACKUP_DIR}/db_${DATE}.sql.gz"
STORAGE_FILE="${BACKUP_DIR}/storage_${DATE}.tar.gz"

echo "[$(date)] Starting backup..."
echo "  Database: ${DB_DATABASE}"

# === Database backup ===
PGPASSWORD="${DB_PASSWORD}" pg_dump \
    -h "${DB_HOST:-127.0.0.1}" \
    -p "${DB_PORT:-5432}" \
    -U "${DB_USERNAME}" \
    -d "${DB_DATABASE}" \
    --no-owner --no-acl --clean --if-exists \
    | gzip > "${DB_FILE}"

echo "  DB backup: $(du -h "${DB_FILE}" | cut -f1)"

# === Storage backup ===
if [ -d "${APP_DIR}/storage/app/public" ]; then
    tar -czf "${STORAGE_FILE}" \
        -C "${APP_DIR}/storage/app/public" \
        . 2>/dev/null || true
    echo "  Storage backup: $(du -h "${STORAGE_FILE}" | cut -f1)"
fi

# === Cleanup old backups ===
find "${BACKUP_DIR}" -name "db_*.sql.gz" -mtime +${RETENTION_DAYS} -delete
find "${BACKUP_DIR}" -name "storage_*.tar.gz" -mtime +${RETENTION_DAYS} -delete

echo "[$(date)] Backup complete. Retained last ${RETENTION_DAYS} days."
echo ""