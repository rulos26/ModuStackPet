
# 🧠 Prompt IA: Administrador de Módulos — Laravel 11 (Alta Seguridad y Control Total)

## 🎯 Objetivo
Desarrollar un **módulo de administración de módulos del sistema**, que gestione la **visualización, activación, desactivación y permisos de acceso** de cada módulo funcional en la aplicación (por ejemplo: geolocalización, certificados, notificaciones electrónicas, etc.).

---

## 🔹 Requerimientos funcionales

### 1. Gestión centralizada de módulos
- Cada módulo del sistema se registra automáticamente en una **tabla `modules`** con campos:
  - `id`, `name`, `slug`, `description`, `status` (activo/inactivo), `created_at`, `updated_at`.
- Cada vez que se agrega un nuevo módulo al sistema, se crea automáticamente su registro en la tabla (por seed o evento).

### 2. Control de acceso por roles y permisos
- Integrar con **spatie/laravel-permission** (ya configurado).
- Solo el **rol “SuperAdministrador”** puede modificar la visibilidad o el estado (`status`) de un módulo.
- Los demás roles solo pueden visualizar los módulos activos a los que tienen permisos.

### 3. Bloqueo dinámico de acceso
- Si un módulo está desactivado (`status = false`), cualquier intento de acceder a sus rutas, controladores o vistas debe redirigir al usuario a una **vista de acceso denegado personalizada**, sin excepción.
- Middleware de validación automática: `CheckModuleStatus`.

### 4. Seguridad avanzada
- **Doble autenticación para cambios críticos:**
  - Al desactivar un módulo, se envía un **código de verificación al correo institucional** del SuperAdministrador.
  - Solo con ese código puede confirmar la acción.
- **Registro de auditoría/logs:**
  - Tabla `module_logs` con campos: `user_id`, `module_id`, `action`, `ip_address`, `user_agent`, `timestamp`.
  - Se registran acciones como: Activación, Desactivación, Intento de acceso bloqueado, Modificación de permisos.
- **Protección CSRF**, **XSS** y **Rate Limiting** activados (Laravel 11 lo soporta nativo con Middleware moderno).

### 5. Interfaz de administración
- Panel visual donde el SuperAdministrador puede:
  - Ver listado de módulos con indicadores de estado (verde/rojo).
  - Activar o desactivar módulos con botones toggle protegidos.
  - Filtrar por nombre, rol o estado.
  - Ver historial de auditoría por módulo.
- Frontend en Blade o Livewire, con feedback inmediato (AJAX o evento Livewire emitido).

---

## 🔹 Requerimientos técnicos

- **Framework:** Laravel 11 (PHP 8.3)
- **Paquetes:**
  - `spatie/laravel-permission` (roles y permisos)
  - `laravel/fortify` o `laravel/breeze` (autenticación base)
  - `laravel/auditing` o sistema propio de logs
  - `laravel-mail` (envío de códigos de seguridad)
- **Seguridad:**
  - Verificación de token de correo electrónico antes de aplicar cambios.
  - Uso de colas (`queue`) para correos y auditorías.
  - Rate limiting en endpoints sensibles (`ThrottleRequests` middleware).

---

## 🔹 Estructura esperada

```
app/
 ├── Http/
 │    ├── Controllers/
 │    │     └── ModuleController.php
 │    ├── Middleware/
 │    │     └── CheckModuleStatus.php
 │    ├── Requests/
 │    │     └── UpdateModuleStatusRequest.php
 ├── Models/
 │    └── Module.php
database/
 ├── migrations/
 │    ├── create_modules_table.php
 │    └── create_module_logs_table.php
resources/
 └── views/
      └── modules/
           ├── index.blade.php
           └── access-denied.blade.php
routes/
 └── web.php
```

---

## 🔹 Comportamientos esperados

- Cuando el cliente **no paga por un módulo**, el SuperAdmin lo desactiva desde este panel.
- El sistema automáticamente bloquea su acceso visual y funcional.
- Se registra el cambio con quién lo hizo, desde qué IP y con qué código de verificación.
- Cualquier intento de uso del módulo muestra un mensaje tipo:
  > “El módulo **Geolocalización** no está disponible actualmente. Contacte al administrador para más información.”

---

## 🧩 Anticipación y manejo de errores

El módulo debe prever posibles errores y gestionarlos con claridad, registrándolos en logs y notificando al administrador cuando sea crítico.

### Escenarios comunes:
- Error al crear automáticamente el registro del módulo → registrar en `module_logs` y emitir alerta al administrador.
- Error en envío de correo de doble autenticación → revertir la acción y mostrar mensaje seguro.
- Error de permisos (usuario sin rol adecuado) → redirigir con `403 Access Denied` y log de intento.
- Desincronización entre base de datos y caché de módulos → incluir comando Artisan `php artisan modules:sync`.
- Protección ante intentos de fuerza bruta → aplicar limitadores de intento con `ThrottleRequests`.

---

## 💡 Solicitud al generador de código

> “Genérame el código completo para este módulo en Laravel 11, incluyendo migraciones, modelos, controladores, middleware, políticas, vistas y pruebas unitarias.  
> Implementa validaciones sólidas, manejo de errores anticipado y buenas prácticas de seguridad (Repository Pattern o Service Layer si aplica).  
> Incluye mensajes claros de error y logs detallados para trazabilidad total.”

---
