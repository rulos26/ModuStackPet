# Informe de módulos de ModuStackPet — estado real, relaciones, puntaje y mejoras

Fecha: 2026-10-04 · Agente: Claude · Base: `origin/main` @ `824e7b17` · Rama: `ia/claude/informe-modulos`
Alcance: **todo lo que existe hoy en el código** (no lo que dice la documentación). Solo lectura de código; 329 pruebas existentes pasan. Para confirmar dos sospechas escribí pruebas temporales (ya borradas, no quedan en el repo); los resultados se marcan **[VERIFICADO]**.

---

## 0. Lo primero que debes saber (resumen crítico)

1. **El proyecto no está al "85 %/100 % por módulo" que dicen `ModuStackPet_Informe_Completo.md` y `DOCUMENTO_TECNICO_MODUSTACKPET.md`.** Esos documentos marcan 10 módulos al 100 %; el código muestra que lo realmente terminado y sólido es una **base de gestión** (autenticación, mascotas con documentos, catálogos geográficos y herramientas de administración). Lo que el nombre del producto promete (*paseos, reservas, pagos, calificaciones, chat*) **no existe**: `docs/ModuStackPet_Informe_Completo.md` mismo lo lista como 0 %.
2. **Sobre tus ejemplos:**
   - **"Módulo de la DIAN": no existe.** Solo hay campos `nit` y `dv` (dígito de verificación) en *Empresas*, y el `dv` solo se valida como "1 carácter" (no se calcula ni verifica con el algoritmo de la DIAN). No hay facturación electrónica, ni resoluciones, ni integración alguna.
   - **"Módulo de cuentas": no existe como tal.** Lo más cercano son las *cuentas sociales* (`social_accounts`, login con Google/Facebook) y la tabla de usuarios. No hay cuentas por cobrar, saldos, planes ni pagos.
   - **Usuarios, Paseadores, Razas, Departamentos: sí existen**, con calidades muy distintas (ver fichas: Razas y Departamentos son catálogos correctos y simples; Usuarios/Admin tienen defectos serios; Paseadores es casi solo una ficha).
3. **Hallazgos graves nuevos de este informe** (más abajo, con evidencia):
   - **[VERIFICADO] Un usuario *Admin* puede borrar a un *Superadmin* y a sí mismo** (`AdminController::destroy` compara contra `'superadmin'` en minúscula; el rol real es `Superadmin`).
   - Los **documentos de las mascotas, certificados veterinarios y copias de la cédula del propietario se guardan en el disco *público*** (`storage/app/public` → `/storage/…`): quien conozca o adivine la URL los descarga sin iniciar sesión; el chequeo de `descargar()` se salta con la URL directa.
   - **El login social entra a cuentas existentes por correo sin comprobar que el proveedor lo haya verificado, y no respeta `activo=false`**: un usuario desactivado sigue entrando por Google/Facebook.
   - **[VERIFICADO] Un *Cliente* puede abrir y usar la gestión de *requisitos documentales*** (`/admin/document-requirements`): índice 200, formulario de creación 200 y **eliminó un requisito** (la ruta dice "solo administradores" pero solo exige `auth`+`verified`, y el controlador no comprueba rol).
   - **[VERIFICADO] Rutas rotas**: `admin.users.toggle-status` apunta a un método que no existe (500) y crear usuarios desde el panel Admin falla con "no existe el rol `admin`" en bases con collation sensible a mayúsculas.
4. **Cosas que revisé y descarté** (para que no te alarmes): subir un `.php` como "avatar" de mascota **se rechaza** [VERIFICADO]; un nombre de mascota con `../` **no** escribe fuera de la carpeta [VERIFICADO].

**Promedio de los 34 módulos existentes: 4,8 / 10** (ver §3). **Como base técnica ~5/10; frente al producto que se promete (paseos, reservas, pagos) ~2/10**, porque esa parte no existe. Madurez: *buena base de acceso y pruebas tras las tareas 001-045, pero dominio de negocio incompleto, mucha duplicación, huecos de autorización que aún quedan y deuda de frontend*.

---

## 1. Cómo se calificó (criterio, no una fórmula exacta)

Puntaje 1-10 por módulo, ponderando: **funcionalidad real** (35 %), **seguridad/privacidad** (25 %), **calidad de código** (20 %), **pruebas** (10 %), **UX y mantenibilidad** (10 %). Referencia: 9-10 = listo para producción sin reparos; 7-8 = sólido con mejoras menores; 5-6 = funciona pero con deuda relevante; 3-4 = parcial o con defectos serios; 1-2 = roto, muerto o casi inexistente.
"Pruebas" = pruebas de **comportamiento** que ejercitan el módulo (no solo "la ruta exige login").

Inventario medido: 34 controladores (7 076 líneas), 29 modelos, 149 vistas, 48 migraciones, 34 archivos de prueba (329 pruebas), 3 políticas, 3 servicios de dominio + `BackupService`, 3 comandos Artisan, 2 componentes Livewire, 28 módulos declarados en `ModuleSeeder`.

---

## 2. Mapa de relaciones entre módulos

```
                         ┌──────────────── PLATAFORMA ─────────────────┐
  Autenticación ──► Roles/Permisos ──► Sistema de Módulos ──► (on/off de rutas)
   │  ▲ Login social            │            │ Livewire (menú / toggle)
   ▼  │                         ▼            ▼
  Usuarios ─┬─ Superadmin       Dashboards + Bienvenida (por rol)
            ├─ Admin            
            ├─ Cliente ─────────► Mascotas ──► Razas
            │     │                  │  │
            │     └─ Árbol           │  ├─► Vacunas y Certificaciones ─┐
            │        genealógico     │  └─► Documentos de Mascotas ◄───┤
            └─ Paseador (ficha)      │            ▲                    │
                                     │      Requisitos documentales    │
  Ubicaciones: Departamentos ► Ciudades ► Barrios ──► Cliente/Empresa/Paseador (ciudad_id, barrio_id)
                     ▲ Geolocalización (Nominatim) ──► Cliente (latitud/longitud)
  Empresas ◄── Tipos de empresa, Sectores, Ciudades, Departamentos ──► PDF
  Rutas de documentos / Tipo de documento ──► Usuarios (cédula) y Documentos
  Notificaciones ◄── Verificación de correo, credenciales de paseador, backups, módulos
  Herramientas Superadmin (solo APP_ENV=local): Config BD · Config correo · Backup · OAuth ·
                         Migraciones · Seeders · Limpieza  ──► escriben .env / ejecutan Artisan
```

