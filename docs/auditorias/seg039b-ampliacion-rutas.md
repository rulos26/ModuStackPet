# SEG-039b: ampliación de auditoría (PDF, barrios, notificaciones, duplicados)

Fecha: 2026-10-03  
Agente: Cursor  
Rama: `ia/cursor/ampliar-auditoria-rutas-039b`  
Base revisada: `routes/web.php` en HEAD del worktree (post tareas 040/041 en main local).  
Continúa: `docs/auditorias/seg039-rutas-sin-auth.md` (rama `ia/cursor/auditoria-rutas-sin-auth`, aún no mergeada a `origin/main`).  
Alcance: **solo lectura** (rutas, controladores, vistas PDF, `php artisan route:list --json`).

## Contexto tras 040/041

Los siete catálogos P0 de seg039 ya llevan `auth + verified + role` en ruta (040). `departamentos`, `empresas` (resource) y `vacunas_certificaciones` también tienen `auth + verified` en ruta (041). Este informe detalla los **P1/P2** que seg039 solo listó.

`CheckModuleStatus` sigue sin sustituir `auth`: un invitado puede acceder si el módulo está activo y la ruta no exige sesión.

---

## Tabla principal (ordenada por severidad)

| Grupo / ruta | Líneas `web.php` | Middleware efectivo (`route:list`) | Auth en controlador | Severidad | Qué expone / permite | Prioridad |
|---|---|---|---|---|---|---|
| `GET /pdf/mascota` (`pdf.mascota`) | 167-169 | `web`, `CheckModuleStatus:reportes` | **No** (`PDFController`) | **Alta** | PDF en navegador con **mascota fija `id=5`** (`PDFController.php:20`), relaciones `raza`, `barrio`, **`user`**. La vista incluye **nombre, email y teléfono del propietario**, dirección/interior de la mascota, salud, enfermedades, etc. (`resources/views/pdf/mascotas.blade.php`). Escritura: no. | **P1** |
| `GET empresas/{empresa}/pdf` (`empresas.pdf`) | 193 y **231** (duplicada) | `web`, **`auth`** (sin `verified`, sin `role:Superadmin\|Admin`, sin `:empresas`) | Sí (`EmpresaController` `__construct`, excepto `getCiudades`) | **Alta** | PDF descargable con NIT+DV, representante legal, teléfono, email, dirección, sector, timestamps (`resources/views/empresa/pdf.blade.php`). Cualquier usuario **autenticado** con id de empresa enumerable puede generar el PDF (**IDOR**); no exige rol de catálogo ni email verificado en la ruta que gana el registro. | **P1** |
| `GET /barrios-engativa` (`barrios.engativa`) | 37-49 | `web` | N/A (closure) | **Alta** | JSON `id`, `nombre`, `localidad` de barrios filtrados a Engativá (lectura BD). En 500 devuelve `message` con texto de excepción. Escritura: no. | **P1** |
| `GET /barrios-por-ciudad/{ciudadId}` (`barrios.by-ciudad`) | 52-65 | `web` | N/A (closure) | **Alta** | Mismo payload que la anterior; **`{ciudadId}` se ignora** (siempre Engativá). Mismo riesgo de filtración en errores. | **P1** |
| `POST /notificaciones/leidas` (`notificaciones.marcar.leidas`) | 146-149 | `web` (sin `auth`) | N/A: `auth()->user()->unreadNotifications->markAsRead()` | **Alta** (integridad / disponibilidad) | **No lee** notificaciones ajenas: solo marca como leídas las del usuario de sesión. Invitado: error al resolver `user()` (500 / fallo). Autenticado: mutación de estado **sin middleware `auth`/`verified`**, CSRF sí (form en `navbar.blade.php`). Riesgo: endpoint state-changing mal declarado; posible abuso con sesión válida sin alinear política de verificación. | **P1** |
| `GET /pdf` (`pdf.generar`) | 167-169 | `web`, `CheckModuleStatus:reportes` | **No** | **Media** | PDF de demo (`title` + vista `pdf.ejemplo`); sin datos de BD en el controlador. Confirma que el módulo reportes está activo. | **P2** |
| `GET ciudades-api` (`ciudades.api`) | 196-230 | `web` | N/A | **Media** | JSON con lista **hardcodeada** de 20 municipios + `departamentoId` de query (default `11`), timestamp y metadatos de entorno. No lee tablas `ciudades`/`departamentos`. Patrón análogo a barrios pero datos no sensibles reales. | **P2** |
| `GET /dashboard` (`temp.index`) | 90-92 | `web` | N/A | **Media** | Vista `dashboard` genérica sin login. | **P2** |

