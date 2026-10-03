---
agente: cursor
estado: terminado
rama: ia/cursor/ci-github-actions
archivos: [.github/workflows/tests.yml, docs/auditorias/informe-unificado-y-plan.md]
---

# U-03: Workflow mínimo de GitHub Actions

Ver docs/auditorias/informe-unificado-y-plan.md (U-03, C-010). Tres
auditorías independientes en fechas distintas señalaron la ausencia de CI.

## Qué hacer
1. Crea .github/workflows/tests.yml (nuevo archivo) que en cada push y
   pull request a main:
   - Instale PHP 8.3 con extensiones necesarias (revisa composer.json
     "require" para confirmar cuáles).
   - Ejecute composer install --no-interaction.
   - Copie .env.testing si hace falta, o confirma que phpunit.xml ya trae
     todo lo necesario (APP_KEY, SQLite en memoria) sin necesitar .env.
   - Ejecute php artisan test.
   - Ejecute composer validate.
2. Verifica LOCALMENTE que los mismos comandos exactos del workflow
   funcionan en tu worktree antes de darlo por terminado (no se puede
   ejecutar GitHub Actions desde aquí, pero sí reproducir cada paso).
3. Usa una acción oficial y actual para PHP (busca cuál es la recomendada
   actualmente para Laravel/PHP en GitHub Actions).

## Entregable
Añade una sección a docs/auditorias/informe-unificado-y-plan.md
confirmando el workflow creado y los comandos verificados localmente.

## Restricciones
- Solo crea el archivo .github/workflows/tests.yml. No toques nada más.
- No agregues secrets ni credenciales al workflow.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Cursor, 2026-10-03
- Qué se hizo: creado `.github/workflows/tests.yml` (PHP 8.3 vía
  `shivammathur/setup-php@v2`, `composer install --no-interaction`,
  `php artisan test`, `composer validate` en push/PR a `main`). Confirmado
  que `phpunit.xml` basta sin `.env`. Documentado en §10 del informe
  unificado.
- Archivos modificados:
  - `.github/workflows/tests.yml` (nuevo)
  - `docs/auditorias/informe-unificado-y-plan.md` (sección 10 al final)
  - `docs/tareas/terminados/028-ci-github-actions.md`
- Cómo probarlo: mergear/abrir PR a `main` y revisar el check "tests";
  en local: `composer install --no-interaction`, `php artisan test`,
  `composer validate`.
- Verificación ejecutada (local, mismos comandos del workflow):
  - `composer install --no-interaction` → OK
  - `php artisan test` → 146 passed (438 assertions)
  - `composer validate` → `./composer.json is valid`
- Pendientes y riesgos: el primer run real ocurre al mergear este PR a
  `main` (o abrir PR); no se pudo ejecutar Actions desde el worktree.
- Preguntas para el humano: ninguna.
