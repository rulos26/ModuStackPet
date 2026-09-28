---
agente: claude
estado: en-curso
rama: ia/claude/corregir-009
archivos: [app/Http/Controllers/MigrationController.php, app/Http/Controllers/SeederController.php, app/Http/Controllers/Superadmin/BackupConfigController.php, app/Http/Controllers/Superadmin/DatabaseConfigController.php, app/Http/Controllers/Superadmin/EmailConfigController.php, tests/Feature/AdminWebToolsTest.php, docs/auditorias/seg009-herramientas-web.md]
---

# Corregir la tarea 009 de Codex (error fatal, nunca se ejecutaron sus pruebas)

La rama `origin/ia/codex/desactivar-herramientas-web-produccion` provoca:
"Cannot make non static method Illuminate\Routing\Controller::middleware() static".
El controlador base hereda de Illuminate\Routing\Controller, así que la interfaz
HasMiddleware no es compatible en este proyecto.

## Qué hacer
1. Crea `ia/claude/corregir-009` desde la rama de Codex e integra `origin/main`
   (merge, no rebase), para incluir las tareas 008 y 010.
2. En los cinco controladores, elimina `HasMiddleware` y aplica
   `EnsureAdminToolsEnabled` con `$this->middleware(...)` en el constructor.
   No cambies el controlador base.
3. Revisa `tests/Feature/AdminWebToolsTest.php`: nunca se ha ejecutado.
   Comprueba que verifica lo que dice. Quita temporalmente el middleware de un
   controlador y confirma que sus pruebas FALLAN; luego restáuralo.
4. `php artisan test`: todas deben pasar (las 68 actuales y las de Codex).

## Entregable
Añade a `docs/auditorias/seg009-herramientas-web.md` una sección con la causa del
error, la corrección, la verificación del punto 3 y el resultado final.

## Restricciones
- No toques routes/web.php, bootstrap/app.php, `.env` ni dependencias.
- No debilites las pruebas de Codex: si alguna está mal, corrígela y explica por qué.
- Al terminar, vuelve con `git switch --detach origin/main`.