### Notas sobre PDF de empresa (duplicado de ruta)

En `web.php` la misma URI y nombre se registran dos veces (grupo con `auth+verified+role+empresas` en 191-193 y línea suelta 231). `php artisan route:list` muestra **una** entrada `empresas/{empresa}/pdf` con middleware **`auth` únicamente** (la definición posterior + middleware del controlador). Conviene **eliminar la línea 231** y dejar solo la ruta dentro del grupo 041 para heredar `verified` y rol.

---

## APIs de barrios vs `ciudades-api`

| Aspecto | Barrios (`/barrios-engativa`, `/barrios-por-ciudad/{ciudadId}`) | Ciudades (`/ciudades-api`) |
|---|---|---|
| Auth | Ninguna | Ninguna |
| Fuente de datos | Modelo `Barrio` (BD) | Array PHP fijo |
| Escritura | No | No |
| Severidad | **Alta** (catálogo geográfico real + posible leak en errores) | **Media** (demo / UX forms) |
| Corrección sugerida | Mover tras `auth` (y rol si aplica) o exponer solo campos mínimos vía endpoint autenticado usado por formularios | Sustituir por consulta autenticada a `Ciudad` o eliminar si obsoleto |

---

## `POST /notificaciones/leidas` (detalle)

- **Comportamiento:** marca todas las notificaciones no leídas del usuario autenticado como leídas y redirige `back()`.
- **Datos de terceros:** no; usa la relación `unreadNotifications` del usuario de sesión.
- **Problema de seguridad:** falta `middleware(['auth', 'verified'])` (y opcionalmente `throttle`) en la ruta; el navbar solo muestra el formulario con sesión, pero la ruta no lo exige explícitamente.
- **Prioridad:** P1 (alinear con el resto de acciones autenticadas).

---

## Rutas duplicadas u homónimas (más allá de users / superadmin.usuarios)

Hallazgos en `routes/web.php` y `route:list --json` (HEAD actual):

| Tipo | Rutas / nombres | Comportamiento | Severidad | Prioridad |
|---|---|---|---|---|
| **Misma URI + mismo nombre** | `empresas/{empresa}/pdf` → `empresas.pdf` (L193 y L231) | Registro doble; gana definición con **menos** middleware en ruta | Alta (véase PDF empresa) | **P1** |
| **Mismo nombre, URI distinta** | `cliente.dashboard`: `GET /clientes/dashboard` (sin auth, L104) vs `GET /cliente/dashboard` (auth, L251) | Dos URLs; `route('cliente.dashboard')` apunta a la **última** registrada (`/cliente/dashboard`). La ruta `/clientes/dashboard` queda huérfana de nombre pero **sigue accesible sin login** si se conoce la URL. | **Media** | **P2** |
| **Mismo nombre, método distinto** | `logout`: `GET /logout` (closure, L116) vs `POST /logout` Fortify | Patrón distinto; GET custom invalida sesión sin auth previo (común pero duplica semántica con Fortify). | Baja | **P3** |
| **Resource + rutas manuales redundantes** | `paths-documentos`: `Route::resource` (L235) + `index`/`create`/`store` repetidos (L236-238) | Misma acción registrada dos veces; middleware idéntico tras 040 | Baja (mantenimiento) | **P3** |
| **Dashboards legacy vs protegidos** | L99-107 (`login_*`) vs L124-126 / prefijos `admin`, `superadmin`, etc. | `superadmin.dashboard` en `route:list` apunta a `SuperadminController@index` bajo prefijo con `auth+verified+role`. Las rutas sueltas L99-107 **compiten por URI** con las protegidas; conviene borrar o proteger las sueltas para evitar sombras. | **Media** | **P2** |
| **Admin dashboard** | L102 `login_Admin` vs L244 `admin/dashboard` | Misma URI `/admin/dashboard`; gana la del grupo `auth` (`AdminController@index`). | Media (código muerto en L102) | **P2** |

