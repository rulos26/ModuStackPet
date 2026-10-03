# SEG-033: pruebas de autenticación con Socialite

Fecha: 2026-10-03  
Rama: `ia/codex/pruebas-socialite`

## Cobertura agregada

Se agregó `tests/Feature/SocialAuthTest.php` con ocho casos que simulan
Socialite mediante `Socialite::shouldReceive`; ninguna prueba contacta a un
proveedor OAuth real.

- Redirección a un proveedor activo y configurado.
- Creación de usuario nuevo, perfil de cliente y cuenta social en el callback.
- Vinculación por correo a un usuario existente sin duplicar usuarios.
- Inicio de sesión por `provider_id` existente sin duplicar usuarios ni cuentas.
- Rechazo con HTTP 403 de proveedores inactivos.
- Rechazo con HTTP 403 de proveedores sin configuración completa.
- Respuesta HTTP 404 para proveedores inexistentes, sin error 500.
- Manejo de una excepción del proveedor: regreso al login con mensaje, registro
  del error y ausencia de escrituras parciales.

## Bug encontrado

El caso de usuario nuevo falla porque el correo no queda verificado:
`email_verified_at` permanece en `null`.

El controlador incluye `email_verified_at => now()` al crear el usuario en
`app/Http/Controllers/Auth/SocialAuthController.php:183`, pero
`app/Models/User.php:26` no incluye ese atributo en `$fillable`. Por ello,
Eloquent descarta silenciosamente el valor durante la asignación masiva.

La revisión del historial confirma que el intento de verificar el correo se
introdujo con el flujo social en el commit `36749397`; no se encontró un cambio
posterior que indicara que dejarlo sin verificar fuera intencional.

No se corrigió el código de aplicación porque la tarea prohíbe modificar
`app/`. La corrección mínima propuesta es permitir explícitamente el atributo
en el modelo o asignarlo de forma explícita y persistirlo después de crear el
usuario. La prueba se conserva sin relajar su aserción para que caracterice el
comportamiento esperado y detecte la corrección futura.

## Resultados de verificación

- Antes de los cambios: `php artisan test` → 169 pruebas aprobadas,
  510 aserciones.
- Archivo nuevo aislado: 7 aprobadas, 1 fallida, 46 aserciones.
- Suite completa: 176 aprobadas, 1 fallida, 556 aserciones. La única falla es
  `callback_creates_new_user_cliente_profile_and_social_account`, por el bug de
  `email_verified_at` descrito arriba.
- `composer validate` → `./composer.json is valid`.

