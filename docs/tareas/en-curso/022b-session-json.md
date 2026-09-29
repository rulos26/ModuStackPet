---
agente: claude
estado: en-curso
rama: ia/claude/fase3-laravel-13
archivos: [config/session.php, tests/]
---

# Ajuste a la 022: activar session.serialization = json

Decisión humana aprobada: adoptar ahora `session.serialization = json`
(Laravel 13), ya que el proyecto aún no está en producción y no hay
sesiones reales que perder.

## Qué hacer
1. En la misma rama `ia/claude/fase3-laravel-13`, configura
   `session.serialization` en `json` en config/session.php (o vía .env si
   la guía oficial lo recomienda así).
2. Ejecuta la suite completa: las 144 deben seguir pasando.
3. Si alguna prueba dependía de la serialización PHP de sesión de forma
   implícita, ajústala y explica el porqué en el entregable.

## Entregable
Añade una sección a docs/auditorias/fase3-laravel13.md documentando el
cambio y confirmando el resultado de las pruebas.

## Restricciones
- No toques nada más de lo ya aplicado en la tarea 022.
- Al terminar, vuelve con `git switch --detach origin/main`.
