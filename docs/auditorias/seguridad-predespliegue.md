# Auditoría de seguridad previa al despliegue

Fecha: 2026-09-28  
Alcance: revisión estática y de solo lectura del código solicitado. No se leyó
`.env`, no se ejecutaron migraciones ni seeders y no se conectó a bases de datos.

## Resumen ejecutivo

No se recomienda publicar el estado actual. El bloqueo principal es de control de
acceso: el prefijo `superadmin` solo exige `auth` y `verified`, no el rol
`Superadmin`. Un usuario común con correo verificado puede alcanzar controles que
reescriben `.env`, cambian credenciales, configuran una base de datos externa y
copian allí todas las tablas. Además, las contraseñas de base de datos, correo,
backup y OAuth se almacenan en texto claro en la base de datos.

## Hallazgos priorizados

| ID | Severidad | Hallazgo | Evidencia archivo:línea | Corrección propuesta |
|---|---|---|---|---|
| SEG-001 | Crítica | **El área `superadmin` no comprueba el rol.** El grupo solo aplica `auth` y `verified`; los controladores de base de datos, correo, backup y OAuth tampoco aplican una policy. Cualquier cuenta verificada puede crear, modificar, activar, probar o borrar estas configuraciones. | `routes/web.php:312`; `routes/web.php:330-365`; `app/Http/Controllers/Superadmin/DatabaseConfigController.php:13`; `app/Http/Controllers/Superadmin/EmailConfigController.php:13`; `app/Http/Controllers/Superadmin/BackupConfigController.php:17` | Añadir al grupo un middleware de rol/permiso comprobado en servidor (`role:Superadmin` o equivalente) y aplicar policies por recurso como defensa en profundidad. Crear pruebas negativas para Cliente, Paseador y Admin. |
| SEG-002 | Crítica | **Un usuario verificado puede exfiltrar una copia completa de producción.** Puede registrar un MySQL bajo su control y ejecutar el “backup”; el servicio crea/migra el destino y copia todas las tablas, incluidos usuarios, tokens y secretos almacenados en BD. | `routes/web.php:312,362-365`; `app/Http/Controllers/Superadmin/BackupConfigController.php:54-77,162-180`; `app/Services/BackupService.php:45-63,259-275,282-346` | Corregir primero SEG-001. Restringir destinos por allowlist de host/base, usar una cuenta de destino de privilegio mínimo, requerir reautenticación/MFA y confirmación explícita, registrar alertas y desactivar la ejecución web hasta completar esos controles. |
| SEG-003 | Crítica | **La aplicación web puede reescribir `.env` y dejar copias completas en la raíz del proyecto.** Activar configuraciones de BD o correo modifica secretos operativos y crea `.env.backup.FECHA`; una cuenta indebidamente autorizada podría redirigir la app, provocar indisponibilidad o cambiar el transporte de correo. Las escrituras no son atómicas ni usan bloqueo. | `app/Models/DatabaseConfig.php:78-128`; `app/Models/EmailConfig.php:78-133`; `app/Http/Controllers/Superadmin/DatabaseConfigController.php:115-123,173-184`; `app/Http/Controllers/Superadmin/EmailConfigController.php:120-128,178-189` | Retirar la edición de `.env` del flujo HTTP en producción. Gestionar secretos desde el panel del hosting/secret manager y desplegar configuración inmutable. Eliminar de forma segura las copias existentes después de inventariarlas y rotar credenciales; si se conserva la función, almacenar copias fuera del document root con permisos restrictivos y escritura atómica/bloqueada. |
| SEG-004 | Alta | **Credenciales almacenadas en texto claro.** `database_configs`, `email_configs`, `backup_configs` y `oauth_providers` declaran columnas string y sus modelos no usan casts `encrypted`; `$hidden` solo evita serialización, no cifra el valor persistido. Una fuga SQL expone credenciales reutilizables. | `database/migrations/2025_01_30_120000_create_database_configs_table.php:18-22`; `database/migrations/2025_01_30_130000_create_email_configs_table.php:18-21`; `database/migrations/2025_01_31_140000_create_backup_configs_table.php:19-23`; `app/Models/DatabaseConfig.php:24-32`; `app/Models/EmailConfig.php:26-34`; `app/Models/BackupConfig.php:26-36`; `app/Models/OAuthProvider.php:11-25` | Preferir no persistir secretos administrables desde la app. Cuando sea imprescindible, usar casts `encrypted`/`encrypted:string`, limitar selección/serialización, migrar y rotar los valores actuales, y proteger/rotar `APP_KEY` mediante un secreto externo. |
| SEG-005 | Alta | **`CheckModuleStatus` falla abierto.** Si falta la tabla o cualquier consulta/creación falla, permite la petición. Este middleware rodea precisamente controles sensibles de BD, correo, backup y OAuth, por lo que un incidente de BD anula el interruptor de módulos. También autocrea módulos activos. | `app/Http/Middleware/CheckModuleStatus.php:16-22,25-58`; `routes/web.php:330-365` | Para módulos administrativos, fallar cerrado con 503/403 y telemetría sin datos sensibles. Registrar módulos mediante migración/seeder controlado, no durante una petición. Separar explícitamente la tolerancia de arranque de los controles de autorización: este middleware nunca debe sustituir roles/policies. |
| SEG-006 | Alta | **Desplegar con la raíz del proyecto como document root expone una superficie innecesaria.** Existe un `index.php` en la raíz que carga `vendor` y `bootstrap`; el `.htaccess` raíz solo reescribe y no contiene reglas explícitas `Require all denied` para `.env*`, `storage`, `vendor`, `database`, `bootstrap` o `.git`. La protección depende de que Apache procese correctamente `mod_rewrite`; en servidores alternativos o con overrides distintos esos archivos podrían servirse. Las copias `.env.backup.*` agravan el riesgo. | `index.php:8-16`; `.htaccess:1-8`; `public/.htaccess:1-20`; `app/Models/DatabaseConfig.php:104-120`; `app/Models/EmailConfig.php:109-125` | Configurar el dominio para que el document root sea exclusivamente `public/`. No usar `/public` en URLs. Como defensa adicional, denegar dotfiles y directorios internos en la configuración del servidor y probar desde fuera que devuelven 403/404 y nunca contenido. No confiar únicamente en `.htaccess`. |
| SEG-007 | Alta | **Seeders ejecutables por HTTP incluyen cuentas con contraseñas fijas y datos de prueba.** `SeederController` permite `UserSeeder` y fuerza `db:seed`; la policy sí limita la acción a Superadmin, pero una cuenta Superadmin comprometida o un error futuro de autorización puede crear cuentas conocidas (`root` y `12345678`) y poblar usuarios de prueba en producción. | `app/Http/Controllers/SeederController.php:16-30,43-58,100-127`; `app/Policies/ModulePolicy.php:10-13`; `database/seeders/UserSeeder.php:16-84`; `routes/web.php:394-398` | Eliminar/deshabilitar en producción la ejecución web de seeders y excluir `UserSeeder`/`TokenSeeder` de cualquier allowlist productiva. Crear el administrador inicial mediante un comando de provisión de una sola vez con contraseña aleatoria/entrada segura, rotación obligatoria y auditoría. |
| SEG-008 | Media | **La construcción de `.env` no rechaza controles y usa entrada como reemplazo regex.** La validación admite cualquier string de hasta 255/500 caracteres; `escapeEnvValue()` entrecomilla pero no rechaza CR/LF ni escapa barras invertidas o `$`, y el valor resultante se entrega como replacement a `preg_replace`. Esto permite corromper el archivo y hace inseguro asumir que se impide toda inyección de línea/variable. | `app/Http/Controllers/Superadmin/DatabaseConfigController.php:89-97`; `app/Models/DatabaseConfig.php:143-185`; `app/Http/Controllers/Superadmin/EmailConfigController.php:90-100`; `app/Models/EmailConfig.php:148-190` | Retirar la edición HTTP (preferido). Si permanece, rechazar `\r`, `\n`, NUL y caracteres fuera de allowlists por campo; no construir `.env` con regex, usar un serializador probado; validar hosts, drivers, puertos y cifrado con enums/formatos estrictos, y añadir pruebas de payloads con CRLF, comillas, barras y `$1`. |
| SEG-009 | Media | **Los “backups” no son archivos protegidos sino réplicas completas a otra BD, y sus metadatos/errores quedan en tablas y logs.** `backup_logs` guarda nombres de tablas, errores y recuentos; `backup_configs` guarda la credencial del destino en claro. Esos datos son visibles desde rutas del mismo grupo débil. | `app/Services/BackupService.php:36-42,65-86,100-122`; `database/migrations/2025_01_31_140001_create_backup_logs_table.php:17-31`; `app/Http/Controllers/Superadmin/BackupConfigController.php:205-213` | Tratar la BD destino como copia sensible: cifrado en tránsito y reposo, red/usuario dedicados, retención y borrado definidos, acceso mínimo y restauraciones probadas. Sanear errores antes de persistir/mostrar y limitar la retención de logs. |
| SEG-010 | Media | **Los logs de prueba OAuth no guardan access/refresh tokens, pero sí PII, IP, user-agent y trazas completas sin cifrado ni retención visible.** `social_accounts` tampoco guarda tokens: conserva proveedor, ID externo y avatar. El riesgo real es exposición/retención de datos de diagnóstico y trazas. | `database/migrations/2025_01_30_110000_create_oauth_test_logs_table.php:17-43`; `app/Http/Controllers/Auth/SocialAuthController.php:93-103,153-166,211-240,253-274`; `database/migrations/2025_10_31_112029_create_social_accounts_table.php:17-25`; `app/Models/SocialAccount.php:15-20` | Mantener la regla de no persistir tokens; minimizar `step_data`, no guardar trazas completas en BD, aplicar retención corta/borrado programado y restringir resultados a Superadmin mediante policy. Documentar finalidad y retención de IP/user-agent. |
| SEG-011 | Baja | **No se encontraron rutas `/public/...` en vistas actuales**, pero la presencia del front controller raíz muestra que el despliegue contempla servir el proyecto completo. El riesgo no está corregido por ausencia de esas URLs. | Búsqueda estática en `resources/views/**/*.blade.php` sin coincidencias; `index.php:1-17`; `.htaccess:1-8` | Mantener todas las URLs mediante `asset()`, `route()` o Vite, fijar el document root a `public/` y añadir una prueba de humo en el hosting que confirme que los assets funcionan sin prefijo `/public`. |

