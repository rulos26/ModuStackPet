# 📚 DOCUMENTO TÉCNICO EXHAUSTIVO - MODUSTACKPET

**Versión:** 1.0  
**Fecha:** Noviembre 2025  
**Framework:** Laravel 11.31  
**PHP:** ^8.2

---

## 📋 ÍNDICE

1. [Introducción](#introducción)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Tecnologías y Dependencias](#tecnologías-y-dependencias)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [Modelos y Relaciones](#modelos-y-relaciones)
6. [Controladores](#controladores)
7. [Servicios](#servicios)
8. [Middleware](#middleware)
9. [Autenticación y Autorización](#autenticación-y-autorización)
10. [Módulos del Sistema](#módulos-del-sistema)
11. [Base de Datos](#base-de-datos)
12. [Vistas y Frontend](#vistas-y-frontend)
13. [Configuraciones](#configuraciones)
14. [APIs y Servicios Externos](#apis-y-servicios-externos)
15. [Seguridad](#seguridad)
16. [Flujos Principales](#flujos-principales)
17. [Mejores Prácticas](#mejores-prácticas)

---

## 1. INTRODUCCIÓN

### 1.1 Descripción General

**ModuStackPet** es una aplicación web desarrollada en Laravel 11 que gestiona información de clientes, mascotas, vacunas, certificaciones y servicios relacionados con el cuidado de animales domésticos. El sistema está diseñado con una arquitectura modular que permite activar/desactivar funcionalidades según las necesidades del negocio.

### 1.2 Objetivos del Sistema

- Gestión centralizada de clientes y sus mascotas
- Administración de vacunas y certificaciones veterinarias
- Sistema de roles y permisos granular
- Autenticación mediante OAuth (Google, Facebook, etc.)
- Sistema modular con activación/desactivación dinámica
- Geolocalización de clientes mediante geocodificación
- Generación de reportes PDF
- Sistema de backup automatizado de base de datos

### 1.3 Roles del Sistema

1. **Superadmin**: Acceso completo al sistema, gestión de módulos, configuraciones avanzadas
2. **Admin**: Administración de usuarios y contenido, sin acceso a configuraciones críticas
3. **Cliente**: Gestión de su perfil, mascotas y documentos
4. **Paseador**: Gestión de servicios de paseo de mascotas

---

## 2. ARQUITECTURA DEL SISTEMA

### 2.1 Patrón Arquitectónico

El sistema sigue el patrón **MVC (Modelo-Vista-Controlador)** de Laravel con las siguientes capas:

```
┌─────────────────────────────────────────────────────────┐
│                    CAPA DE PRESENTACIÓN                  │
│  (Blade Templates, JavaScript, CSS, Vite)                │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                    CAPA DE CONTROL                       │
│  (Controllers, Middleware, Requests)                    │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                    CAPA DE SERVICIOS                     │
│  (BackupService, GeocodingService, etc.)                 │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                    CAPA DE DATOS                         │
│  (Models, Migrations, Eloquent ORM)                     │
└─────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────┐
│                    BASE DE DATOS                         │
│  (MySQL/MariaDB)                                         │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Principios de Diseño

- **Separación de Responsabilidades**: Cada clase tiene una única responsabilidad
- **Inversión de Dependencias**: Uso de interfaces y inyección de dependencias
- **DRY (Don't Repeat Yourself)**: Reutilización de código mediante servicios y traits
- **Modularidad**: Sistema de módulos activables/desactivables
- **Seguridad por Capas**: Middleware, validaciones, políticas de acceso

---

## 3. TECNOLOGÍAS Y DEPENDENCIAS

### 3.1 Backend

| Tecnología | Versión | Uso |
|------------|---------|-----|
| PHP | ^8.2 | Lenguaje de programación |
| Laravel | ^11.31 | Framework principal |
| MySQL/MariaDB | - | Base de datos |
| Composer | - | Gestor de dependencias |

### 3.2 Paquetes Principales

#### Producción
- **spatie/laravel-permission** (^6.10): Sistema de roles y permisos
- **laravel/fortify** (^1.25): Autenticación y registro
- **laravel/socialite** (^5.23): Autenticación OAuth
- **barryvdh/laravel-dompdf** (^3.1): Generación de PDFs

#### Desarrollo
- **laravel/pint** (^1.20): Formateo de código
- **nunomaduro/larastan** (^3.0): Análisis estático
- **phpunit/phpunit** (^11.0.1): Testing
- **ibex/crud-generator** (^2.1): Generación de CRUDs

### 3.3 Frontend

| Tecnología | Versión | Uso |
|------------|---------|-----|
| Vite | ^6.0 | Build tool |
| Tailwind CSS | ^3.4.13 | Framework CSS |
| Axios | ^1.7.4 | Cliente HTTP |
| D3.js | v7 | Visualización de árbol genealógico |
| DataTables | - | Tablas interactivas |
| SweetAlert2 | - | Alertas y confirmaciones |

### 3.4 Servicios Externos

- **Nominatim (OpenStreetMap)**: Geocodificación de direcciones
- **OAuth Providers**: Google, Facebook, GitHub, etc.

---

## 4. ESTRUCTURA DEL PROYECTO

### 4.1 Estructura de Directorios

```
ModuStackPet/
├── app/
│   ├── Actions/
│   │   └── Fortify/          # Acciones personalizadas de Fortify
│   ├── Console/
│   │   └── Commands/         # Comandos Artisan
│   ├── Exceptions/
│   │   └── Handler.php       # Manejo de excepciones
│   ├── Http/
│   │   ├── Controllers/      # Controladores (41 archivos)
│   │   ├── Kernel.php        # Configuración de middleware
│   │   ├── Middleware/       # Middleware personalizado
│   │   ├── Requests/         # Form Requests (validación)
│   │   └── Responses/       # Respuestas personalizadas
│   ├── Livewire/             # Componentes Livewire
│   ├── Mail/                 # Plantillas de correo
│   ├── Models/               # Modelos Eloquent (27 archivos)
│   ├── Notifications/        # Notificaciones
│   ├── Observers/             # Observadores de modelos
│   ├── Policies/              # Políticas de autorización
│   ├── Providers/             # Service Providers
│   └── Services/              # Servicios de negocio
├── bootstrap/
│   └── app.php                # Inicialización de Laravel
├── config/                    # Archivos de configuración
├── database/
│   ├── factories/            # Factories para testing
│   ├── migrations/            # Migraciones (42 archivos)
│   └── seeders/               # Seeders (10 archivos)
├── public/                    # Archivos públicos
│   ├── storage/               # Almacenamiento de archivos
│   └── index.php              # Punto de entrada
├── resources/
│   ├── css/                   # Estilos CSS
│   ├── js/                    # JavaScript
│   ├── lang/                  # Archivos de idioma
│   └── views/                 # Vistas Blade (142 archivos)
├── routes/
│   ├── console.php            # Rutas de consola
│   └── web.php                # Rutas web
├── storage/
│   ├── app/                   # Archivos de la aplicación
│   ├── framework/             # Cache y sesiones
│   └── logs/                  # Logs del sistema
├── tests/                    # Tests
└── vendor/                   # Dependencias de Composer
```

### 4.2 Convenciones de Nomenclatura

- **Controladores**: PascalCase + "Controller" (ej: `ClienteController`)
- **Modelos**: PascalCase singular (ej: `Mascota`)
- **Vistas**: kebab-case (ej: `cliente-dashboard.blade.php`)
- **Rutas**: kebab-case (ej: `cliente.dashboard`)
- **Migraciones**: snake_case con timestamp (ej: `2025_01_31_create_mascotas_table.php`)

---

## 5. MODELOS Y RELACIONES

### 5.1 Modelos Principales

#### User (Usuario)
```php
- Campos principales: name, email, password, tipo_documento, cedula, avatar, telefono, whatsapp, activo, fecha_nacimiento
- Relaciones:
  ├── hasOne(Cliente)
  ├── hasOne(Paseador)
  ├── hasMany(Mascota)
  └── hasMany(SocialAccount)
- Traits: HasRoles (Spatie), MustVerifyEmail
```

#### Cliente
```php
- Campos principales: user_id, nombre, direccion, ciudad_id, barrio_id, latitud, longitud, nombre_conjunto_cerrado, interior_apartamento
- Relaciones:
  ├── belongsTo(User)
  ├── belongsTo(Ciudad)
  ├── belongsTo(Barrio)
  └── belongsTo(TipoDocumento)
```

#### Mascota
```php
- Campos principales: user_id, nombre, edad, fecha_nacimiento, raza_id, genero, vacunas_completas, esterilizado, avatar
- Relaciones:
  ├── belongsTo(User)
  └── belongsTo(Raza)
- Casts: fecha_nacimiento (date), vacunas_completas (boolean), esterilizado (boolean)
```

#### VacunasCertificacione
```php
- Campos principales: id_mascota, fecha_ultima_vacuna, operaciones, certificado_veterinario, cedula_propietario
- Relaciones:
  └── belongsTo(Mascota)
```

#### Module (Módulo)
```php
- Campos principales: name, slug, description, status
- Funcionalidad: Control de activación/desactivación de módulos
```

### 5.2 Relaciones Entre Modelos

```
User
  ├── Cliente (1:1)
  │     ├── Ciudad (N:1)
  │     └── Barrio (N:1)
  ├── Paseador (1:1)
  ├── Mascota (1:N)
  │     └── Raza (N:1)
  │           └── TipoMascota
  └── SocialAccount (1:N)
        └── OAuthProvider (N:1)

VacunasCertificacione
  └── Mascota (N:1)
        └── User (N:1)
```

### 5.3 Modelos de Configuración

- **BackupConfig**: Configuración de backups
- **BackupLog**: Logs de backups ejecutados
- **DatabaseConfig**: Configuraciones de conexión a BD
- **EmailConfig**: Configuración SMTP
- **OAuthProvider**: Proveedores OAuth configurados
- **Module**: Módulos del sistema
- **ModuleLog**: Logs de cambios en módulos
- **Configuracion**: Configuraciones generales

---

## 6. CONTROLADORES

### 6.1 Controladores por Módulo

#### Autenticación
- `Auth/LoginController`: Login tradicional
- `Auth/RegisterController`: Registro de usuarios
- `Auth/SocialAuthController`: Autenticación OAuth
- `Auth/ResetPasswordController`: Recuperación de contraseña

#### Roles
- `SuperadminController`: Dashboard y gestión superadmin
- `AdminController`: Dashboard y gestión admin
- `ClienteController`: Dashboard y perfil de cliente
- `PaseadorController`: Dashboard y perfil de paseador

#### Gestión de Contenido
- `MascotaController`: CRUD de mascotas
- `RazaController`: CRUD de razas
- `VacunasCertificacionesController`: CRUD de vacunas y certificados
- `BarrioController`: CRUD de barrios
- `CiudadController`: CRUD de ciudades
- `DepartamentoController`: CRUD de departamentos

#### Configuración (Superadmin)
- `Superadmin/BackupConfigController`: Configuración de backups
- `Superadmin/DatabaseConfigController`: Configuración de BD
- `Superadmin/EmailConfigController`: Configuración de email
- `Superadmin/OAuthProviderController`: Configuración OAuth

#### Sistema
- `ModuleController`: Gestión de módulos
- `ConfiguracionController`: Configuración general
- `UserController`: Gestión de usuarios
- `RoleAssignmentController`: Asignación de roles

#### Especiales
- `ArbolGenealogicoController`: Visualización del árbol genealógico
- `PDFController`: Generación de PDFs
- `MensajeDeBienvenidaController`: Mensajes de bienvenida por rol

### 6.2 Patrones de Controladores

#### Resource Controllers (CRUD estándar)
```php
index()    // Listar
create()   // Formulario de creación
store()    // Guardar nuevo
show()     // Mostrar uno
edit()     // Formulario de edición
update()   // Actualizar
destroy()  // Eliminar
```

#### Controladores con Lógica Especial
- Filtrado por usuario (clientes solo ven sus datos)
- Validación de propiedad (solo pueden editar sus propios registros)
- Transacciones DB para operaciones críticas
- Manejo de archivos (upload, storage, eliminación)

---

## 7. SERVICIOS

### 7.1 BackupService

**Ubicación**: `app/Services/BackupService.php`

**Responsabilidades**:
- Crear backups completos de la base de datos de producción
- Verificar y crear base de datos destino
- Copiar estructura y datos de tablas
- Generar logs de operaciones
- Validar permisos y evitar backups a BD de producción

**Métodos principales**:
```php
executeBackup(BackupConfig $backupConfig, $userId): BackupLog
createOrVerifyDatabase(): void
copyTablesData(): void
getTablesList(): array
```

**Características**:
- Uso de conexiones PDO directas para evitar problemas de permisos
- Validación de que no se haga backup a la misma BD de producción
- Logs detallados de cada operación
- Manejo de errores robusto

### 7.2 GeocodingService

**Ubicación**: `app/Services/GeocodingService.php`

**Responsabilidades**:
- Convertir direcciones en coordenadas (latitud/longitud)
- Integración con Nominatim (OpenStreetMap)
- Parseo de direcciones colombianas
- Estrategia de búsqueda en cascada

**Métodos principales**:
```php
geocode(string $direccion, ?string $ciudad, ?string $pais): ?array
parsearDireccionColombiana(string $direccion): ?array
```

**Características**:
- Búsqueda estructurada y libre
- Variaciones de direcciones (CRA, CALLE, AV, etc.)
- Rate limiting (1 segundo entre llamadas)
- Manejo de errores y fallbacks

### 7.3 ClienteDataVerificationService

**Ubicación**: `app/Services/ClienteDataVerificationService.php`

**Responsabilidades**:
- Verificar datos faltantes del perfil de cliente
- Calcular porcentaje de completitud del perfil
- Generar lista de acciones pendientes
- Validar completitud del perfil

**Métodos principales**:
```php
getMissingData(User $user): array
getCompletionPercentage(User $user): int
isProfileComplete(User $user): bool
```

**Datos verificados**:
- Email verificado
- Datos del usuario (teléfono, WhatsApp, cédula, fecha de nacimiento, avatar)
- Datos del cliente (dirección, ciudad, barrio)
- Registro de mascotas
- Fotos de mascotas

---

## 8. MIDDLEWARE

### 8.1 Middleware Personalizado

#### CheckModuleStatus

**Ubicación**: `app/Http/Middleware/CheckModuleStatus.php`

**Propósito**: Verificar si un módulo está activo antes de permitir el acceso a una ruta.

**Uso**:
```php
Route::middleware([CheckModuleStatus::class . ':arbol-genealogico'])
    ->group(function () {
        // Rutas del módulo
    });
```

**Funcionalidad**:
- Consulta la tabla `modules` para verificar el estado
- Redirige a página de acceso denegado si el módulo está inactivo
- Registra intentos de acceso en logs

#### SessionTimeout

**Ubicación**: `app/Http/Middleware/SessionTimeout.php`

**Propósito**: Gestionar timeout de sesiones automáticamente.

### 8.2 Middleware de Laravel Utilizados

- `auth`: Verificar autenticación
- `verified`: Verificar email verificado
- `role`: Verificar rol específico
- `permission`: Verificar permiso específico
- `role_or_permission`: Verificar rol o permiso

---

## 9. AUTENTICACIÓN Y AUTORIZACIÓN

### 9.1 Sistema de Autenticación

#### Métodos de Autenticación

1. **Autenticación Tradicional**
   - Email + Password
   - Registro con formulario
   - Recuperación de contraseña

2. **OAuth (Social Authentication)**
   - Google
   - Facebook
   - GitHub
   - Otros proveedores configurados

3. **Verificación de Email**
   - Obligatoria para usuarios
   - Notificaciones personalizadas en español
   - Reenvío de verificación

#### Flujo de Registro

```
Usuario → Registro (tradicional/OAuth)
    ↓
Creación de User
    ↓
Asignación de Rol (Cliente por defecto)
    ↓
Creación de Perfil Cliente (si aplica)
    ↓
Envío de Email de Verificación
    ↓
Redirección a Dashboard (con restricciones si no verificado)
```

### 9.2 Sistema de Roles y Permisos

**Paquete**: Spatie Laravel Permission

**Roles del Sistema**:
- `Superadmin`: Acceso completo
- `Admin`: Gestión de usuarios y contenido
- `Cliente`: Gestión de su perfil y mascotas
- `Paseador`: Gestión de servicios

**Uso en Controladores**:
```php
// Verificar rol
if ($user->hasRole('Superadmin')) { }

// Verificar permiso
if ($user->can('edit users')) { }

// Middleware
Route::middleware(['role:Superadmin'])->group(...);
```

### 9.3 Políticas de Autorización

**ModulePolicy**: Controla acceso a módulos según roles.

**Validaciones de Propiedad**:
- Los clientes solo pueden ver/editar sus propios datos
- Los administradores tienen acceso completo
- Validación en `show()`, `edit()`, `update()`, `destroy()`

---

## 10. MÓDULOS DEL SISTEMA

### 10.1 Sistema de Módulos

El sistema utiliza una tabla `modules` para gestionar módulos activables/desactivables.

**Estructura**:
```php
- name: Nombre del módulo
- slug: Identificador único (usado en rutas)
- description: Descripción del módulo
- status: Boolean (activo/inactivo)
```

### 10.2 Módulos Disponibles

#### Módulos Principales
1. **modulos**: Administración de módulos
2. **mascotas**: Gestión de mascotas
3. **certificados**: Vacunas y certificaciones
4. **reportes**: Generación de PDFs
5. **empresas**: Gestión de empresas
6. **configuracion**: Configuración general
7. **migraciones**: Gestión de migraciones
8. **seeders**: Ejecución de seeders
9. **clean**: Limpieza del sistema

#### Módulos Geográficos
10. **departamentos**: Gestión de departamentos
11. **ciudades**: Gestión de ciudades
12. **barrios**: Gestión de barrios
13. **sectores**: Gestión de sectores

#### Módulos de Configuración
14. **oauth-providers**: Proveedores OAuth
15. **database-config**: Configuración de BD
16. **email-config**: Configuración de email
17. **backup-config**: Configuración de backups

#### Módulos Especiales
18. **bienvenida**: Mensajes de bienvenida
19. **tipo-documentos**: Tipos de documentos
20. **razas**: Razas de mascotas
21. **paths-documentos**: Rutas de documentos
22. **arbol-genealogico**: Árbol genealógico interactivo

### 10.3 Activación/Desactivación

- **Controlador**: `ModuleController`
- **Vista**: `modules/index.blade.php`
- **Livewire**: `ToggleButton` para activación rápida
- **Middleware**: `CheckModuleStatus` para protección de rutas
- **Logs**: Registro de cambios en `module_logs`

---

## 11. BASE DE DATOS

### 11.1 Estructura de Tablas Principales

#### Tabla: `users`

**Descripción**: Tabla principal de usuarios del sistema. Almacena información básica de autenticación y perfil de todos los usuarios.

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Identificador único |
| `name` | string(255) | NOT NULL | Nombre completo del usuario |
| `email` | string(255) | NOT NULL, UNIQUE | Correo electrónico (usado para login) |
| `email_verified_at` | timestamp | NULLABLE | Fecha de verificación de email |
| `password` | string(255) | NOT NULL | Contraseña hasheada (bcrypt) |
| `remember_token` | string(100) | NULLABLE | Token para "recordar sesión" |
| `tipo_documento` | string(255) | NULLABLE | Tipo de documento (ID o referencia) |
| `cedula` | string(255) | NULLABLE, UNIQUE | Número de documento de identidad |
| `avatar` | string(255) | NULLABLE | Ruta al archivo de foto de perfil |
| `activo` | boolean | DEFAULT true | Estado del usuario (activo/inactivo) |
| `telefono` | string(255) | NULLABLE | Número de teléfono |
| `whatsapp` | string(255) | NULLABLE | Número de WhatsApp |
| `fecha_nacimiento` | date | NULLABLE | Fecha de nacimiento |
| `created_at` | timestamp | NULLABLE | Fecha de creación |
| `updated_at` | timestamp | NULLABLE | Fecha de última actualización |

**Índices**:
- PRIMARY KEY: `id`
- UNIQUE: `email`
- UNIQUE: `cedula`

**Relaciones**:
- `hasOne(Cliente)`: Un usuario puede tener un perfil de cliente
- `hasOne(Paseador)`: Un usuario puede tener un perfil de paseador
- `hasMany(Mascota)`: Un usuario puede tener múltiples mascotas
- `hasMany(SocialAccount)`: Un usuario puede tener múltiples cuentas sociales (OAuth)

---

#### Tabla: `clientes`

**Descripción**: Perfil extendido de clientes. Almacena información específica de clientes incluyendo ubicación geográfica y datos de contacto.

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Identificador único |
| `user_id` | bigint unsigned | NOT NULL, FOREIGN KEY | Referencia a `users.id` (onDelete: cascade) |
| `nombre` | string(255) | NOT NULL | Nombre completo del cliente |
| `tipo_documento_id` | bigint unsigned | NULLABLE, FOREIGN KEY | Referencia a `tipo_documentos.id` |
| `cedula` | string(255) | NULLABLE | Número de documento (duplicado de users para referencia) |
| `telefono` | string(255) | NULLABLE | Teléfono de contacto |
| `whatsapp` | string(255) | NULLABLE | WhatsApp de contacto |
| `fecha_nacimiento` | date | NULLABLE | Fecha de nacimiento |
| `direccion` | string(255) | NULLABLE | Dirección completa |
| `nombre_conjunto_cerrado` | string(255) | NULLABLE | Nombre del conjunto cerrado/complejo |
| `interior_apartamento` | string(100) | NULLABLE | Interior o número de apartamento |
| `ciudad_id` | bigint unsigned | NULLABLE, FOREIGN KEY | Referencia a `ciudades.id_municipio` |
| `barrio_id` | bigint unsigned | NULLABLE, FOREIGN KEY | Referencia a `barrios.id` |
| `latitud` | decimal(10,8) | NULLABLE | Coordenada de latitud (geocodificación) |
| `longitud` | decimal(11,8) | NULLABLE | Coordenada de longitud (geocodificación) |
| `avatar` | string(255) | NULLABLE | Ruta al archivo de foto de perfil |
| `created_at` | timestamp | NULLABLE | Fecha de creación |
| `updated_at` | timestamp | NULLABLE | Fecha de última actualización |

**Índices**:
- PRIMARY KEY: `id`
- INDEX: `user_id`
- INDEX: `idx_clientes_coordenadas` (latitud, longitud)

**Foreign Keys**:
- `user_id` → `users.id` (onDelete: CASCADE)
- `tipo_documento_id` → `tipo_documentos.id` (onDelete: SET NULL)
- `ciudad_id` → `ciudades.id_municipio` (onDelete: SET NULL)
- `barrio_id` → `barrios.id` (onDelete: SET NULL)

**Relaciones**:
- `belongsTo(User)`: Un cliente pertenece a un usuario
- `belongsTo(Ciudad)`: Un cliente pertenece a una ciudad
- `belongsTo(Barrio)`: Un cliente pertenece a un barrio
- `belongsTo(TipoDocumento)`: Un cliente tiene un tipo de documento

**Notas**:
- La tabla `clientes` tiene una relación 1:1 con `users`
- Los campos `direccion`, `ciudad_id`, `barrio_id` se utilizan para geocodificación automática
- `latitud` y `longitud` se calculan automáticamente mediante `GeocodingService`

---

#### Tabla: `mascotas`

**Descripción**: Información de mascotas registradas en el sistema. Cada mascota pertenece a un usuario (propietario).

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Identificador único |
| `user_id` | bigint unsigned | NOT NULL, FOREIGN KEY | Referencia a `users.id` (propietario) |
| `avatar` | string(255) | NULLABLE | Ruta al archivo de foto de la mascota |
| `nombre` | string(255) | NOT NULL | Nombre de la mascota |
| `edad` | integer | NULLABLE, UNSIGNED | Edad en años |
| `fecha_nacimiento` | date | NULLABLE | Fecha de nacimiento |
| `raza_id` | bigint unsigned | NULLABLE, FOREIGN KEY | Referencia a `razas.id` |
| `genero` | enum | NULLABLE | 'Macho' o 'Hembra' |
| `vacunas_completas` | boolean | DEFAULT false | Indica si las vacunas están completas |
| `ultima_vacunacion` | date | NULLABLE | Fecha de última vacunación |
| `comportamiento` | text | NULLABLE | Descripción del comportamiento |
| `recomendaciones` | text | NULLABLE | Recomendaciones especiales |
| `esterilizado` | boolean | DEFAULT false | Indica si está esterilizado |
| `enfermedades` | text | NULLABLE | Enfermedades conocidas |
| `ultimo_examen_medico` | date | NULLABLE | Fecha del último examen médico |
| `created_at` | timestamp | NULLABLE | Fecha de creación |
| `updated_at` | timestamp | NULLABLE | Fecha de última actualización |

**Índices**:
- PRIMARY KEY: `id`
- INDEX: `user_id`
- INDEX: `raza_id`
- INDEX: `fecha_nacimiento`
- INDEX: `ultima_vacunacion`
- INDEX: `ultimo_examen_medico`

**Foreign Keys**:
- `user_id` → `users.id` (onDelete: CASCADE)
- `raza_id` → `razas.id` (onDelete: SET NULL)

**Relaciones**:
- `belongsTo(User)`: Una mascota pertenece a un usuario (propietario)
- `belongsTo(Raza)`: Una mascota pertenece a una raza

**Casts** (en el modelo):
- `fecha_nacimiento` → `date`
- `ultima_vacunacion` → `date`
- `ultimo_examen_medico` → `date`
- `vacunas_completas` → `boolean`
- `esterilizado` → `boolean`

**Notas**:
- Los campos de ubicación (`direccion`, `barrio_id`, `interior_apto`) fueron eliminados de esta tabla, ya que la ubicación se gestiona a nivel del perfil del cliente (propietario)
- La tabla `mascotas` tiene una relación N:1 con `users` (un usuario puede tener múltiples mascotas)

---

#### Tabla: `vacunas_certificaciones`

**Descripción**: Registro de vacunas y certificaciones veterinarias de las mascotas. Almacena información sobre vacunaciones, operaciones y documentos adjuntos.

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Identificador único |
| `id_mascota` | bigint unsigned | NOT NULL, FOREIGN KEY | Referencia a `mascotas.id` |
| `fecha_ultima_vacuna` | date | NOT NULL | Fecha de la última vacuna aplicada |
| `operaciones` | text | NULLABLE | Descripción de operaciones realizadas |
| `certificado_veterinario` | string(255) | NULLABLE | Ruta al archivo del certificado veterinario |
| `cedula_propietario` | string(255) | NULLABLE | Ruta al archivo de cédula del propietario |
| `created_at` | timestamp | NULLABLE | Fecha de creación |
| `updated_at` | timestamp | NULLABLE | Fecha de última actualización |

**Índices**:
- PRIMARY KEY: `id`
- FOREIGN KEY: `id_mascota`

**Foreign Keys**:
- `id_mascota` → `mascotas.id` (onDelete: CASCADE)

**Relaciones**:
- `belongsTo(Mascota)`: Un registro pertenece a una mascota

**Notas**:
- Los archivos `certificado_veterinario` y `cedula_propietario` se almacenan en `storage/app/public/documentos_mascotas/`
- El campo `fecha_ultima_vacuna` debe ser anterior o igual a la fecha actual
- Los usuarios solo pueden ver/editar registros de sus propias mascotas (excepto administradores)
- Los archivos aceptados son: PDF, JPG, JPEG, PNG (máximo 2MB)

**Estructura de almacenamiento de archivos**:
```
storage/app/public/documentos_mascotas/
  ├── {cedula_usuario}/
  │   ├── {nombre_mascota}_vacunas_{timestamp}.pdf
  │   └── {nombre_mascota}_cedula_{timestamp}.pdf
```

---

### 11.2 Otras Tablas del Sistema

#### Tablas de Usuarios y Autenticación
- `users`: Usuarios principales
- `clientes`: Perfiles de clientes
- `paseadores`: Perfiles de paseadores
- `social_accounts`: Cuentas OAuth
- `password_reset_tokens`: Tokens de recuperación
- `sessions`: Sesiones activas

#### Tablas de Mascotas
- `mascotas`: Información de mascotas
- `razas`: Razas de mascotas
- `vacunas_certificaciones`: Vacunas y certificados

#### Tablas Geográficas
- `departamentos`: Departamentos de Colombia
- `ciudades`: Ciudades
- `barrios`: Barrios por ciudad
- `sectores`: Sectores económicos

#### Tablas de Configuración
- `modules`: Módulos del sistema
- `module_logs`: Logs de módulos
- `backup_configs`: Configuración de backups
- `backup_logs`: Logs de backups
- `database_configs`: Configuración de BD
- `email_configs`: Configuración de email
- `oauth_providers`: Proveedores OAuth
- `configuraciones`: Configuración general

#### Tablas de Permisos
- `roles`: Roles del sistema
- `permissions`: Permisos
- `model_has_roles`: Asignación de roles
- `model_has_permissions`: Asignación de permisos
- `role_has_permissions`: Permisos por rol

#### Otras Tablas
- `tipos_documentos`: Tipos de documentos
- `tipos_empresas`: Tipos de empresas
- `empresas`: Empresas
- `mensaje_de_bienvenidas`: Mensajes de bienvenida
- `paths_documentos`: Rutas de documentos
- `notifications`: Notificaciones

### 11.3 Migraciones

**Total**: 42 migraciones

**Migraciones importantes**:

**Tabla Users**:
- `2014_10_12_000000_create_users_table.php`: Creación inicial de tabla users
- `2025_04_07_114705_add_campos_to_users_table.php`: Agregado de campos adicionales (tipo_documento, cedula, avatar, telefono, whatsapp, fecha_nacimiento, activo)

**Tabla Clientes**:
- `2025_01_22_140625_create_clientes_table.php`: Creación inicial de tabla clientes
- `2025_10_31_112027_add_user_id_to_clientes_table.php`: Agregado de user_id y campos completos (tipo_documento_id, cedula, telefono, whatsapp, fecha_nacimiento, direccion, ciudad_id, barrio_id, avatar)
- `2025_11_04_163415_add_latitud_longitud_to_clientes_table.php`: Agregado de campos de geocodificación
- `2025_11_05_091913_add_conjunto_cerrado_interior_to_clientes_table.php`: Agregado de campos nombre_conjunto_cerrado e interior_apartamento

**Tabla Mascotas**:
- `2025_04_09_173600_create_mascotas_table.php`: Creación inicial con todos los campos
- `2025_01_31_000000_remove_ubicacion_fields_from_mascotas_table.php`: Eliminación de campos de ubicación (direccion, barrio_id, interior_apto) - estos campos se gestionan a nivel de cliente

**Tabla Vacunas y Certificaciones**:
- `2025_04_14_000000_create_vacunas_certificaciones_table.php`: Creación inicial (estructura antigua)
- `2025_11_05_143744_fix_vacunas_certificaciones_table_structure.php`: Corrección de estructura (campos correctos: id_mascota, fecha_ultima_vacuna, operaciones, certificado_veterinario, cedula_propietario)

**Otras migraciones importantes**:
- `2023_11_16_224256_create_permission_tables.php`: Sistema de permisos (Spatie)
- `2025_10_29_150001_create_modules_table.php`: Tabla de módulos
- `2025_01_30_120000_create_database_configs_table.php`: Configuración de BD
- `2025_01_30_130000_create_email_configs_table.php`: Configuración de email
- `2025_01_31_140000_create_backup_configs_table.php`: Configuración de backups

### 11.4 Seeders

**Total**: 10 seeders

**Seeders principales**:
- `DatabaseSeeder`: Seeder principal
- `ModuleSeeder`: Registro de módulos
- `RoleSeeder`: Creación de roles
- `PermissionSeeder`: Creación de permisos

---

## 12. VISTAS Y FRONTEND

### 12.1 Estructura de Vistas

**Total**: 142 archivos Blade

**Layouts**:
- `layouts/app.blade.php`: Layout principal
- `layouts/navbar.blade.php`: Barra de navegación
- `layouts/sidebar.blade.php`: Menú lateral
- `layouts/footer.blade.php`: Pie de página

**Vistas por Módulo**:
- `auth/`: Autenticación (login, register, reset)
- `cliente/`: Dashboard y perfil de cliente
- `admin/`: Dashboard de admin
- `superadmin/`: Dashboard y configuraciones
- `mascota/`: CRUD de mascotas
- `vacunas_certificaciones/`: CRUD de vacunas
- `modules/`: Gestión de módulos

### 12.2 Tecnologías Frontend

#### CSS
- **Tailwind CSS**: Framework CSS utility-first
- **AdminLTE**: Template de administración (integración parcial)

#### JavaScript
- **Vite**: Build tool y HMR
- **Axios**: Peticiones HTTP
- **DataTables**: Tablas interactivas
- **SweetAlert2**: Alertas personalizadas
- **D3.js**: Visualización de árbol genealógico

#### Componentes Blade
- Componentes reutilizables en `resources/views/components/`
- Partials para formularios comunes (`user/form.blade.php`)

### 12.3 Características de UI/UX

- **Responsive Design**: Adaptable a móviles y tablets
- **Progress Bar**: Indicador de completitud de perfil
- **Validación en Tiempo Real**: Feedback inmediato
- **Loading States**: Indicadores de carga
- **Mensajes Flash**: Notificaciones de éxito/error
- **Modales**: Para confirmaciones y formularios

---

## 13. CONFIGURACIONES

### 13.1 Archivos de Configuración

#### `config/permission.php`
Configuración de Spatie Permission (roles, permisos, tablas).

#### `config/fortify.php`
Configuración de Laravel Fortify (autenticación, registro, verificación).

#### `config/services.php`
Configuración de servicios externos (OAuth providers).

#### `config/filesystems.php`
Configuración de almacenamiento de archivos.

#### `config/mail.php`
Configuración de envío de correos (SMTP).

### 13.2 Variables de Entorno

**Archivo**: `.env`

**Variables importantes**:
```env
APP_NAME=ModuStackPet
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=usuario
DB_PASSWORD=contraseña

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=contraseña

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
```

### 13.3 Configuraciones Dinámicas

El sistema permite configuraciones dinámicas desde la base de datos:

- **Database Configs**: Configuración de conexiones a BD
- **Email Configs**: Configuración SMTP
- **OAuth Providers**: Configuración de proveedores OAuth
- **Backup Configs**: Configuración de backups

---

## 14. APIS Y SERVICIOS EXTERNOS

### 14.1 Nominatim (OpenStreetMap)

**Uso**: Geocodificación de direcciones

**Endpoint**: `https://nominatim.openstreetmap.org/search`

**Implementación**: `GeocodingService`

**Características**:
- Búsqueda estructurada y libre
- Rate limiting (1 segundo entre llamadas)
- Parseo de direcciones colombianas
- Fallbacks y variaciones

### 14.2 OAuth Providers

**Proveedores soportados**:
- Google
- Facebook
- GitHub
- Otros configurados

**Implementación**: Laravel Socialite

**Flujo**:
```
Usuario → Click en "Login con Google"
    ↓
Redirección a proveedor OAuth
    ↓
Autorización del usuario
    ↓
Callback con token
    ↓
Creación/actualización de usuario
    ↓
Redirección a dashboard
```

### 14.3 Rutas API Internas

**Rutas JSON**:
- `/barrios-engativa`: Obtener barrios de Engativá
- `/barrios-por-ciudad/{ciudadId}`: Obtener barrios por ciudad

---

## 15. SEGURIDAD

### 15.1 Medidas de Seguridad Implementadas

#### Autenticación
- Verificación de email obligatoria
- Contraseñas hasheadas (bcrypt)
- Tokens CSRF en formularios
- Rate limiting en login
- Timeout de sesiones

#### Autorización
- Sistema de roles y permisos granular
- Validación de propiedad (clientes solo ven sus datos)
- Middleware de verificación de módulos
- Políticas de acceso

#### Validación
- Form Requests para validación de datos
- Validación en frontend y backend
- Sanitización de inputs
- Validación de tipos de archivo

#### Protección de Archivos
- Almacenamiento fuera de web root (storage)
- Validación de tipos MIME
- Límites de tamaño de archivo
- Nombres únicos para evitar sobrescritura

#### Seguridad de Base de Datos
- Prepared statements (Eloquent)
- Validación de existencia de registros
- Foreign keys para integridad referencial
- Transacciones para operaciones críticas

### 15.2 Mejores Prácticas de Seguridad

- **Principio de Menor Privilegio**: Usuarios solo tienen acceso a lo necesario
- **Validación en Múltiples Capas**: Frontend + Backend
- **Logging de Actividades**: Registro de acciones importantes
- **Backups Regulares**: Sistema automatizado de backups
- **Actualizaciones**: Mantenimiento de dependencias actualizadas

---

## 16. FLUJOS PRINCIPALES

### 16.1 Flujo de Registro

```
1. Usuario accede a /register
2. Completa formulario o selecciona OAuth
3. Sistema crea User
4. Asigna rol "Cliente" por defecto
5. Crea perfil Cliente (opcional)
6. Envía email de verificación
7. Redirige a dashboard con restricciones
8. Usuario verifica email
9. Acceso completo habilitado
```

### 16.2 Flujo de Login

```
1. Usuario accede a /login
2. Ingresa credenciales o selecciona OAuth
3. Sistema valida credenciales
4. Verifica email (si no verificado, redirige)
5. Verifica perfil completo (ClienteDataVerificationService)
6. Redirige según rol:
   - Superadmin → superadmin.dashboard
   - Admin → admin.dashboard
   - Cliente → cliente.dashboard (o verificación-datos)
   - Paseador → paseador.dashboard
```

### 16.3 Flujo de Gestión de Mascota

```
1. Usuario accede a /mascotas/create
2. Completa formulario (automáticamente asigna user_id)
3. Sube foto (opcional)
4. Sistema guarda con transacción
5. Redirige a /mascotas con mensaje de éxito
6. Usuario puede ver, editar o eliminar
```

### 16.4 Flujo de Vacunas y Certificaciones

```
1. Usuario accede a /vacunas_certificaciones/create
2. Selecciona mascota (solo sus propias mascotas)
3. Completa datos y sube archivos
4. Sistema valida propiedad
5. Guarda con transacción
6. Redirige a listado (filtrado por usuario)
```

### 16.5 Flujo de Backup

```
1. Superadmin accede a /superadmin/backup-configs
2. Configura BD destino
3. Ejecuta backup
4. BackupService:
   a. Valida que no sea BD de producción
   b. Crea/verifica BD destino
   c. Copia estructura de tablas
   d. Copia datos
   e. Genera log
5. Notifica resultado
```

### 16.6 Flujo de Geocodificación

```
1. Cliente actualiza dirección en perfil
2. ClienteController llama a GeocodingService
3. GeocodingService:
   a. Parsea dirección colombiana
   b. Consulta Nominatim API
   c. Si falla, intenta variaciones
   d. Retorna coordenadas
4. Sistema guarda latitud/longitud
5. Actualiza perfil completo
```

---

## 17. MEJORES PRÁCTICAS

### 17.1 Código

- **PSR-12**: Estándar de codificación PHP
- **Laravel Conventions**: Convenciones de Laravel
- **SOLID Principles**: Principios de diseño
- **DRY**: Reutilización de código
- **Comentarios**: Documentación en código complejo

### 17.2 Base de Datos

- **Migraciones**: Todas las cambios en BD vía migraciones
- **Seeders**: Datos iniciales y de prueba
- **Foreign Keys**: Integridad referencial
- **Índices**: Optimización de consultas
- **Soft Deletes**: Eliminación lógica donde aplica

### 17.3 Testing

- **Unit Tests**: Tests de modelos y servicios
- **Feature Tests**: Tests de funcionalidades completas
- **PHPUnit**: Framework de testing

### 17.4 Deployment

- **Environment**: Variables de entorno separadas
- **Cache**: Optimización de configuraciones
- **Logs**: Sistema de logging estructurado
- **Monitoring**: Monitoreo de errores y performance

### 17.5 Mantenimiento

- **Versionado**: Control de versiones con Git
- **Documentación**: Documentación técnica actualizada
- **Backups**: Backups regulares automatizados
- **Updates**: Actualización de dependencias

---

## 18. CONCLUSIONES

ModuStackPet es un sistema robusto y modular desarrollado con Laravel 11, que implementa las mejores prácticas de desarrollo web moderno. El sistema ofrece:

- ✅ Arquitectura escalable y mantenible
- ✅ Sistema de módulos flexible
- ✅ Seguridad multicapa
- ✅ Integración con servicios externos
- ✅ Experiencia de usuario optimizada
- ✅ Código limpio y documentado

### 18.1 Fortalezas

1. **Modularidad**: Sistema de módulos activables/desactivables
2. **Seguridad**: Múltiples capas de protección
3. **Escalabilidad**: Arquitectura preparada para crecimiento
4. **Mantenibilidad**: Código organizado y documentado
5. **UX**: Interfaz intuitiva y responsive

### 18.2 Áreas de Mejora Futura

1. **Testing**: Aumentar cobertura de tests
2. **API REST**: Implementar API RESTful completa
3. **Notificaciones Push**: Implementar notificaciones en tiempo real
4. **Cache**: Implementar cache más agresivo
5. **Monitoreo**: Sistema de monitoreo y alertas

---

## 19. ANEXOS

### 19.1 Comandos Artisan Útiles

```bash
# Migraciones
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed

# Cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimización
php artisan optimize
php artisan config:cache
php artisan route:cache

# Testing
php artisan test
```

### 19.2 Estructura de Archivos Críticos

```
app/
├── Http/Controllers/
│   ├── ClienteController.php          # Gestión de clientes
│   ├── MascotaController.php          # Gestión de mascotas
│   ├── VacunasCertificacionesController.php  # Vacunas
│   └── ArbolGenealogicoController.php # Árbol genealógico
├── Services/
│   ├── BackupService.php              # Backups
│   ├── GeocodingService.php           # Geocodificación
│   └── ClienteDataVerificationService.php  # Verificación
└── Models/
    ├── User.php                       # Usuario principal
    ├── Cliente.php                    # Cliente
    ├── Mascota.php                    # Mascota
    └── VacunasCertificacione.php      # Vacunas
```

### 19.3 Referencias

- **Laravel Documentation**: https://laravel.com/docs
- **Spatie Permission**: https://spatie.be/docs/laravel-permission
- **Laravel Fortify**: https://laravel.com/docs/fortify
- **Laravel Socialite**: https://laravel.com/docs/socialite
- **Nominatim API**: https://nominatim.org/release-docs/latest/api/Overview/

---

**Fin del Documento Técnico**

---

*Este documento fue generado automáticamente basado en el análisis exhaustivo del código fuente del proyecto ModuStackPet. Para actualizaciones o correcciones, contactar al equipo de desarrollo.*

