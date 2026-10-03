# SEG-040 — Escritura anónima en 7 recursos de catálogo (P0)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/escritura-anonima`
Origen: `docs/auditorias/seg039-rutas-sin-auth.md` (rama `ia/cursor/auditoria-rutas-sin-auth`).

## Qué se protegió (en `routes/web.php`, a nivel de ruta)
Middleware nuevo: `auth` + `verified` + `role:...` delante de `CheckModuleStatus` (que no exige sesión).

| Recurso | Rol permitido | Criterio |
|---|---|---|
| `paths-documentos` (incl. toggle y rutas duplicadas) | Superadmin, Admin | enlazado en ambos sidebars |
| `tipos-empresas` | Superadmin, Admin | enlazado en ambos sidebars |
| `tipo-documentos` | Superadmin, Admin | enlazado en ambos sidebars |
| `sectores` | Superadmin, Admin | enlazado en ambos sidebars |
| `razas` | Superadmin | solo en el sidebar de Superadmin |
| `barrios` | Superadmin | solo en el sidebar de Superadmin |
| `mensaje-de-bienvenidas` | Superadmin | solo en el sidebar de Superadmin |

Criterio: el mismo de 034b (quién ve el recurso en el menú). Cambiar de rol es editar el middleware del grupo.
`tipos-empresas` estaba dentro del grupo `:empresas`; se separó en su propio grupo con el mismo slug de módulo. Las rutas `empresas` quedaron **intactas** (tarea 041).

## Confirmación sobre `paths-documentos`
Un invitado recibe redirección a `login` en `index`, `create`, `store`, `show`, `edit`, `update`, `destroy` y `toggle-status`. La prueba `paths_documentos_does_not_leak_user_emails_or_cedulas_to_guests` crea un usuario con email y cédula conocidos y confirma que no aparecen en la respuesta anónima. Cliente y Paseador reciben 403 (o 404 si el id no existe; ver abajo).

## Pruebas
`tests/Feature/EscrituraAnonimaTest.php` (38 casos): para cada uno de los 7 recursos, invitado → login en las 7 acciones; Cliente/Paseador bloqueados; usuario sin verificar → `verification.notice`; Superadmin abre el índice; Admin abre los 4 permitidos y recibe 403 en los 3 solo-Superadmin; un invitado no puede crear ni borrar una raza real (BD intacta). Primer commit: 27 fallos de 38. Suite final: **234 passed** (196 previas + 38 nuevas; los 6 casos de rol dependen del cambio de rutas).

## No tocado / pendientes
- `departamentos`, `empresas`, `vacunas_certificaciones`: tarea 041.
- Con un id inexistente, `SubstituteBindings` corre antes del middleware `role` y un usuario autenticado sin rol ve 404 en vez de 403 (no ejecuta nada; fuga mínima de existencia). Se arreglaría con `$middleware->priority`; no se hizo.
- `paths-documentos`: el parámetro de ruta (`paths_documento`) no coincide con el type-hint `$pathDocumento`, así que show/edit/update/destroy no enlazan el modelo (mismo patrón que `ciudade` en 034b). No es un hueco de acceso; sin corregir aquí.
- P1/P2 de seg039 (`/pdf*`, APIs de barrios, `notificaciones/leidas`, duplicados) siguen pendientes.
- Sin prueba manual en navegador.
