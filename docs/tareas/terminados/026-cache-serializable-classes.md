---
agente: cursor
estado: terminado
rama: ia/cursor/cache-serializable-classes
archivos: [config/cache.php, docs/auditorias/informe-unificado-y-plan.md]
---

# U-01: Adoptar cache.serializable_classes = false

Laravel 13 añade esta protección contra deserialización insegura en el
driver de caché. Ver docs/auditorias/informe-unificado-y-plan.md (U-01).

## Qué hacer
1. Agrega 'serializable_classes' => false en config/cache.php, siguiendo
   exactamente la sintaxis del skeleton oficial de Laravel 13 (búscalo si
   no lo tienes en contexto).
2. Ejecuta php artisan test: las 144 deben seguir pasando.
3. Si algo en el proyecto depende de serializar objetos completos en caché
   (revisa con grep en app/ por Cache::put con objetos), repórtalo antes
   de aplicar el cambio.

## Entregable
Añade una sección a docs/auditorias/informe-unificado-y-plan.md (al final,
sin modificar lo existente) confirmando el cambio y el resultado.

## Restricciones
- Solo config/cache.php. No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Cursor, 2026-09-28
- Qué se hizo: se revisó que `app/` no cachea objetos PHP (solo escalares
  y arrays de strings); se añadió `'serializable_classes' => false` en
  `config/cache.php` según el skeleton Laravel 13.x; se documentó en
  §9 de `informe-unificado-y-plan.md`.
- Archivos modificados:
  - `config/cache.php`
  - `docs/auditorias/informe-unificado-y-plan.md` (solo sección nueva al final)
  - `docs/tareas/terminados/026-cache-serializable-classes.md`
- Cómo probarlo: `php artisan test`; comprobar
  `config('cache.serializable_classes') === false`.
- Verificación ejecutada: `php artisan test` → 144 passed (436 assertions);
  `composer validate --no-check-publish` → valid.
- Pendientes y riesgos: ninguno detectado; Spatie permission cachea arrays
  planos (ya documentado en fase3), compatible con `false`.
- Preguntas para el humano: ninguna.
