---
agente: codex
estado: terminado
rama: ia/codex/modulos-fail-closed-paseador
archivos: [app/Http/Middleware/CheckModuleStatus.php, app/Models/Paseador.php, tests/]
---

# SEG-005: CheckModuleStatus debe negar el acceso ante errores + bug de Paseador

## Parte A: fail-closed (decisión humana)
Hoy, si falta la tabla `modules` o falla una consulta, el middleware PERMITE
el acceso. Debe devolver 503 y registrar el error sin datos sensibles.
Se MANTIENE el auto-registro de módulos desconocidos (intencional, commit 43b95d07).
1. Pruebas primero (commit que FALLE): simula el fallo de BD y exige 503.
2. Corrección en CheckModuleStatus.php.
3. Revisa CheckModuleStatusMiddlewareTest: si alguna prueba asumía el
   comportamiento permisivo, repórtalo antes de cambiarla.

## Parte B: modelo Paseador
Crear un usuario Paseador falla con "no such table: paseadors" (la migración
crea `paseadores`). Prueba que FALLE primero y luego añade
`protected $table = 'paseadores';`. Revisa si otros modelos tienen el mismo
problema de pluralización y repórtalos.

## Entregable
`docs/auditorias/seg013-modulos-y-paseador.md`.

## Restricciones
- No toques rutas, `.env` ni dependencias.
- Todas las pruebas (110 y las nuevas) deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se añadieron primero tres pruebas rojas; dos exigen 503 ante tabla `modules` ausente o fallo de consulta y una reproduce el acceso erróneo a `paseadors`. El middleware ahora falla cerrado, evita registrar el mensaje sensible de excepciones y conserva el auto-registro de módulos desconocidos. `Paseador` declara la tabla `paseadores`. Se revisaron otros modelos y se documentaron duplicados, sin ampliar el alcance.
- Archivos modificados: `app/Http/Middleware/CheckModuleStatus.php`, `app/Models/Paseador.php`, `tests/Unit/CheckModuleStatusMiddlewareTest.php`, `tests/Unit/PaseadorTest.php`, `docs/auditorias/seg013-modulos-y-paseador.md` y esta ficha.
- Cómo probarlo: `php artisan test` (113 pruebas, 260 aserciones, todas pasan); `composer validate` (`composer.json` válido). Prueba focal: `php artisan test tests/Unit/CheckModuleStatusMiddlewareTest.php tests/Unit/PaseadorTest.php` (8 pruebas, 17 aserciones).
- Pendientes y riesgos: una indisponibilidad transitoria de la tabla o conexión de módulos ahora responde 503 deliberadamente. Existen pares de modelos para la misma tabla (`Ciudad`/`Ciudade`, `Sector`/`Sectore`, `TipoEmpresa`/`TiposEmpresa`) que conviene consolidar en otra tarea; no se confirmó otro fallo de pluralización.
- Preguntas para el humano: ¿se crea una tarea separada para consolidar los modelos duplicados y reducir el riesgo de divergencia?
