---
agente: claude
estado: terminado
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

## Handoff
- Agente y fecha: claude, 2026-09-28.
- Qué se hizo: se creó `ia/claude/corregir-009` desde
  `origin/ia/codex/desactivar-herramientas-web-produccion` y se integró
  `origin/main` con `merge` (sin conflictos; trajo las tareas 008 y 010).
  Causa del error confirmada: `HasMiddleware` exige un método `middleware()`
  **estático**, pero `Illuminate\Routing\Controller` (base del proyecto) ya
  declara uno de **instancia**, y PHP no permite ese choque
  ("Cannot make non static method ... static"). Se quitó `HasMiddleware`
  de los 5 controladores y se registró `EnsureAdminToolsEnabled` (y
  `auth`/`verified` donde correspondía) con `$this->middleware(...)` en
  el constructor de cada uno. Se revisó `AdminWebToolsTest.php`: está
  bien planteado, no hizo falta corregir ninguna aserción. Con
  autorización explícita del humano (ver más abajo), se quitó
  temporalmente el middleware de `DatabaseConfigController`, se confirmó
  que exactamente las 2 pruebas que dependen de ese controlador fallan
  (404 esperado vs 200/302 recibido), y se restauró de inmediato. Detalle
  completo añadido como nueva sección en
  `docs/auditorias/seg009-herramientas-web.md` (se conservó el contenido
  original de Codex).
- Archivos modificados: `app/Http/Controllers/MigrationController.php`,
  `app/Http/Controllers/SeederController.php`,
  `app/Http/Controllers/Superadmin/BackupConfigController.php`,
  `app/Http/Controllers/Superadmin/DatabaseConfigController.php`,
  `app/Http/Controllers/Superadmin/EmailConfigController.php`,
  `docs/auditorias/seg009-herramientas-web.md` (sección añadida).
  `tests/Feature/AdminWebToolsTest.php` no se modificó.
- Cómo probarlo: `php artisan test` → 80 passed (191 assertions): 68
  previas (tareas 008 y 010) + 12 de `AdminWebToolsTest`, que antes no
  podían ejecutarse por el error fatal. `composer validate` → válido.
- Pendientes y riesgos: ninguno detectado en el alcance de esta tarea.
  Nota de proceso: durante el punto 3, el clasificador de seguridad del
  modo automático bloqueó la primera edición (quitar temporalmente el
  middleware) por tratarse de una "Security Weaken"; se pausó y se pidió
  autorización explícita al humano antes de continuar, que la concedió.
  El cambio se restauró de inmediato y no quedó en ningún commit.
- Preguntas para el humano: ninguna adicional a las ya planteadas en el
  Handoff de la tarea 010 (eliminar `Kernel.php`, registrar
  `AuthServiceProvider`, priorizar los grupos de rutas sin rol).
