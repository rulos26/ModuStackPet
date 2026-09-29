#!/usr/bin/env bash
set -euo pipefail
RAMA="$1"
DIR="$2"
cd "$DIR"
echo "== Verificando $RAMA en $DIR =="
git fetch
git switch --detach "origin/$RAMA"
echo "-- Commits nuevos --"
git --no-pager log --oneline origin/main..HEAD
echo "-- Archivos cambiados --"
git --no-pager diff --stat origin/main...HEAD
echo "-- Suite completa --"
RESULT_FULL=$(php artisan test 2>&1 | tail -3)
echo "$RESULT_FULL"
HASH_TEST=$(git log origin/main..HEAD --oneline --grep='^test' -i | tail -1 | cut -d' ' -f1)
if [ -n "$HASH_TEST" ]; then
  git switch --detach "$HASH_TEST"
  echo "-- Pruebas en el commit rojo ($HASH_TEST) --"
  RESULT_RED=$(php artisan test 2>&1 | tail -3)
  echo "$RESULT_RED"
fi
git switch --detach origin/main
if echo "$RESULT_FULL" | grep -q "failed"; then
  echo "VERIFICACION FALLIDA: la suite completa tiene fallos"
  exit 1
fi
if [ -n "${HASH_TEST:-}" ] && ! echo "$RESULT_RED" | grep -q "failed"; then
  echo "VERIFICACION FALLIDA: el commit de pruebas no falla (no demuestra el hueco)"
  exit 1
fi
echo "VERIFICACION OK: $RAMA"
