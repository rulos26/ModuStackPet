---
agente: codex
estado: terminada
rama: ia/codex/autoclean-admin-tools
archivos: [app/Http/Controllers/CleanController.php, tests/Feature/AdminWebToolsTest.php]
---

# U-02: Aplicar EnsureAdminToolsEnabled a CleanController (AutoClean)

Ver docs/auditorias/informe-unificado-y-plan.md (U-02, SEG023-002).
CleanController ya está detrás de auth + role:Superadmin + gate de módulo
'clean', pero le falta la misma capa que ya tienen los otros 5 controladores
administrativos (tarea 009/009b): EnsureAdminToolsEnabled.

## Qué hacer
1. Pruebas primero (commit que falle): añade a AdminWebToolsTest.php los
   mismos casos que ya existen para los otros 5 controladores, aplicados a
   CleanController (404 fuera de local, 200 en local).
2. Aplica EnsureAdminToolsEnabled a CleanController, igual patrón que en
   los otros 5 (tarea 009b).
3. Ejecuta php artisan test: las 144 + las nuevas deben pasar.

## Entregable
docs/auditorias/seg027-autoclean.md.

## Restricciones
- No toques routes/web.php ni otros controladores.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se añadió primero una prueba roja que demostró que AutoClean
  respondía 200 con herramientas desactivadas. Después se aplicó
  `EnsureAdminToolsEnabled` a `CleanController`; ahora devuelve 404 cuando está
  deshabilitado y 200 cuando está habilitado.
- Archivos modificados: `app/Http/Controllers/CleanController.php`,
  `tests/Feature/AdminWebToolsTest.php`,
  `docs/auditorias/seg027-autoclean.md` y este archivo de tarea.
- Cómo probarlo: `php artisan test tests/Feature/AdminWebToolsTest.php` — 14
  pruebas y 36 aserciones correctas; `php artisan test` — 146 pruebas y 438
  aserciones correctas; `composer validate` — válido.
- Pendientes y riesgos: ninguno conocido dentro del alcance. Se conservaron las
  defensas existentes de autenticación, rol, módulo y throttling.
- Preguntas para el humano: ninguna.