Relaciones de datos reales (FK en migraciones): `mascotas.user_id→users`, `mascotas.raza_id→razas`, `vacunas_certificaciones→mascotas`, `mascota_documents→mascotas, document_requirements, users`, `clientes/paseadores.user_id→users`, `empresas→tipos_empresas, sectores`, `ciudades.departamento_id→departamentos`, `empresas/clientes.ciudad_id→ciudades`, `module_logs/module_verifications→modules`.
**Observación crítica:** `Empresas` **no tiene relación con ningún usuario, cliente ni paseador**: es un catálogo aislado. Y `Mascota` tiene columnas `barrio_id`, `direccion`, `interior_apto` en la migración pero **no existe la relación `barrio()`** en el modelo (las vistas la usan: era la causa del 500 del PDF corregido en 042).

---

## 3. Tabla resumen (ordenada por puntaje)

| # | Módulo | Pje | Estado real | Riesgo principal |
|---|---|:-:|---|---|
| M-01 | Autenticación (Fortify, verificación, reset) | **7** | Funcional y probado | 2FA apagado; mensajes/flujo cercanos al estándar |
| M-02 | Sistema de módulos (on/off) | **7** | Sólido, 28 pruebas | No gobierna todo (3 módulos sin efecto) |
| M-03 | Ciudades | **7** | Funcional, probado, protegido | Id externo vs local en Empresas |
| M-04 | Mascotas | **7** | Funcional con política y pruebas | Avatar público por cédula; sin SoftDeletes |
| M-05 | Documentos de mascotas | **6** | Flujo completo (subir/validar/aprobar/descargar) | **Archivos en disco público** |
| M-06 | Vacunas y certificaciones | **6** | Funcional con transacciones | **Cédula del dueño en disco público**; sin recordatorios |
| M-07 | Razas | **6** | Catálogo correcto | Sin SoftDeletes; datos de cuidado limitados |
| M-08 | Requisitos documentales | **4** | Funcional, SoftDeletes | **[VERIFICADO] Cualquier usuario verificado (Cliente) puede administrarlos** |
| M-09 | Departamentos | **6** | Catálogo correcto | `toggleStatus` sin ruta; poca prueba |
| M-10 | Sectores | **6** | Consolidado y probado | Borrado lógico vs `unique` |
| M-11 | Tipos de empresa | **6** | Consolidado y probado | Sin regla `unique` |
| M-12 | Configuración y tiempo de sesión | **6** | Funciona | Pocas pruebas |
| M-13 | Dashboards y mensaje de bienvenida | **5** | Funciona | Datos con `\r\n`, `explode('.')` |
| M-14 | Roles y permisos | **5** | Roles sí; permisos **no se usan** | Strings de rol con mayúsculas inconsistentes |
| M-15 | Superadmin (gestión de usuarios y dashboard) | **5** | Funciona | Duplica a `UserController` y `AdminController` |
| M-16 | Cliente (perfil, verificación de datos) | **5** | Funcional | 21 logs `DEBUG` con datos personales; 621 líneas |
| M-17 | Empresas | **5** | CRUD + PDF | Aislada; DV de la DIAN sin validar |
| M-18 | OAuth: administración de proveedores | **5** | Funcional y complejo | "Simuladores" y test-logs en producción |
| M-19 | Tipo de documento | **5** | Catálogo simple | Poca prueba |
| M-20 | Login social (SocialAuthController) | **4** | Funciona | **Toma de cuenta por correo; ignora `activo`** |
| M-21 | Usuarios (`UserController`) | **4** | Solo crea Paseadores | Borrado duro, datos desincronizados |
| M-22 | Rutas de documentos (`paths-documentos`) | **4** | Propósito poco claro | Lista usuarios con correo/cédula; binding roto |
| M-23 | Barrios | **4** | Solo Engativá en la práctica | Relación invertida; endpoints con valores fijos |
| M-24 | Geolocalización (Nominatim) | **4** | Geocodifica una dirección | Sin caché, bloqueante, sin pruebas |
| M-25 | Reportes PDF | **4** | 3 PDFs (demo, mascota, empresa) | No hay reportes de negocio |
| M-26 | Notificaciones | **4** | 4 notificaciones sueltas | Credenciales en texto plano por correo |
| M-27 | Configuración de correo | **4** | Funciona (local) | Escribe `.env` desde la web |
| M-28 | Configuración de BD | **4** | Funciona (local) | Escribe `.env`; config de BD guardada en la propia BD |
| M-29 | Migraciones / Seeders / Limpieza (web) | **4** | Solo con `APP_ENV=local` | `migrate:refresh` de un clic; inútiles en producción |
| M-30 | Backup de BD | **3** | Copia tablas a otra BD, manual | Sin programación, restauración ni retención |
| M-31 | Paseadores | **3** | Una ficha + bienvenida | Sin servicio, tarifas ni calificaciones |
| M-32 | Árbol genealógico | **3** | Grafo cliente→mascotas | No es genealógico; sin pruebas |
| M-33 | Admin (`AdminController`) | **2** | Parcialmente roto | **Borra Superadmin**; rutas rotas; sin menú |
| M-34 | Perfil de usuario (`ProfileController`) | **2** | Código sin ruta | Enlace "Perfil" apunta a `#` |
| — | **DIAN / Facturación** | **0** | **No existe** | Solo `nit`/`dv` |
| — | **Cuentas / Pagos / Reservas / Calificaciones / Chat / API** | **0** | **No existen** | Es el núcleo comercial prometido |

**Promedio de los 34 módulos existentes: 4,8 / 10** (suma 162 / 34). Los 7 módulos de negocio ausentes (DIAN, cuentas, reservas, pagos, calificaciones, chat, API) cuentan como 0 y no están en el promedio.

---

## 4. Fichas por módulo

Formato: **qué es** · **se relaciona con** · **crítica con evidencia** · **mejoras (1-2)**.

### M-01 Autenticación — 7/10
- **Qué es:** Laravel Fortify como stack oficial (registro, login, reset, verificación de correo) con respuestas propias (`CustomLoginResponse`, `CustomRegisterResponse`, redirección por rol, bloqueo de usuarios inactivos). `FortifyServiceProvider`, `app/Http/Responses/*`. 2FA apagado por decisión. Pruebas: `FortifyAuthTest` (10) + `FortifyVerificationRedirectTest`.
- **Se relaciona con:** Usuarios, Roles, Dashboards, Notificaciones (correo de verificación), Login social.
- **Crítica:** la autenticación ya es coherente y probada (SEG-031/031b); queda deuda: **sin 2FA** (el Superadmin puede ejecutar herramientas críticas), reglas de contraseña solo `Password::default()`, el registro crea usuarios `Cliente` y envía verificación pero **no hay límite por IP/correo en registro ni reset** más allá del limitador de login, y la ruta `GET /logout` (sin CSRF) sigue activa junto al `POST` de Fortify.
- **Mejoras:** (1) activar 2FA (TOTP) al menos para Superadmin/Admin; (2) quitar `GET /logout` y añadir *throttle* a registro/reset/verificación.

