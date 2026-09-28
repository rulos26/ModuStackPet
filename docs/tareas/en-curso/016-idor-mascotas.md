---
agente: claude
estado: en-curso
rama: ia/claude/idor-mascotas
archivos: [routes/web.php, app/Http/Controllers/MascotaController.php, app/Policies/MascotaPolicy.php, tests/Feature/]
---

# IDOR en mascotas (defecto 1, crítico) y errores expuestos (defecto 4)

Ver docs/auditorias/pruebas-mascotas-documentos.md.

## Reglas de acceso (decisión humana)
- Cliente: solo sus propias mascotas.
- Admin y Superadmin: todas las mascotas.
- Paseador: ninguna (por ahora).
- Nadie cambia el dueño (`user_id`) al editar, tampoco Admin ni Superadmin.
- Invitado: redirección a login.

## Qué hacer
1. Pruebas primero (commit que FALLE), partiendo de la reproducción del informe:
   invitado, Cliente ajeno, Paseador, Admin y Superadmin en show, edit, update
   y destroy; comprobar que el dueño no cambia y que nada se borra sin permiso.
2. Corrección: `auth` y `verified` en el grupo de rutas de mascotas;
   `MascotaPolicy` aplicada en todas las acciones sobre una mascota concreta;
   `update` conserva el `user_id` original.
3. Defecto 4 solo en MascotaController: registrar el error internamente y
   mostrar al usuario un mensaje genérico, sin `$e->getMessage()`.
4. Comprueba si las rutas de documentos de mascotas quedan dentro del grupo
   protegido e infórmalo.

## Entregable
`docs/auditorias/seg016-idor-mascotas.md`.

## Restricciones
- No toques MascotaDocumentController (lo corrige Codex en la tarea 017).
- No modifiques las pruebas de Codex salvo que la corrección lo exija; si lo haces, explícalo.
- No toques `.env` ni dependencias. Todas las pruebas deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.