*(Par `admin.users.*` / `superadmin.usuarios.*` ya tratado en tarea 020; no re-auditado aquí.)*

---

## Inventario completo de rutas cuyo path contiene `pdf`

| Método | URI | Nombre | Auth efectivo |
|---|---|---|---|
| GET | `/pdf` | `pdf.generar` | No (solo módulo reportes) |
| GET | `/pdf/mascota` | `pdf.mascota` | No (solo módulo reportes) |
| GET | `empresas/{empresa}/pdf` | `empresas.pdf` | Sí (`auth`); sin `verified`/rol en ruta ganadora |

No hay otras rutas `*pdf*` en `routes/web.php` ni en `routes/api.php` (no usado para estos casos).

---

## Recomendaciones (no aplicadas en esta tarea)

1. **P1:** Añadir `auth + verified` (+ autorización por dueño/rol) a `/pdf` y `/pdf/mascota`; parametrizar mascota por id con policy, no `find(5)`.
2. **P1:** Eliminar `Route::get('empresas/{empresa}/pdf', …)` suelta (L231); exigir mismo middleware que el resource `empresas`.
3. **P1:** Proteger APIs de barrios como mínimo con `auth` (+ rol si el catálogo barrios es solo Superadmin).
4. **P1:** Envolver `POST notificaciones/leidas` en `auth` + `verified`.
5. **P2:** Unificar dashboards cliente (`/clientes/` vs `/cliente/`); retirar rutas `login_*` públicas si están obsoletas.
6. **P2/P3:** Limpiar duplicados `paths-documentos` y documentar `ciudades-api` vs API real de ciudades autenticada.

---

## Verificación (auditoría original)

- `php artisan route:list --json` (2026-10-03, worktree cursor): middleware citado arriba.
- No se modificó código de aplicación en la auditoría 039b (solo este markdown).

---

## Correcciones aplicadas (tarea 043 — 2026-10-03)

Rama: `ia/cursor/corregir-hallazgos-039b`. Patrón: pruebas en
`tests/Feature/Seg039bRoutesHardeningTest.php`, luego fix en `routes/web.php`.

| Hallazgo | Decisión | Qué se hizo |
|---|---|---|
| APIs barrios (`/barrios-engativa`, `/barrios-por-ciudad/{ciudadId}`) | **Dejar públicas** | Catálogo geográfico (`id`, `nombre`, `localidad`) sin dato personal. Se usan desde `user.form` (fetch con sesión) para rellenar selects de Engativá; mismo criterio de “público por diseño” que `ciudades-api`. El CRUD de barrios sigue siendo Superadmin. No se cambió código. |
| `POST /notificaciones/leidas` | **Corregido** | Confirmado: **no hay IDOR** — `auth()->user()->unreadNotifications` solo toca notificaciones del usuario de sesión (prueba Alice/Bob). El hueco real era invitado → 500 y falta de `verified`. Se añadió `middleware(['auth', 'verified'])`. |
| Dashboards duplicados / `login_*` legacy | **Corregido** | La app usa `route('cliente.dashboard')` → `/cliente/dashboard` (grupo `auth`). Se eliminaron: `/clientes/dashboard`, registros sueltos `login_Superadmin` / `login_Admin` / `login_Paseador`, y el grupo intermedio `auth+verified` de `/superadmin/dashboard` sin `role` (quedaba sombreando el del prefijo Superadmin). Quedan solo las rutas bajo prefijos con `auth`. |
| `empresas.pdf` duplicada | **Ya resuelto (042)** | Confirmado con prueba: una sola URI con `auth + verified + role:Superadmin\|Admin`. No se tocó. |
| `/pdf` y `/pdf/mascota` | **Ya resuelto (042)** | Fuera del alcance de 043; ver `seg042-pdf-sin-auth.md`. |