### M-02 Sistema de módulos (activar/desactivar) — 7/10
- **Qué es:** `Module`, `ModuleLog`, `ModuleVerification`, `CheckModuleStatus`, `ModuleController` (activar/desactivar con código de verificación por correo), Livewire `ToggleButton` y `ModulesMenu`, comandos `modules:sync` y `modules:clean-verifications`. ~28 pruebas (las más abundantes del proyecto).
- **Se relaciona con:** casi todas las rutas (`mod:<slug>` en el middleware), el menú lateral y Notificaciones.
- **Crítica:** es lo mejor probado, pero **no gobierna todo**: siete slugs del seeder (`modulos`, `usuarios`, `migraciones`, `seeders`, `geolocalizacion`, `notificaciones`, `tipos-empresas`) **no aparecen en ninguna ruta** con `CheckModuleStatus` (apagarlos no hace nada; `migraciones` y `seeders` dependen en cambio del interruptor `admin_tools`); el middleware **crea módulos automáticamente** para cualquier slug nuevo (útil en desarrollo, confuso en producción); `modules:clean-verifications` **no está programado** (no hay scheduler en Docker ni en `routes/console.php`), así que los códigos caducados se acumulan.
- **Mejoras:** (1) sincronizar `ModuleSeeder` con las rutas reales (un test que falle si un slug no tiene ruta o viceversa); (2) programar `modules:clean-verifications` y añadir un worker/scheduler al `docker-compose`.

### M-03 Ciudades — 7/10
- **Qué es:** CRUD de municipios (`Ciudad`, `CiudadController`, `CiudadeRequest`); consolidado y protegido en 034/034b (auth + verified + Superadmin|Admin); ~17 pruebas.
- **Se relaciona con:** Departamentos (`departamento_id`), Barrios, Clientes, Empresas, Paseadores, formulario de usuario.
- **Crítica:** bien probado; pero `empresa/form.blade.php` carga ciudades desde **`api-colombia.com` en el navegador** (ids de la API externa) mientras la tabla local usa otros ids: riesgo de guardar un `ciudad_id` que no corresponde; el controlador y los mensajes siguen con el nombre/idioma de generador (`Ciudade`, vistas `ciudade/`).
- **Mejoras:** (1) eliminar la dependencia de la API externa y servir ciudades desde la BD (endpoint propio con caché); (2) renombrar `Ciudade*` (request/vistas) a `Ciudad*`.

### M-04 Mascotas — 7/10
- **Qué es:** CRUD con `MascotaPolicy` (Cliente solo las suyas; Admin/Superadmin todas; Paseador ninguna), `MascotaRequest`, avatar. ~15 pruebas (`MascotaAccessControlTest`, `MascotaFlowsTest`).
- **Se relaciona con:** Clientes/Usuarios (dueño), Razas, Vacunas, Documentos de mascotas, PDF, Árbol.
- **Crítica:** es el módulo central y está bien resguardado. Pero: el avatar se guarda como `public/avatars/<cédula>/mascotas/<nombre>.<ext>` → **la cédula del dueño aparece en la URL pública** y dos mascotas con el mismo nombre **se pisan** la imagen; sin `SoftDeletes` (borrar una mascota arrastra por `cascade` sus datos); `direccion`, `barrio_id` e `interior_apto` existen en BD pero no en `$fillable` ni en el modelo (columnas fantasma); la `edad` es un entero manual aunque se guarda `fecha_nacimiento`.
- **Mejoras:** (1) guardar avatares en el disco privado con nombre aleatorio (`Str::uuid`) y servirlos por ruta autorizada; (2) añadir `SoftDeletes`, calcular edad desde `fecha_nacimiento` y completar/limpiar las columnas fantasma.

### M-05 Documentos de mascotas — 6/10
- **Qué es:** subida de documentos por requisito, validación (`DocumentValidationService`: formato, tamaño, fechas), aprobación/rechazo por Admin, descarga, reemplazo con versión previa (`MascotaDocumentController`, 493 líneas). 8 pruebas de flujo.
- **Se relaciona con:** Mascotas, Requisitos documentales, Usuarios (quien sube/aprueba), Notificaciones.
- **Crítica:** el flujo de negocio es el más completo del dominio, **pero los archivos se guardan en `Storage::disk('public')`**: el control de `descargar()` (403 si no eres dueño) se evita abriendo `/storage/<ruta>` directamente. La ruta se construye con datos previsibles (cédula + nombre + tipo + `time()`), así que es adivinable. Se usa `getClientOriginalExtension()` (dato del cliente) tras validar tipo MIME (bien) y el documento anterior se **borra físicamente** al reemplazar (sin historial).
- **Mejoras:** (1) mover a disco **privado** (`local`) y servir solo vía `descargar()` con URL firmada/temporal; (2) conservar versiones (SoftDeletes en archivo) y registrar quién aprobó/rechazó y por qué en un log inmutable.

### M-06 Vacunas y certificaciones — 6/10
- **Qué es:** registro de vacunas por mascota con adjuntos (certificado veterinario y **copia de la cédula del propietario**), transacciones y limpieza de archivos si falla; 7 pruebas de flujo. 396 líneas.
- **Se relaciona con:** Mascotas, Usuarios (propietario), PDF (parcial).
- **Crítica:** mismo problema de privacidad que M-05, **más grave**: la **imagen de la cédula** queda en `storage/app/public/documentos_mascotas/<cédula>/…`. La autorización se repite como `if (!hasRole('Superadmin') && !hasRole('Admin'))` en cada método en lugar de usar una política. **No hay fechas de próxima dosis ni recordatorios**: para un sistema de mascotas, el valor de las vacunas es avisar cuándo vencen y hoy no existe. Controlador de 396 líneas con lógica de archivos, permisos y negocio mezclada.
- **Mejoras:** (1) disco privado + `VacunaPolicy`; (2) campo `proxima_dosis`, tarea programada y notificación de vencimiento (requiere scheduler/worker).

