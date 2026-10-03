# SEG-041: auth del constructor movido a la ruta

Fecha: 2026-10-03  
Agente: Cursor  
Rama: `ia/cursor/auth-constructor-a-ruta`

## Problema

`departamentos`, `empresas` y `vacunas_certificaciones` solo tenían
`auth` en el `__construct` del controlador. En la ruta figuraba únicamente
`CheckModuleStatus` (ver SEG-039). Eso es más débil: el middleware de
módulo corre en la definición de ruta sin exigir sesión allí.

## Pruebas primero

Commit rojo: `test: SEG-041 pruebas rojas auth en ruta de catalogos`
(`tests/Feature/CatalogRouteAuthTest.php`). Fallaban, entre otras:

- `route_definitions_include_auth_middleware` — inspecciona
  `$route->middleware()` (no `gatherMiddleware()`, que ya mezclaba el
  `auth` del constructor).
- Invitado sin verificar / roles Cliente|Paseador en departamentos y
  empresas (aún no había `verified` ni `role` en la ruta).

## Cambio en `routes/web.php`

| Grupo | Middleware de ruta (nuevo) |
|---|---|
| `vacunas_certificaciones` | `auth`, `verified`, `CheckModuleStatus:certificados` |
| `departamentos` | `auth`, `verified`, `role:Superadmin\|Admin`, `CheckModuleStatus:departamentos` |
| `empresas` (+ pdf del grupo) | `auth`, `verified`, `role:Superadmin\|Admin`, `CheckModuleStatus:empresas` |

`tipos-empresas` se **separó** del grupo de `empresas` y sigue solo con
`CheckModuleStatus:empresas` (alcance de la tarea 040; prueba
`tipos_empresas_route_is_not_accidentally_given_auth_by_this_task`).

Criterio de roles:

- Departamentos y empresas: igual que ciudades (034b) —
  `Superadmin|Admin`.
- Vacunas: solo `auth` + `verified` (sin `role`), porque el controlador
  sirve también a Cliente sobre sus propias mascotas.

## Constructor: se mantiene

Se **deja** `$this->middleware('auth')` en
`DepartamentoController`, `EmpresaController` (con `except('getCiudades')`)
y `VacunasCertificacionesController` como defensa en profundidad. Motivo:
barato, no cambia comportamiento, y cubre si alguien registra el
controlador en otra ruta sin el grupo completo. No se quitó por no ser
redundante de forma peligrosa.

## Verificación

- `php artisan test --filter=CatalogRouteAuthTest`: **14 passed**.
- `php artisan test`: **210 passed** (672 assertions) = 196 previas + 14.
- `composer validate`: **valid**.

## Fuera de alcance (sin tocar)

paths-documentos, tipos-empresas, tipo-documentos, razas, barrios,
sectores, mensaje-de-bienvenidas (tarea 040).
