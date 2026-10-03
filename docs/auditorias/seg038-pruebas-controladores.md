# SEG-038: cobertura Feature de controladores

Fecha: 2026-10-03  
Rama: `ia/codex/cobertura-controladores`

## Criterio de revisión

Se volvió a cruzar el contenido actual de `app/Http/Controllers`, las rutas de
`routes/web.php` y las pruebas en `tests/Feature`. No se consideró que un
controlador tuviera cobertura completa solo porque una prueba de seguridad
visitara su `index`: para marcarlo como cubierto se exigieron caminos de negocio
y autorización propios del recurso.

## Estado actualizado

### Cobertura dedicada suficiente

| Controlador | Pruebas principales |
|---|---|
| `MascotaController` | `MascotaFlowsTest`, `MascotaAccessControlTest` |
| `MascotaDocumentController` | `MascotaDocumentFlowsTest` |
| `CiudadController` | `CiudadAccessAndCrudTest`, `CiudadCharacterizationTest` |
| `SectoreController` | `SectorCharacterizationTest` |
| `UserController` | `SuperadminUsuariosResourceAccessTest` |
| `ModuleController` | `ModuleManagementTest` |
| `RoleAssignmentController` | `RoleAssignmentAccessTest` |
| `SocialAuthController` | `SocialAuthTest` |
| `VacunasCertificacionesController` | `VacunasCertificacionesFlowsTest` (esta tarea) |

### Cobertura parcial o transversal

| Controlador o grupo | Cobertura existente | Falta principal |
|---|---|---|
| `RazaController` | Autorización transversal y alta con cuidados especiales | show, update, destroy y validación integral |
| `PDFController` | Autorización de ambas rutas | contenido y respuesta PDF con datos reales |
| `DepartamentoController`, `EmpresaController` | Autenticación/roles e index en `CatalogRouteAuthTest` | CRUD y validaciones de negocio |
| `AdminController`, `SuperadminController` | Matriz de acceso y bloqueo de mutaciones | flujos positivos de perfil/usuarios |
| `BackupConfigController`, `DatabaseConfigController`, `MigrationController` | Acceso denegado y no mutación | caminos positivos, errores y efectos secundarios |
| `CleanController`, `SeederController` | Herramientas ocultas/deshabilitadas | ejecución positiva controlada y errores |
| Catálogos protegidos por tareas 040/041/043 | Invitado y roles no autorizados | CRUD completo por recurso |

### Sin flujo Feature dedicado

Los siguientes controladores siguen sin una prueba que recorra sus caminos de
negocio propios:

- Prioridad alta: `EmpresaController`, `ClienteController`,
  `PaseadorController`, `DocumentRequirementController`,
  `OAuthProviderController` y `EmailConfigController`.
- Prioridad media: `DepartamentoController`, `RazaController`,
  `BarrioController`, `TipoDocumentoController`, `TiposEmpresaController`,
  `MensajeDeBienvenidaController`, `PathDocumentoController`,
  `ConfiguracionController` y `ArbolGenealogicoController`.
- Prioridad operativa: `BackupConfigController`, `DatabaseConfigController`,
  `MigrationController`, `CleanController` y `SeederController`; sus caminos
  positivos requieren dobles cuidadosos para no ejecutar operaciones reales.
- `ProfileController` no tiene rutas activas propias en `web.php`; debe
  confirmarse si sigue siendo código utilizado antes de invertir en cobertura.

## Cobertura agregada

Se priorizó `VacunasCertificacionesController` porque maneja datos veterinarios,
documentos y autorización por propiedad. Las siete pruebas nuevas cubren:

- redirección del invitado en las siete rutas resource;
- index y selector de mascotas limitados al propietario;
- visibilidad global para Admin;
- store válido y validación de campos requeridos;
- rechazo de creación sobre una mascota ajena, sin escritura;
- show, edit, update y destroy del propietario;
- HTTP 403 y ausencia de mutaciones para un tercero.

No se probaron cargas de archivos en esta primera cobertura: el flujo sin
adjuntos ya valida persistencia, autorización y transacciones, mientras que los
archivos merecen casos separados con `Storage::fake` para reemplazo, limpieza y
formatos inválidos.

## Bugs encontrados

No se encontró un fallo reproducible en los caminos cubiertos. No se modificó
ningún archivo de `app/`, conforme a la restricción de la tarea.

## Resultados

- `php artisan test --filter=VacunasCertificacionesFlowsTest`: 7 aprobadas,
  54 aserciones.
- `php artisan test`: 273 aprobadas, 1138 aserciones.
- `composer validate`: `./composer.json is valid`.