### M-07 Razas — 6/10
- **Qué es:** catálogo (`Raza`, `RazaController`, solo Superadmin) con campos de cuidado especial y `aplicaParaRaza` usado por requisitos documentales (corregido en tarea 037). `RazaCuidadosEspecialesTest` (4).
- **Se relaciona con:** Mascotas (`raza_id`), Requisitos documentales (`aplicaParaRaza`), Árbol (tipo de mascota).
- **Crítica:** catálogo correcto y sencillo, protegido (SEG-040). Sin `SoftDeletes` (borrar una raza pone `raza_id` en null en mascotas: pierde información); la clasificación "peligrosa" depende de texto libre y no de un campo normalizado; solo Superadmin la edita aunque Admin gestiona mascotas.
- **Mejoras:** (1) campo `categoria`/`es_peligrosa` booleano con migración y tests; (2) borrado lógico y semilla de razas comunes (hoy depende de datos cargados a mano).

### M-08 Requisitos documentales — 4/10
- **Qué es:** define qué documentos exige el sistema (tipo, formatos, vencimiento, razas aplicables) con `DocumentRequirement`, `...Log`, SoftDeletes. `DocumentRequirementSeeder`. Pocas pruebas directas (~6 menciones).
- **Se relaciona con:** Documentos de mascotas, Tipo de documento, Razas, Mascotas.
- **Crítica:** buen modelo (con log de cambios), pero **hueco de autorización [VERIFICADO con prueba temporal]**: las rutas viven en `Route::middleware(['auth','verified'])->prefix('admin')` (el comentario dice "solo administradores") **sin `role:`**, y `DocumentRequirementController` no tiene ninguna comprobación de rol (0 `hasRole`/`authorize`). Un usuario con rol **Cliente** obtuvo **200 en el índice y en el formulario de creación** y **logró eliminar un requisito** (la respuesta final fue 500 por un error posterior, pero el registro quedó borrado). Como los requisitos definen qué documentos deben subir todos los dueños, cualquier usuario verificado puede alterar el flujo documental de todo el sistema. Además casi no hay pruebas de comportamiento (solo `aplicaParaRaza`).
- **Mejoras:** (1) **urgente**: `role:Superadmin|Admin` en el grupo y prueba de acceso por rol (como SEG-040/041); (2) pruebas de CRUD, de `toggleStatus` y del 500 posterior al borrado.

### M-09 Departamentos — 6/10
- **Qué es:** catálogo (`Departamento`, `DepartamentoController`, auth+verified+Superadmin|Admin, 041), con SoftDeletes y semilla SQL (`DepartamentoSqlSeeder`).
- **Se relaciona con:** Ciudades (`hasMany`), Empresas, Clientes.
- **Crítica:** correcto. `toggleStatus()` existe pero **sin ruta** (código muerto); el mismo bug histórico de columna `id` vs `id_departamento` apareció en Ciudades y aquí solo se evitó por suerte (sin pruebas de CRUD que lo garanticen); la UI de listado carga DataTables+pdfmake por CDN por pantalla.
- **Mejoras:** (1) pruebas de caracterización de CRUD (como Ciudades) y quitar o enrutar `toggleStatus`; (2) unificar el campo de estado (`estado` entero vs booleano en otros catálogos).

### M-10 Sectores — 6/10
- **Qué es:** catálogo consolidado en 035 (`Sector`, SoftDeletes, 20/pág., `findOrFail`), con pruebas de caracterización. Relación con `Empresa`.
- **Crítica:** limpio; falta que el *show* imprima el nombre y los mensajes están en inglés ("Sectore created successfully"); la unicidad ya ignora eliminados (corrección posterior).
- **Mejoras:** (1) traducir mensajes y completar la vista *show*; (2) añadir ordenamiento/búsqueda (hoy lista plana).

### M-11 Tipos de empresa — 6/10
- **Qué es:** catálogo consolidado en 036 (`TipoEmpresa`), pruebas de caracterización.
- **Crítica:** **sin regla `unique`** (se pueden crear "SAS" duplicados); mensajes en inglés.
- **Mejoras:** (1) regla `unique:tipos_empresas,nombre,{id},id,deleted_at,NULL`; (2) semilla de tipos legales colombianos (SAS, LTDA, S.A., persona natural…).

### M-12 Configuración y tiempo de sesión — 6/10
- **Qué es:** `Configuracion` (clave/valor), `ConfiguracionController` (tiempo de sesión) y middleware `SessionTimeout`. `SessionTimeoutTest` (2).
- **Crítica:** mecanismo útil y probado a nivel básico; solo expone el timeout (no hay una configuración general del sistema: nombre, correo de soporte, política de contraseñas); `updateSessionTimeout` sin límites claros de valor mínimo/máximo *(verificar)*.
- **Mejoras:** (1) validar rangos (p. ej. 5-480 min) con prueba; (2) ampliar a ajustes reales del negocio (zona horaria, datos de contacto, textos legales).

### M-13 Dashboards y mensaje de bienvenida — 5/10
- **Qué es:** un dashboard por rol (`login_Superadmin`/`login_Admin`/`login_Cliente`/`login_Paseador`) y el CRUD `MensajeDeBienvenida` (solo Superadmin) que alimenta el texto de inicio por rol.
- **Se relaciona con:** Autenticación (redirección), Roles, Módulos (acciones rápidas).
- **Crítica:** cumple, pero los cuatro dashboards **solo muestran una bienvenida y tarjetas de accesos**; no hay métricas (mascotas por cliente, documentos pendientes, vacunas por vencer). El texto se parte con `explode('.')` para fingir párrafos (hack frágil) y el dato guardado tenía `\r\n` literales (mitigado en la vista, no en el origen). Rol Admin y Paseador **sin menú lateral** (Admin comentado, Paseador no incluido: ver informe unificado de frontend).
- **Mejoras:** (1) un dashboard útil por rol (pendientes de aprobación para Admin; mascotas y vencimientos para Cliente); (2) editor de bienvenida que guarde HTML/markdown sanitizado en lugar de depender de puntos.

