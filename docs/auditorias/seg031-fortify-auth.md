# SEG-031 — Fortify como stack oficial de autenticación

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/fortify-auth`

## Qué se hizo
- `FortifyServiceProvider` registrado en `bootstrap/providers.php` (antes existía pero no se cargaba).
- `config/fortify.php`: se quitó `Features::twoFactorAuthentication(...)`. Las rutas `two-factor.*` ya no existen (`route:list` sin coincidencias). 2FA no se implementó.
- `routes/web.php`: eliminadas las rutas propias de `register`, `login` (GET/POST) y `password/*`, y los `use` asociados. Esas rutas ahora las registra Fortify (`/register`, `/login`, `/forgot-password`, `/reset-password/{token}`), con los mismos nombres de ruta (`register`, `login`, `password.request|email|reset|update`), así que las vistas existentes siguen funcionando sin cambios.
- Para no perder comportamiento que tenían los controladores propios (no es decisión de producto nueva):
  - `app/Http/Responses/CustomLoginResponse.php` + `RoleRedirect.php`: redirección por rol tras el login (Superadmin/Admin/Cliente/Paseador, o `temp.index` sin rol), igual que el antiguo `Auth\LoginController`.
  - `app/Http/Responses/CustomRegisterResponse.php`: tras registrarse, redirige a `cliente.dashboard` con el mensaje de éxito.
  - `FortifyServiceProvider::authenticateUsing`: usuarios con `activo = false` no pueden entrar (mensaje "Tu cuenta está desactivada...").
  - Registro: `CreateNewUser` (ya existía) asigna rol Cliente y crea el perfil `clientes`; el correo de verificación lo envía el evento `Registered` (usa la `VerifyEmailNotification` propia).

## Movido a `_borrar/controladores-auth/` (ignorada por git; el movimiento sí queda en el commit como eliminación)
`RegisterController`, `LoginController`, `ResetPasswordController` (raíz), `ForgotPasswordController`, `VerificationController`, `LogoutController` (raíz; estos tres no tenían ruta alguna), y `Auth\LoginController`, `Auth\RegisterController`, `Auth\ResetPasswordController` (copiados como `Auth_*.php`).
Nota: `RegisteredUserController` que mencionaba el informe nunca existió en `app/`; la ruta `/register` apuntaba a una clase inexistente y estaba tapada por la siguiente.

## Vistas
Sin cambios: `auth/login`, `register`, `forgot-password`, `reset-password` ya coincidían con lo que Fortify espera (campos `email`, `password`, `password_confirmation`, `token` vía `$request->route('token')`, rutas por nombre). `auth/passwords/email.blade.php` y `auth/verify-email` ya no se usan desde estas rutas (`passwords/email` queda huérfana; no se tocó).

## Pruebas
- `tests/Feature/FortifyAuthTest.php` (18 casos): rutas servidas por Fortify, 2FA apagado, formularios renderizan, registro (rol, perfil, correo de verificación, email duplicado), login por rol, contraseña errónea, usuario inactivo, reset de contraseña de punta a punta.
- Primer commit de pruebas fallaba (10 de 18) como se esperaba; tras el cambio: `php artisan test` → **164 passed** (146 previas + 18 nuevas). `composer validate` OK.

## Prueba manual
**No realizada.** El worktree no tiene `.env` ni base MySQL y las reglas prohíben crearlo; la suite cubre el flujo HTTP completo con SQLite en memoria, pero el humano debe probar en navegador login, registro y reset (el correo de reset usa el mailer configurado).

## Riesgos / pendientes
- URLs viejas `password/reset/{token}` en correos ya enviados dejan de funcionar (ahora `reset-password/{token}`).
- `Features::emailVerification()` sigue activo en Fortify junto a las rutas `email/verify*` propias de `web.php` (no se tocó).
- `config/fortify.php` `home` sigue en `/home` (ruta inexistente); solo afecta a `RedirectIfAuthenticated` con rutas guest.