### Dejado sin cambio (justificación)

- **`ciudades-api`**: JSON hardcodeado de demo; sin lectura de BD. Misma familia que barrios públicos; limpieza futura opcional (P2/P3).
- **`GET /dashboard` (`temp.index`)**: vista genérica; no se abordó en 043.
- **Mensajes de error en APIs de barrios** (`$e->getMessage()` en 500): mejora cosméticas/P3; no bloqueante si el catálogo es público.
- **Métodos `login_Cliente` / `login_Paseador`**: siguen siendo los handlers de los dashboards autenticados (no son rutas legacy sueltas).

### Pruebas

`php artisan test --filter=Seg039bRoutesHardeningTest`: invitado → login en notificaciones; Alice no marca las de Bob; sin verificar → `verification.notice`; `/clientes/dashboard` ausente (404); `cliente.dashboard` → `cliente/dashboard` con `auth`; dashboards de rol exigen login; `empresas.pdf` sigue única y completa.

---

## Corrección 043b — dashboards activos del login (2026-10-03)

### Error de la 043

La 043 eliminó los registros sueltos `login_*` y el grupo intermedio de
`/superadmin/dashboard`, creyendo que eran solo legacy. Los **nombres**
`superadmin.dashboard`, `admin.dashboard`, `cliente.dashboard` y
`paseador.dashboard` seguían existiendo vía `prefix(...)->name(...)->name('dashboard')`,
pero:

1. Un `grep` de `name('….dashboard')` en `web.php` quedaba vacío (los
   nombres se componían por prefijo), lo que parecía “ruta eliminada”.
2. `admin.dashboard` quedó apuntando a `AdminController@index` (listado de
   usuarios) en lugar de `login_Admin` (vista de bienvenida).
3. Había riesgo de sombra/confusión con el grupo Superadmin sin
   `role:Superadmin` explícito en el nombre literal.

Las referencias en `RoleRedirect`, controladores y vistas **nunca** debieron
quitarse; son el núcleo del post-login.

### Corrección

- Se restauraron las **4 rutas con nombre literal** y middleware:
  - `superadmin.dashboard` → `auth + verified + role:Superadmin` → `index`
  - `admin.dashboard` → `auth` → `login_Admin`
  - `cliente.dashboard` → `auth` → `login_Cliente`
  - `paseador.dashboard` → `auth` → `login_Paseador`
- Se quitaron los `->name('dashboard')` duplicados dentro de los prefijos
  (misma URI).
- **No** se restauró `/clientes/dashboard` ni los `login_*` sin `auth`
  (duplicados inseguros).
- `POST notificaciones/leidas` con `auth+verified` se conserva.

### Grep de verificación (paso 3 de 043b)

Referencias en `app/` + `resources/views/` (deben existir; no están rotas):

- `route('superadmin.dashboard')`, `admin.dashboard`, `cliente.dashboard`,
  `paseador.dashboard` — presentes en `RoleRedirect`, login/social,
  sidebars y controladores de perfil.

Definiciones en `routes/web.php` (deben existir):

```
139: ->name('superadmin.dashboard')
142: ->name('admin.dashboard')
143: ->name('cliente.dashboard')
144: ->name('paseador.dashboard')
```

`/clientes/dashboard` (plural): sin referencias de nombre distinto; URI
ausente a propósito.
