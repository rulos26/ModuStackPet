---
agente: cursor
estado: pendiente
rama:
archivos: [config/cache.php]
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