## Respuestas directas al alcance

- Editor de `.env`: lo alcanzan todos los usuarios autenticados y verificados por
  la ausencia de middleware de rol. La entrada tiene límites de longitud, pero no
  validación estricta ni rechazo de caracteres de control. La mitigación segura es
  no editar `.env` desde HTTP en producción.
- Seeders: sí pueden ejecutarse desde la web. La policy actual exige Superadmin,
  existe allowlist y throttle de una petición por minuto, pero la allowlist incluye
  seeders peligrosos para producción.
- Contraseñas: BD, correo, backup y OAuth se persisten en texto claro. `$hidden`
  no equivale a cifrado.
- `CheckModuleStatus`: permite acceso si falta la tabla o se produce cualquier
  excepción; por diseño falla abierto.
- Rutas `/public`: no se encontraron en las vistas actuales. Aun así, servir la
  raíz del repositorio es un bloqueo de despliegue.
- Backups: no se genera un archivo; se replica toda la BD a otra base MySQL. La
  copia queda fuera de la aplicación y su accesibilidad depende del servidor de
  destino. Los metadatos quedan en `backup_configs`/`backup_logs`.
- OAuth: `oauth_test_logs` y `social_accounts` no almacenan tokens según el código
  actual. Sí almacenan identificadores, correo/nombre en algunos pasos, IP,
  user-agent, errores y trazas.