### M-14 Roles y permisos — 5/10
- **Qué es:** Spatie Permission (4 roles: Superadmin, Admin, Cliente, Paseador), `roleSeeder` con permisos tipo `departamentos.index`, `RoleAssignmentController` (solo Superadmin), `MascotaPolicy`, `ModulePolicy`, `UserPolicy`.
- **Se relaciona con:** todo (middleware `role:`).
- **Crítica:** **la matriz de permisos se siembra pero nunca se consulta** (no hay `can()`, `@can` ni `permission:` en el código; solo roles), así que el sistema de permisos es decorativo. Los nombres de rol se escriben a mano en ≥ 100 sitios con **mayúsculas inconsistentes** (`'Superadmin'` vs `'superadmin'`/`'admin'`): ya causó el defecto de M-33 **[VERIFICADO]**. Solo 3 políticas para 30+ recursos; `UserPolicy` no se usa en `UserController`.
- **Mejoras:** (1) constantes/enum de roles (`Role::SUPERADMIN`) y una prueba que falle ante cualquier literal de rol; (2) decidir: o se usan permisos reales (`->middleware('can:…')`) o se eliminan del seeder.

### M-15 Superadmin (usuarios del panel y dashboard) — 5/10
- **Qué es:** `SuperadminController` (crear/editar/borrar/activar usuarios, cambiar contraseña, dashboard) bajo `/superadmin/*` con `auth, verified, role:Superadmin`; `SuperadminAccessTest`.
- **Se relaciona con:** Usuarios, Roles, Dashboards.
- **Crítica:** es la gestión de usuarios **más sana** (impide borrarse a sí mismo, `syncRoles`, Hash); pero convive con **otras tres** (UserController, AdminController y los métodos muertos de ClienteController/PaseadorController): cuatro implementaciones de "gestionar usuarios" con reglas distintas; `login_Superadmin` y `create/store/destroy` hoy sin ruta (código muerto).
- **Mejoras:** (1) unificar la gestión de usuarios en **un** servicio/controlador con políticas por rol; (2) borrar el código sin ruta.

### M-16 Cliente (perfil y verificación de datos) — 5/10
- **Qué es:** `ClienteController` (621 líneas), `ClienteDataVerificationService` (porcentaje de perfil completo), perfil con dirección, ciudad, barrio y geocodificación.
- **Se relaciona con:** Usuarios, Mascotas, Ubicaciones, Geolocalización, Árbol.
- **Crítica:** funcionalidad real y útil (porcentaje de completitud), pero: **21 líneas de log `DEBUG`** que escriben dirección, coordenadas y datos personales en `storage/logs` (privacidad); controlador "dios" mezcla perfil, geocodificación, avatar y **métodos de administración sin ruta** (`index_admin`, `store`, `update_admin`…); **sin pruebas** de comportamiento.
- **Mejoras:** (1) eliminar los logs `DEBUG` con datos personales y extraer `ActualizarPerfilCliente` (acción/servicio); (2) pruebas de actualización de perfil y de umbral de completitud.

### M-17 Empresas — 5/10
- **Qué es:** CRUD de empresas con NIT/DV, representante, ciudad/departamento, sector, tipo, logo y PDF (`EmpresaController`, 301 líneas; auth+verified+Superadmin|Admin; PDF protegido en 042).
- **Se relaciona con:** Tipos de empresa, Sectores, Ciudades, Departamentos, PDF. **No con usuarios ni mascotas.**
- **Crítica:** **módulo aislado**: no queda claro para qué sirve en una app de mascotas (¿guarderías aliadas? ¿empresa del operador?). El **DV de la DIAN no se calcula ni valida** (`'dv' => 'required|string|size:1'`): se puede guardar NIT/DV incorrectos. El formulario depende de una API externa de ciudades (ver M-03).
- **Mejoras:** (1) validar DV con el algoritmo oficial de la DIAN (módulo 11 con pesos) y probarlo; (2) definir el rol de negocio de "Empresa" (relacionarla con paseadores/usuarios o retirarla).

### M-18 OAuth: administración de proveedores — 5/10
- **Qué es:** Superadmin crea proveedores (Google, Facebook…), con `client_secret` cifrado (012), pruebas de conexión y "simulador visual" (`OAuthProviderController`, 425 líneas, 12 rutas). Solo `role:Superadmin` (sin `EnsureAdminToolsEnabled`).
- **Se relaciona con:** Login social, Autenticación, Usuarios.
- **Crítica:** el cifrado de secretos es correcto; pero **hay mucho teatro de desarrollo en producción** (`visual-simulator.blade.php`, `OAuthTestLog`, `alert()` simulados, rutas de simulación) y poco valor para un operador; 6 menciones de prueba. El proveedor se pone en configuración **en tiempo de ejecución** (`config([...])`) desde la BD.
- **Mejoras:** (1) retirar simulador y logs de prueba del flujo productivo (o dejarlos tras `admin_tools`); (2) pruebas de alta/edición con secretos cifrados y de proveedor desactivado.

### M-19 Tipo de documento — 5/10
- **Qué es:** catálogo (Cédula, Pasaporte…), usado por usuarios y requisitos; `TipoDocumento`, auth+verified+Superadmin|Admin (040).
- **Crítica:** simple y correcto, casi sin pruebas (solo acceso); borrar un tipo en uso rompe usuarios (`tipo_documento` es entero sin FK en `users`).
- **Mejoras:** (1) FK + `restrictOnDelete` y `SoftDeletes`; (2) pruebas de CRUD.

### M-20 Login social (consumidor OAuth) — 4/10
- **Qué es:** `Auth\SocialAuthController` (409 líneas, Socialite): redirect/callback, crea usuario `Cliente` verificado, vincula cuentas.
- **Se relaciona con:** Autenticación, OAuth (admin), Usuarios, Clientes.
- **Crítica (código leído):**
  1. **Vincula por correo sin comprobar que el proveedor lo haya verificado** (`User::where('email', …)->first()` y `Auth::login($user)`): con un proveedor que acepte correos sin verificar, se puede entrar a una cuenta existente (incluso Superadmin).
  2. **Ignora `activo`**: el bloqueo de usuarios desactivados vive en el login por contraseña (`authenticateUsing`), no aquí → un usuario desactivado entra por Google.
  3. Mezcla lógica de **prueba** (`state = test_…`, `OAuthTestLog`) con el flujo real en el mismo controlador, y el `state` de prueba comparte campo con el *state* anti-CSRF *(revisar)*.
  4. No exige correo del proveedor (si viene vacío crea usuario con `email` null *(verificar)*).
  8 pruebas (`SocialAuthTest`) cubren lo básico.
- **Mejoras:** (1) vincular solo si el proveedor garantiza `email_verified` y exigir confirmar contraseña/enlace por correo para unir cuentas; verificar `activo`; (2) separar el flujo de prueba en otro controlador detrás de `admin_tools`.

