# SEG-042 — PDF de mascota sin autenticación y `empresas.pdf` duplicada (URGENTE)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/pdf-sin-auth`
Origen: `docs/auditorias/seg039b-ampliacion-rutas.md`.

## 1. `/pdf/mascota` (prioridad 1) — corregido
Problema: `GET /pdf/mascota` generaba el PDF de la mascota con **id fijo 5** sin ninguna autenticación; el PDF incluye nombre, email y teléfono del propietario, además de datos de salud de la mascota.
Corrección:
- Ruta en el grupo `reportes` con `auth + verified` (`routes/web.php`); ahora `/pdf/mascota/{mascota?}` (el id sale de la URL, no está fijo). El nombre `pdf.mascota` se conserva, así que el enlace del sidebar de Superadmin sigue generando URL; sin id redirige a `mascotas.index` con un aviso.
- `PDFController::generarPDFMascota(?Mascota $mascota)` aplica `authorize('view', $mascota)` → **MascotaPolicy** (SEG-016): dueño (Cliente), Admin y Superadmin; Paseador y otros Clientes reciben 403; id inexistente → 404.
- `/pdf` (PDF de demostración) queda también tras `auth + verified` por estar en el mismo grupo.
- Bug adicional: el controlador cargaba la relación `barrio`, que `Mascota` no tiene, así que **el PDF nunca se generaba** (500). Se quitó del `load`; la vista ya tolera su ausencia (`$mascota->barrio->nombre ?? 'No especificado'`).
Verificación (pruebas): invitado → login en `/pdf/mascota/{id}`, `/pdf/mascota?mascota=`, `/pdf/mascota` y `/pdf`, sin que aparezca el email del dueño; dueño, Admin y Superadmin reciben `application/pdf`; otro Cliente y Paseador 403; sin verificar → `verification.notice`.

## 2. `empresas.pdf` duplicada (prioridad 2) — corregido
Había dos registros de `empresas/{empresa}/pdf`; el segundo (suelto, sin grupo) ganaba y solo exigía `auth` (middleware del controlador): cualquier usuario autenticado, incluso Cliente o sin verificar, podía descargar el PDF de cualquier empresa por id (NIT, representante, teléfono, email).
Corrección: se eliminó el registro suelto; queda solo el del grupo de `empresas` con `auth + verified + role:Superadmin|Admin + módulo empresas` (tarea 041). Las empresas no tienen "dueño" (son un catálogo administrado), así que el control es por rol administrativo, igual que el resto del recurso.
Verificación: una prueba comprueba que la URI se registra una sola vez y con esos middleware; invitado → login, Cliente/Paseador 403, sin verificar → `verification.notice`, Admin descarga el PDF.

## Pruebas
`tests/Feature/PdfAccessTest.php` (8 casos). Primer commit: 8 fallos. Suite final: **266 passed**.

## Pendientes
- Resto de P1/P2 de seg039b (APIs de barrios, `notificaciones/leidas`, `ciudades-api`, `/dashboard`).
- La imagen por defecto del PDF usa una ruta fija bajo `public/avatars/1110456003/...` (parece un dato personal en el repo); no se tocó.
- Sin prueba manual en navegador.

## Decisión humana confirmada (2026-10-03)
El control de acceso a empresas (incluido empresas.pdf) queda por rol
(Superadmin y Admin), no por propietario individual. Empresas es un
catálogo administrado centralmente, sin un "dueño" individual como sí
lo tienen las mascotas. Implementación ya correcta desde la tarea 042,
sin cambios necesarios.
