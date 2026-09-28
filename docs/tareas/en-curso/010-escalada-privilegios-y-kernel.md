---
agente: claude
estado: en-curso
rama: ia/claude/escalada-privilegios-kernel
archivos: [routes/web.php, bootstrap/app.php, app/Http/Kernel.php, app/Http/Controllers/, app/Policies/, tests/Feature/]
---

# Escalada de privilegios en /usuarios/roles y Kernel.php sin efecto

## Parte A (URGENTE): /usuarios/roles
Según docs/auditorias/seg001-correccion.md, cualquier usuario autenticado puede
asignarse roles, incluido Superadmin.
1. Commit de pruebas que FALLEN: Admin, Cliente y Paseador no pueden asignar
   ni quitar roles (403) y su rol no cambia en la BD. Tampoco pueden
   asignárselos a sí mismos.
2. Commit de corrección: exigir rol Superadmin en la ruta Y autorización en el
   controlador (policy o Gate), como defensa en profundidad.

## Parte B: app/Http/Kernel.php no se usa (Laravel 11/12)
1. Lista TODO lo que declara Kernel.php (middleware globales, grupos, alias,
   prioridades) y compáralo con bootstrap/app.php.
2. Registra en bootstrap/app.php lo que falte y afecte a seguridad (alias,
   throttle, middleware globales). Añade pruebas cuando sea posible.
3. NO borres Kernel.php: propón su eliminación en el Handoff.

## Parte C (solo reportar)
Para cada grupo de rutas sin rol listado en "Además" de seg001-correccion.md,
propone qué rol debería exigir.

## Entregable
`docs/auditorias/seg010-escalada-y-kernel.md`: pruebas que fallaban, cambios
aplicados, tabla Kernel.php vs bootstrap/app.php y propuestas de la parte C.

## Restricciones
- En controladores, solo cambios de autorización. No toques `.env` ni dependencias.
- No desactives ni debilites pruebas. Las 56 actuales deben seguir pasando.
- Al terminar, vuelve con `git switch --detach origin/main` (AGENTS.md).