### M-21 Usuarios (`UserController`, `/superadmin/usuarios`) — 4/10
- **Qué es:** el módulo "Usuarios" del seeder: listado, edición y borrado; **la creación siempre hace un Paseador** (`assignRole('Paseador')` + fila en `paseadores`) y le envía la contraseña por correo.
- **Se relaciona con:** Paseadores, Tipo de documento, Notificaciones, Roles.
- **Crítica:**
  - El nombre promete "usuarios", pero solo crea Paseadores: no hay forma de crear Admin/Cliente aquí.
  - `update` **no sincroniza** la fila `paseadores` (cédula, teléfono, avatar duplicados → **datos desincronizados**).
  - `destroy` hace `User::find($id)->delete()`: **borrado duro** sin `SoftDeletes`, con *null-pointer* (500) si el id no existe y sin impedir borrarse a sí mismo; arrastra mascotas por `cascade`.
  - Avatar guardado en `public/avatars/<cédula>/…` (PII en la URL).
  - Contraseña generada **enviada en texto plano** por correo; `UserRequest` existe pero **no se usa** (validación duplicada inline entre `store` y `update`); `UserPolicy` sin usar; sin pruebas de comportamiento.
- **Mejoras:** (1) servicio único `CrearUsuario` con rol elegible, transacción y sin duplicar datos de ficha; enviar enlace de "definir contraseña" en lugar de la contraseña; (2) `SoftDeletes` en `User` + política que prohíba borrarse a sí mismo y a Superadmins.

### M-22 Rutas de documentos (`paths-documentos`) — 4/10
- **Qué es:** CRUD de "rutas" de carpetas de documentos por usuario (`PathDocumento`, `PathDocumentoController`); protegido desde 040.
- **Se relaciona con:** Usuarios, Documentos de mascotas (concepto solapado).
- **Crítica:** **propósito poco claro** y solapado con `DocumentRequirement`/`MascotaDocument`; `create()` carga **todos los usuarios con correo, cédula y roles** (enumeración de datos personales para cualquiera con acceso al módulo); el *route-model binding* **no enlaza** (`{paths_documento}` vs `$pathDocumento`) así que show/edit/update/destroy trabajan con un modelo vacío *(detectado en seg039)*; 5 pruebas solo de acceso.
- **Mejoras:** (1) decidir si el concepto sigue vigente; si no, retirarlo; (2) si sigue: arreglar el binding, paginar y no listar PII.

### M-23 Barrios — 4/10
- **Qué es:** CRUD de barrios (solo Superadmin) y dos endpoints JSON (`/barrios-engativa`, `/barrios-por-ciudad/{id}`) que alimentan el formulario de usuario.
- **Se relaciona con:** Ciudades, Clientes (`barrio_id`), Mascotas (columna fantasma).
- **Crítica:** el sistema **solo contempla Engativá** (localidad fija): el parámetro `{ciudadId}` se ignora; `Barrio::mascotas()` declara las llaves al revés (`hasMany(..., 'id', 'barrio_id')`); el formulario CRUD usa `<strong>` en lugar de `<label>`; 2 menciones de prueba.
- **Mejoras:** (1) generalizar a localidad/ciudad reales (filtro por `ciudad_id`) y corregir la relación; (2) pruebas de los endpoints y del CRUD.

### M-24 Geolocalización (Nominatim) — 4/10
- **Qué es:** `GeocodingService` (377 líneas) convierte direcciones del cliente en latitud/longitud con `nominatim.openstreetmap.org`, hasta 3 intentos con `usleep(1 s)`.
- **Se relaciona con:** Clientes, (futuros) paseos/mapas.
- **Crítica:** corre **dentro de la petición web** (hasta ~3×10 s de timeout + pausas) sin caché ni cola; Nominatim público tiene política de uso restrictiva (1 req/s, no apta para producción con carga); **sin pruebas**; las coordenadas se guardan pero **ninguna funcionalidad las usa** (no hay mapa, distancia ni cercanía).
- **Mejoras:** (1) pasar a un job en cola con caché por dirección (y proveedor con SLA si se va a producción); (2) usar las coordenadas en algo visible (mapa del cliente / filtro de paseadores cercanos) o dejar de recolectarlas.

### M-25 Reportes PDF — 4/10
- **Qué es:** `PDFController` (PDF demo y PDF de mascota) y `EmpresaController::pdf`; DomPDF. Protegidos en 042 (`PdfAccessTest`, 8).
- **Crítica:** el módulo "Reportes" se reduce a **tres PDFs**; el `/pdf` es una página de ejemplo; **no existen reportes de negocio** (vacunas por vencer, documentos pendientes, actividad). La imagen por defecto del PDF apunta a un archivo con ruta de usuario real (`public/avatars/1110456003/…`).
- **Mejoras:** (1) reportes reales (exportables) para Admin; (2) quitar el PDF de ejemplo y la imagen con ruta personal.

### M-26 Notificaciones — 4/10
- **Qué es:** 4 clases (`VerifyEmailNotification`, `CredencialesPaseadorNotification`, `BackupCompletedNotification`, `NotificacionSimple`) y la campana del navbar (marcar leídas, endurecido en 039b).
- **Crítica:** no es un módulo sino piezas sueltas; `CredencialesPaseadorNotification` envía la **contraseña en claro**; no hay preferencias, ni notificaciones de negocio (documento aprobado/rechazado, vacuna por vencer); **no hay cola ni worker** definidos (`QUEUE_CONNECTION=database` sin proceso en `docker-compose`), así que cualquier `ShouldQueue` futuro no se enviaría.
- **Mejoras:** (1) notificar los eventos de negocio (aprobación/rechazo de documento, vencimientos) y agregar worker y scheduler a Docker; (2) sustituir contraseñas por enlaces de activación.

### M-27 Configuración de correo — 4/10
- **Qué es:** `EmailConfig` (contraseña cifrada), CRUD + prueba de envío (Superadmin, solo `APP_ENV=local`). Carga la configuración activa desde la tabla al arrancar (`AppServiceProvider`).
- **Crítica:** **reescribe `.env` desde la web** (`updateEnvFile`) — práctica peligrosa y contraria a la regla del proyecto (el `.env` no debe tocarse); tuvo una fila corrupta (031c); queda inutilizable fuera de `local`, así que **en producción no hay UI de correo**.
- **Mejoras:** (1) dejar el correo en `.env`/secretos del servidor y la UI solo para *probar*; (2) pruebas de la carga desde BD con fallback.

