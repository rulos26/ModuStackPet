# SEG-021: `Kernel.php` y modelos duplicados

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/kernel-modelos-duplicados`

## Resumen

- Se retiró `app/Http/Kernel.php`. El archivo no participaba en el arranque de
  la aplicación: `bootstrap/app.php` configura rutas y middleware mediante
  `Application::configure()`.
- Ninguno de los seis modelos investigados está muerto. Cada par se reparte
  consumidores activos sobre la misma tabla.
- Se recomienda consolidar en una tarea posterior hacia `Ciudad`, `Sector` y
  `TipoEmpresa`, que son los nombres singulares correctos y los modelos que
  reflejan mejor las migraciones actuales. Esta recomendación no se aplicó.

## Contexto de versión

`composer.json` exige PHP `^8.2` y `laravel/framework` `^12.61`;
`composer.lock` fija Laravel `v12.69.2`. El directorio `vendor` local reportó
Laravel `v11.56.1`, por lo que está desactualizado respecto del lock. Esta
diferencia no cambia la conclusión: tanto la estructura instalada de Laravel 11
como la fijada de Laravel 12 usan la configuración de `bootstrap/app.php` de
este proyecto y no registran `App\Http\Kernel`.

## Parte A: eliminación de `Kernel.php`

### Evidencia de que estaba muerto

- `bootstrap/app.php` registra los alias de Spatie (`role`, `permission` y
  `role_or_permission`) y agrega `SessionTimeout` al grupo web mediante
  `withMiddleware()`.
- No existe ninguna referencia ejecutable a `App\Http\Kernel`, `Kernel::class`
  ni a sus propiedades fuera del propio archivo. Las otras coincidencias están
  en documentación histórica.
- La auditoría SEG-010 ya verificó que
  `Illuminate\Contracts\Http\Kernel` se resuelve a
  `Illuminate\Foundation\Http\Kernel`, no a la clase de la aplicación.
- El alias `module.active` que aparecía únicamente en el Kernel muerto tampoco
  tiene consumidores ejecutables: las rutas usan directamente
  `App\Http\Middleware\CheckModuleStatus::class`.

### Cambio

Se eliminó del árbol versionado únicamente `app/Http/Kernel.php`. Conforme a
las reglas del repositorio, la copia física se movió primero a
`_borrar/Kernel.php`, directorio ignorado por Git, para permitir revisión humana
local antes de descartarla definitivamente.

## Parte B: modelos duplicados

### Resultado general

No hay una clase totalmente sin uso en ninguno de los pares. Las variantes con
nombres generados sostienen los CRUD de catálogos, mientras los modelos
singulares añadidos después sostienen Empresa y relaciones de dominio. No hay
factories para ninguno de los seis modelos.

Las tres migraciones (`ciudades`, `sectores`, `tipos_empresas`) incluyen
`deleted_at`. Los modelos singulares posteriores usan `SoftDeletes`; las
variantes generadas no. En consecuencia, los CRUD actuales de las variantes
generadas hacen borrado físico y pueden consultar registros marcados como
eliminados por los modelos singulares. Esta diferencia debe cubrirse con pruebas
durante una consolidación.

### `Ciudad` / `Ciudade`

Ambas apuntan a `ciudades`, con clave primaria `id_municipio`.

**Usos activos de `Ciudad`:**

- `EmpresaController` carga ciudades activas para formularios.
- Las relaciones `Empresa::ciudad()` y `Cliente::ciudad()` usan explícitamente
  `Ciudad` y la clave `id_municipio`.
- `Paseador::ciudad()` usa `Ciudad`; el propio modelo define
  `id_municipio` como clave primaria, por lo que Eloquent puede resolverla.
- `Ciudad` también define la relación inversa con empresas y usa
  `SoftDeletes`.

**Usos activos de `Ciudade`:**

- Todo `CiudadController` (listado, alta, consulta, edición, eliminación y
  cambio de estado) usa `Ciudade`; sus rutas resource están activas.
- `Departamento::ciudades()` usa `Ciudade`.
- `resources/views/user/form.blade.php` consulta `Ciudade` para el selector de
  ciudad y para localizar Bogotá.

**Historia y esquema:** `Ciudade` apareció el 2025-04-14 con los CRUD de datos.
`Ciudad` se añadió el 2025-04-15 al mejorar Empresa. La migración del
2025-04-14 usa `id_municipio`, `departamento_id`, `estado` y `softDeletes`, que
`Ciudad` representa de forma más completa. `Ciudade` convierte `estado` a
entero y omite `SoftDeletes`; `Ciudad` lo convierte a booleano y sí incorpora el
trait.

**Recomendación:** conservar `Ciudad` como modelo canónico y migrar hacia él
`CiudadController`, `Departamento::ciudades()` y el formulario de usuario.
Antes de retirar `Ciudade`, agregar pruebas del CRUD, route model binding,
relaciones, clave `id_municipio`, filtro de eliminados y cast de `estado`.

### `Sector` / `Sectore`

Ambas usan por convención la tabla `sectores`.

**Usos activos de `Sector`:**

- `EmpresaController` lo usa en los formularios de creación y edición.
- `Empresa::sector()` y `Sector::empresas()` forman las relaciones del dominio.
- Incluye `HasFactory`, `SoftDeletes`, casts temporales y la relación inversa.

**Usos activos de `Sectore`:**

- Todo `SectoreController` usa esta clase; `Route::resource('sectores', ...)`
  está activa.
- Las vistas `resources/views/sectore/` reciben instancias y colecciones creadas
  por ese controlador.

**Historia y esquema:** `Sectore` ya estaba en la restauración base del
2025-04-05 y se integró con el CRUD el 2025-04-14. `Sector` se añadió el
2025-04-15 para Empresa. La migración crea `deleted_at`; solo `Sector` aplica
`SoftDeletes`.

**Recomendación:** conservar `Sector` y cambiar el CRUD para usarlo. Las pruebas
deben fijar el comportamiento de eliminación lógica, porque sustituir
`Sectore::delete()` por `Sector::delete()` cambia hoy un borrado físico por uno
lógico, que es lo coherente con la migración.

### `TipoEmpresa` / `TiposEmpresa`

Ambas apuntan por convención a `tipos_empresas`.

**Usos activos de `TipoEmpresa`:**

- `EmpresaController` carga este catálogo en creación y edición.
- `Empresa::tipoEmpresa()` y `TipoEmpresa::empresas()` implementan las
  relaciones del dominio.
- Incluye `HasFactory`, `SoftDeletes`, casts temporales y la relación inversa.

**Usos activos de `TiposEmpresa`:**

- Todo `TiposEmpresaController` usa esta clase; el resource
  `tipos-empresas` está activo dentro del grupo del módulo Empresa.
- Las vistas `resources/views/tipos-empresa/` consumen las instancias producidas
  por ese controlador.

**Historia y esquema:** `TiposEmpresa` venía de la restauración base y quedó
integrado al CRUD el 2025-04-14. `TipoEmpresa` se añadió el 2025-04-15 con las
mejoras de Empresa. La migración crea `deleted_at`; únicamente `TipoEmpresa`
usa `SoftDeletes`.

**Recomendación:** conservar `TipoEmpresa`, migrar el CRUD y sus type hints, y
retirar `TiposEmpresa` solo después de cubrir rutas, binding, validación y
borrado lógico con pruebas.

## Plan recomendado para una tarea posterior

1. Añadir pruebas de caracterización para los tres CRUD y sus relaciones con
   Empresa, Departamento, Cliente y Paseador.
2. Sustituir consumidores de `Ciudade`, `Sectore` y `TiposEmpresa` por los
   modelos singulares canónicos.
3. Verificar explícitamente el efecto de `SoftDeletes` y la clave
   `id_municipio`.
4. Corregir PHPDoc y nombres internos generados que todavía mencionan las
   clases antiguas.
5. Retirar las tres clases duplicadas solo cuando no queden referencias.

No se aplicó ningún paso de esta consolidación porque la Parte B era de solo
lectura.
