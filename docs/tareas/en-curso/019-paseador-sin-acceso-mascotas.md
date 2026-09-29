---
agente: claude
estado: en-curso
rama: ia/claude/paseador-sin-mascotas
archivos: [app/Policies/MascotaPolicy.php, routes/web.php, tests/Feature/MascotaAccessControlTest.php]
---

# Paseador sin acceso a mascotas (store e index incluidos)

Decisión humana: Paseador no debe poder crear (store) ni listar (index)
mascotas, igual que ya no puede ver ni editar (tarea 016). Solo Cliente
(las suyas) y roles altos —Admin y Superadmin— (todas) tienen acceso.

## Qué hacer
1. Pruebas primero (commit que FALLE): Paseador recibe 403 en store e index
   de mascotas; la BD no cambia si lo intenta.
2. Corrección en MascotaPolicy (viewAny/create) y donde haga falta.
3. Confirma que Cliente e roles altos no se vean afectados: las 142 pruebas
   actuales deben seguir pasando.

## Entregable
Añade una sección a docs/auditorias/seg016-idor-mascotas.md con el cambio.

## Restricciones
- No toques rutas de documentos ni MascotaDocumentController.
- No toques `.env` ni dependencias. Todas las pruebas deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.