## Lista de verificación de despliegue

### Bloqueantes

- [ ] Exigir rol/permiso Superadmin en todo `/superadmin` y cubrirlo con pruebas
  403 para todos los demás roles.
- [ ] Deshabilitar el editor web de `.env`, seeders, migraciones, limpieza y
  backups hasta aplicar controles específicos y reautenticación.
- [ ] Impedir destinos de backup arbitrarios y rotar cualquier credencial que ya
  haya quedado almacenada o copiada.
- [ ] Cifrar o retirar de la BD todas las credenciales persistidas; rotarlas tras
  la migración.
- [ ] Eliminar cuentas/contraseñas fijas de seeders productivos y rotar cuentas
  existentes creadas con esos valores.
- [ ] Configurar el document root del hosting en `public/`; verificar desde una red
  externa que `.env`, `.env.backup.*`, `.git`, `vendor`, `storage`, `database` y
  `bootstrap` responden 403/404 sin contenido.
- [ ] Cambiar `CheckModuleStatus` a fail-closed para módulos administrativos.

### Antes de abrir tráfico

- [ ] Ejecutar pruebas automatizadas y `composer audit --locked` en el artefacto
  exacto que se desplegará.
- [ ] Confirmar `APP_ENV=production`, `APP_DEBUG=false`, HTTPS forzado, cookies
  `Secure`/`HttpOnly`/`SameSite` y cabeceras de seguridad en el entorno real sin
  registrar valores secretos.
- [ ] Aplicar permisos mínimos: el usuario web solo escribe en `storage/` y
  `bootstrap/cache/`, nunca en código ni configuración.
- [ ] Definir retención y borrado de logs OAuth, logs de backup y copias de BD;
  sanear mensajes de excepción visibles.
- [ ] Probar restauración del backup con una cuenta y red de destino restringidas.
- [ ] Revisar que no existan copias `.env.backup.*` dentro del document root y
  eliminarlas mediante un procedimiento seguro y auditable.
- [ ] Verificar login normal/social, recuperación de contraseña, envío de correo,
  acceso por roles y respuestas 403/404 a rutas administrativas no autorizadas.

## Limitaciones

La auditoría no confirma la configuración efectiva de Apache/Hostinger, permisos
del sistema de archivos, reglas WAF, TLS, valores de producción ni contenido real
de las bases de datos. Esos controles deben verificarse en el hosting antes de
publicar. No se intentó explotar ningún hallazgo.
