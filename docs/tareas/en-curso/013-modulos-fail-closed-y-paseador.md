---
agente: codex
estado: en-curso
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