### M-28 Configuración de BD — 4/10
- **Qué es:** `DatabaseConfig` con cifrado y `updateEnvFile` (CRUD y prueba de conexión, solo local); `AppServiceProvider::loadDatabaseConfigFromDatabase`.
- **Crítica:** **guarda la configuración de la base de datos dentro de la propia base de datos** y la aplica al arrancar (dependencia circular y riesgo de dejar la app sin conexión); reescribe `.env`; blast radius altísimo para un beneficio mínimo.
- **Mejoras:** (1) retirar la carga de BD-desde-tabla (usar `.env`); (2) dejar la pantalla solo como "probar conexión".

### M-29 Migraciones, Seeders y Limpieza (herramientas web) — 4/10
- **Qué es:** `MigrationController`, `SeederController` (lista blanca), `CleanController`; habilitados solo con `APP_ENV=local` (`config/admin_tools.php`), con *throttle* y pruebas `AdminWebToolsTest` (4).
- **Crítica:** la protección actual (SEG-009/013) es buena, pero **en producción estos módulos aparecen en el menú y devuelven 404** (confusión); `migrate:refresh` se ejecuta con **un clic sin confirmación fuerte** en local; el `SeederController` tiene 3 comprobaciones redundantes de nombre (case-insensitive, base name…) que sobran con una lista blanca simple.
- **Mejoras:** (1) ocultarlos del menú cuando `admin_tools` está apagado y exigir confirmación escrita para `refresh`; (2) simplificar la lista blanca de seeders.

### M-30 Backup de base de datos — 3/10
- **Qué es:** `BackupConfig/BackupLog`, `BackupService` (411 líneas): crea una BD destino, ejecuta migraciones y **copia tabla por tabla con PDO**; notificación al terminar. Solo local.
- **Crítica:** no es un respaldo en el sentido operativo: **sin programación** (no hay scheduler), **sin restauración**, sin retención/rotación, sin cifrado ni copia fuera del servidor, sin `mysqldump`; copiar a otra BD en el mismo servidor no protege ante pérdida del servidor.
- **Mejoras:** (1) respaldo con `mysqldump` (o `spatie/laravel-backup`) programado, cifrado y con envío fuera del servidor; (2) procedimiento y prueba de **restauración** documentados.

### M-31 Paseadores — 3/10
- **Qué es:** modelo `Paseador` (disponibilidad, tarifa por hora, calificación promedio) + `PaseadorController` (dashboard de bienvenida; **create/store/destroy sin ruta**); se crean desde `UserController`. 1 prueba unitaria.
- **Se relaciona con:** Usuarios, Ciudades, Tipo de documento.
- **Crítica:** **hoy un Paseador no puede hacer nada**: no hay servicios, ofertas, agenda, reservas, tarifas visibles ni calificaciones (los campos existen en BD pero ninguna pantalla los usa); Paseador no puede ver mascotas por política; sin menú lateral; datos duplicados con `users`.
- **Mejoras:** (1) definir el producto: perfil público del paseador (zonas, tarifa, disponibilidad) y su edición; (2) crear el módulo de **Servicios/Reservas** (la razón de ser del rol).

### M-32 Árbol genealógico — 3/10
- **Qué es:** `ArbolGenealogicoController` (1 método): dibuja con D3 un grafo **Cliente → sus mascotas**. Ruta `auth` + módulo (sin `verified`).
- **Crítica:** **no es genealógico** (no hay padres/hijos ni camadas); duplica la lógica de avatares **con `asset('public/…')`** (siguen dando 404 en el controlador); sin pruebas; D3 por CDN; accesible a cualquier rol autenticado (un Admin ve "su" árbol vacío).
- **Mejoras:** (1) o se modela de verdad la genealogía (`padre_id`, `madre_id`, camadas) o se renombra a "Mis mascotas (vista gráfica)"; (2) reutilizar un único *helper* de URL de avatar y añadir pruebas.

### M-33 Admin (`AdminController`, `/admin/users`) — 2/10
- **Qué es:** panel de usuarios para el rol Admin.
- **Crítica con **[VERIFICADO]** por prueba temporal:**
  - Un Admin ejecutó `DELETE /admin/users/{superadmin}` → **302 y el Superadmin desapareció**; también se borró a sí mismo. La protección compara `hasRole('superadmin')` (el rol real es `Superadmin`).
  - `POST /admin/users/{id}/toggle-status` → **500**: "Method `AdminController::toggleStatus` does not exist".
  - `POST /admin/users` con `role=admin` → **500**: "There is no role named `admin`" (la validación acepta minúsculas; el rol es `Admin`; en MySQL con collation `ci` podría pasar, en SQLite/CS no). Además guarda `active` pero el campo real es `activo`.
  - El **sidebar de Admin está comentado** en `layouts/sidebar.blade.php` (el Admin no tiene menú).
- **Mejoras:** (1) **urgente**: impedir que un Admin gestione/borre Superadmins o a sí mismo (política + roles como constantes) y arreglar `store`/`toggleStatus`; (2) decidir si Admin gestiona usuarios o no y, si sí, unificar con M-15/M-21.

### M-34 Perfil de usuario (`ProfileController`) — 2/10
- **Qué es:** controlador estándar del starter kit (editar/actualizar/borrar perfil).
- **Crítica:** **ninguna ruta lo usa** (código muerto); en el navbar "Perfil" apunta a `#`; el usuario no puede cambiar su propia contraseña ni correo salvo por Fortify (`updateProfileInformation` activo en config pero sin pantallas).
- **Mejoras:** (1) pantalla de perfil real para todos los roles (datos, contraseña, 2FA, cierre de otras sesiones); (2) borrar el controlador si no se usa.

### Módulos ausentes (los que mencionas y los que el negocio exige)
| Módulo | ¿Existe? | Qué hay | Qué sería necesario |
|---|---|---|---|
| **DIAN / facturación electrónica** | **No** | `nit`+`dv` en Empresas | Validar DV; si se factura: proveedor tecnológico autorizado, resoluciones, XML UBL, CUFE, notas crédito. Es un proyecto aparte. |
| **Cuentas (financieras)** | **No** | `users`, `social_accounts` | Planes, cobros, saldos; hoy no hay pasarela ni modelo de pagos. |
| **Reservas / Servicios de paseo** | **No (0 %)** | campos de tarifa en `paseadores` | Catálogo de servicios, agenda, estados, calendario. |
| **Pagos** | **No (0 %)** | — | Pasarela (p. ej. PSE/Wompi/PayU), conciliación. |
| **Calificaciones** | **No** | `calificacion_promedio` sin uso | Reseñas por servicio. |
| **Chat** | **No (0 %)** | — | Mensajería en tiempo real/notificaciones. |
| **API REST / app móvil** | **No** | — (el informe antiguo dice 30 %) | Autenticación por tokens (Sanctum), versionado, contratos. |

