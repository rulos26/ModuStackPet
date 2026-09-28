# AGENTS.md — ModuStackPet

Reglas compartidas para todos los agentes de IA (Claude Code, Codex, Cursor).
Si otro archivo de reglas contradice este, manda este.

## Proyecto
- Laravel + PHP + MySQL, entorno local con Docker Compose.
- Verifica versiones reales en `composer.json` / `composer.lock` antes de asumir.
- Documentación en `docs/`. SQL auxiliar en `database/sql/`.

## Git
- Nunca trabajes ni hagas commit directamente en `main`.
- Una rama por tarea: `ia/<agente>/<tarea-corta>` (ej. `ia/codex/fix-rutas`).
- Commits pequeños con Conventional Commits: feat, fix, docs, chore, refactor, test.
- Sin merge, force push ni reescritura de historial sin autorización explícita.

## Seguridad
- Nunca leas, muestres ni subas `.env`. `.env.example` solo lleva placeholders.
- `.env.testing` es la única excepción a la regla de `.env`: se versiona, y
  nunca debe contener secretos.
- Nunca escribas credenciales en código, docs ni commits: usa `env()` / `config()`.
- No elimines archivos: muévelos a `_borrar/` (ignorada por git) para revisión humana.
- Sin comandos destructivos (`migrate:fresh`, `db:wipe`, `DROP`, `rm -rf`) sin autorización.

## Protocolo de tareas entre agentes
Carpetas: `docs/tareas/pendientes/`, `en-curso/`, `terminados/`.
1. Toma una tarea de `pendientes/` solo si `agente:` es tu nombre o `cualquiera`.
2. Muévela a `en-curso/`, completa `rama:` y haz commit + push de ese movimiento
   ANTES de empezar, para que los demás agentes la vean reservada.
3. No modifiques archivos listados en `archivos:` de otra tarea en curso.
4. Al terminar, agrega la sección Handoff y mueve la tarea a `terminados/`.

Encabezado de cada tarea:
    ---
    agente: claude | codex | cursor | cualquiera
    estado: pendiente
    rama:
    archivos: []
    ---

Sección Handoff (obligatoria al terminar):
    ## Handoff
    - Agente y fecha:
    - Qué se hizo:
    - Archivos modificados:
    - Cómo probarlo:
    - Pendientes y riesgos:
    - Preguntas para el humano:

## Antes de dar una tarea por terminada
- `php artisan test` y `composer validate` si es posible ejecutarlos.
- Si no pudiste ejecutar algo, dilo en el Handoff. No afirmes que algo pasó sin verlo.

## Forma de trabajar
- Responde en español. Cambios mínimos y dentro del alcance; no refactorices de más.
- Ante ambigüedad, pregunta antes de asumir.

## Lecciones aprendidas
- Al verificar hallazgos, distingue el estado actual del historial de git.
  Un secreto borrado hoy sigue expuesto en commits anteriores: repórtalo como
  "corregido en HEAD, expuesto en historial", nunca como "refutado".
- La carpeta del proyecto puede estar compartida con otros agentes. Si cambias
  de rama, al terminar vuelve a `main` y deja el árbol de trabajo limpio.
  Si trabajas en paralelo con otro agente, usa un `git worktree` propio.
- Guarda todos los archivos en UTF-8 SIN BOM. Un BOM en `composer.json`
  (commit 364f9400) rompió `composer install` en todos los entornos.
  En Windows PowerShell 5, `Set-Content`/`Out-File -Encoding UTF8` añaden BOM:
  usa `[IO.File]::WriteAllText()` o PowerShell 7.
- Si una herramienta oficial existe (`composer validate`, `composer audit`,
  `php artisan test`), ejecútala en lugar de revisar los archivos a mano.
- NUNCA crees, copies, modifiques ni sobrescribas `.env`, ni ejecutes
  `php artisan key:generate` fuera de `phpunit.xml`. Contiene credenciales
  locales sin historial: si se pierde, no se recupera. Las pruebas usan
  SQLite en memoria y una APP_KEY propia definidas en `phpunit.xml`.
  Si algo parece requerir `.env`, detente y pregunta al humano.
- SemVer en 0.x: mientras un paquete esté en 0.x, un cambio del segundo número
  (0.12 -> 0.14) cuenta como salto MAYOR. Compáralo así al verificar versiones.
- Windows: no uses `php -r` con varias líneas (el puente .bat rompe los
  argumentos); escribe el script en `_borrar/` y ejecútalo. En comandos que pasen
  por cmd.exe (por ejemplo `shell_exec`), usa `commit~1` en lugar de `commit^1`:
  cmd trata `^` como carácter de escape.
- Si una prueba contradice el comportamiento del código, NO reescribas la prueba
  para que pase. Revisa el historial (`git log -S`) para saber si el cambio de
  comportamiento fue intencional y repórtalo en el Handoff; la decisión es humana.
- Nunca uses `git add -A` ni `git add .`: agrega los archivos por nombre.
  Nunca versiones bases de datos (.sqlite ni archivos binarios de datos).

## Worktrees (una carpeta por agente)
- Claude Code trabaja SOLO en `../ModuStackPet-claude`. Codex trabaja SOLO en
  `../ModuStackPet-codex`. La carpeta `ModuStackPet` es del humano y de Cursor.
- Antes de tomar una tarea: `git fetch` y `git switch --detach origin/main`;
  luego crea tu rama `ia/<agente>/<tarea>`.
- Al terminar, vuelve con `git switch --detach origin/main`. No uses
  `git switch main`: `main` está abierta en la carpeta del humano y git no
  permite la misma rama en dos worktrees.
- Nunca ejecutes `git worktree remove` ni `git worktree prune`.
- Las pruebas no necesitan `.env`: usa `php artisan test` directamente.
- Si no puedes ejecutar las pruebas, NO des la tarea por terminada: déjala en
  en-curso/ con estado bloqueada y explica el motivo en el Handoff.
