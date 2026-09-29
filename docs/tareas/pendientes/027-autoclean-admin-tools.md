---
agente: codex
estado: pendiente
rama:
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