---

## 5. Problemas transversales (afectan a varios módulos)

1. **Privacidad de datos personales (alta):** cédulas, direcciones, coordenadas, certificados y documentos de identidad viven en disco **público** (`avatars/<cédula>/…`, `documentos_mascotas/<cédula>/…`) y en **logs `DEBUG`** (ClienteController). Con datos de identificación de personas en Colombia aplica la **Ley 1581 de 2012 (Habeas Data)**: se necesita política de tratamiento, consentimiento y minimización *(consulta jurídica recomendada; esto no es asesoría legal)*. Además no existe auditoría de quién consulta/descarga qué.
2. **Gestión de usuarios duplicada ×4** (UserController, SuperadminController, AdminController, métodos muertos en Cliente/Paseador) con reglas contradictorias: de ahí salen los defectos de M-21, M-33 y la falta de coherencia en borrado/desactivación.
3. **Roles como cadenas sueltas** (≥ 100 literales; mayúsculas inconsistentes) y **permisos sembrados pero nunca usados**.
4. **Sin tareas programadas ni cola** en Docker (`docker-compose` solo tiene app, nginx y mysql): no corren `modules:clean-verifications`, backups ni recordatorios; cualquier notificación en cola no se enviaría.
5. **Borrado duro por todas partes** (solo 8 modelos con `SoftDeletes`; `User`, `Mascota`, `Cliente`, `Paseador`, `Raza`, `Barrio` no): se pierde información y se rompen relaciones por `cascade`.
6. **Código muerto/duplicado:** métodos de controlador sin ruta (Cliente, Paseador, Superadmin, Departamento, Empresa::getCiudades, ProfileController completo), `public/ciudades.php`, `public/api-ciudades.php` (con CORS `*` y listas fijas), `public/app.blade.php`, copias de datos de vistas en `_borrar/`.
7. **Herramientas de administración que escriben `.env` y ejecutan Artisan desde la web**: bien protegidas hoy (solo `local`), pero conceptualmente frágiles; en producción **no hay** forma administrativa de cambiar correo/BD/copias (dependen de acceso al servidor).
8. **Frontend:** ver `informe-unificado-frontend.md` (CDN sin gestionar, tema oscuro roto, auth fuera del layout, accesibilidad).
9. **Pruebas:** 329 pruebas, pero concentradas en acceso/seguridad y flujos de Mascotas/Documentos/Módulos; **sin pruebas de comportamiento** para Cliente, Paseador, Admin, Usuarios, Árbol, Geolocalización, Empresas (CRUD), OAuth admin, Backup. **No hay CI** que las ejecute (el workflow está bloqueado por facturación según los docs recientes) ni pruebas de navegador.
10. **Documentación desalineada con el código** (85 %/100 % declarados): conviene reemplazarla por un estado honesto (este informe puede servir de base).

---

## 6. Cómo proseguir (propuesta priorizada)

**Fase A — Cerrar riesgos (1-2 semanas, antes de cualquier funcionalidad nueva)**
1. **M-33 y M-08**: impedir que Admin borre/gestione Superadmins y a sí mismo; arreglar `store`/`toggleStatus`; roles como constantes. **Proteger `/admin/document-requirements` con `role:Superadmin|Admin`** (un Cliente hoy puede borrar requisitos).
2. **M-05/M-06/M-04**: mover documentos, certificados, cédulas y avatares a **disco privado** con descarga autorizada (migrar los archivos existentes).
3. **M-20**: login social: verificar `activo`, exigir correo verificado del proveedor y no vincular cuentas automáticamente.
4. **M-16**: quitar los logs `DEBUG` con datos personales.
5. Añadir **scheduler y worker** a Docker (y programar `modules:clean-verifications`).

**Fase B — Consolidar lo existente (2-4 semanas)**
6. Unificar gestión de usuarios (M-15/M-21/M-33) con políticas y `SoftDeletes` en `User`.
7. Frontend Fase 1-2 (informe unificado: rutas de imagen, accesibilidad base, assets propios).
8. Pruebas de comportamiento para Cliente, Paseador, Usuarios, Empresas, OAuth admin y Geolocalización.
9. Decidir el destino de: `paths-documentos`, `Árbol genealógico`, `Empresas`, `Backup`, herramientas web y `ProfileController`.

**Fase C — Construir el negocio (la parte que hoy falta)**
10. Definir el producto mínimo viable: **Servicios → Reservas → Pagos → Calificaciones** (en ese orden), con el rol Paseador como eje (M-31).
11. Dashboards útiles (M-13) y reportes de negocio (M-25), recordatorios de vacunas (M-06).
12. Si se factura: iniciar el proyecto de **facturación electrónica DIAN** con proveedor tecnológico (módulo aparte, no un campo en Empresas).

**Decisiones que necesito de ti (cambian el orden):**
- ¿El producto es una **herramienta interna de una guardería** (gestión de mascotas y documentos) o un **marketplace de paseos** (clientes ↔ paseadores)? Hoy el código es lo primero; la documentación vende lo segundo.
- ¿Qué se hace con "Empresas" y "Rutas de documentos": aliados, parte del negocio o se retiran?
- ¿Se factura electrónicamente (DIAN)? Si sí, ¿con qué proveedor?
- ¿Hay datos reales en producción? (condiciona la migración de archivos a disco privado y el plan de privacidad).

---

## 7. Limitaciones y qué verifiqué
- **No ejecuté la aplicación en navegador** ni medí rendimiento en esta pasada; el frontend se resume desde el informe unificado.
- **[VERIFICADO] con pruebas temporales (borradas):** Admin borra Superadmin y a sí mismo; Cliente administra requisitos documentales; `toggleStatus` inexistente (500); `store` falla por rol `admin`; `.php` como avatar rechazado; recorrido de directorios neutralizado.
- **Verificado leyendo código** (no ejecutado): documentos en disco público, login social sin `activo`/correo verificado, 21 logs `DEBUG`, permisos nunca consultados, `Empresa` sin relación con usuarios, ausencia de scheduler/worker, `Barrio::mascotas()` invertida, métodos sin ruta (script sobre `route:list`).
- Los puntajes son **juicio experto con criterio explícito**, no una métrica objetiva; las cifras (líneas, rutas, pruebas) sí son medidas.
- Marcado *(verificar)* donde no pude confirmar el comportamiento.
