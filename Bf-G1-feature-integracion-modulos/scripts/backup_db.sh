#!/usr/bin/env bash
set -euo pipefail

: "${PGHOST:=localhost}"
: "${PGPORT:=5432}"
: "${PGUSER:=postgres}"
: "${PGDATABASE:=bruce_fire}"
: "${BACKUP_DIR:=$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)/backups}"

mkdir -p "$BACKUP_DIR"

if ! command -v pg_dump >/dev/null 2>&1; then
  echo "Error: pg_dump is not installed or is not available in PATH." >&2
  exit 1
fi

stamp="$(date '+%Y-%m-%d_%H%M%S')"
output_file="$BACKUP_DIR/bruce_fire_${stamp}.dump"

echo "Creating backup for ${PGDATABASE} at ${output_file}"
pg_dump -h "$PGHOST" -p "$PGPORT" -U "$PGUSER" -d "$PGDATABASE" -Fc -f "$output_file"
echo "Backup created successfully: ${output_file}"
