# 📧 Explicación del Sistema 2FA para Módulos

## 🔄 Flujo Completo del Código de Verificación

### 1. **Generación del Código** (Automático por el Sistema)
Cuando un Superadmin intenta activar/desactivar un módulo:

```php
// app/Models/ModuleVerification.php
public static function generateCode(): string
{
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}
```

- ✅ **Genera automáticamente** un código de 6 dígitos (000000 a 999999)
- ✅ **Se guarda en BD** en la tabla `module_verifications`
- ✅ **Expira en 10 minutos** (`expires_at = now() + 10 minutos`)
- ✅ **Solo puede usarse una vez** (se marca como usado después)

### 2. **Envío por Correo Electrónico**
```php
// app/Http/Controllers/ModuleController.php
Mail::to(Auth::user()->email)->send(
    new ModuleVerificationMail(
        $verification->verification_code,  // Código generado
        $module->name,                      // Nombre del módulo
        $action                            // "activar" o "desactivar"
    )
);
```

**El correo se envía a:**
- ✅ **El email del usuario autenticado** (`Auth::user()->email`)
- ✅ Usa la clase `ModuleVerificationMail` 
- ✅ Contiene el código de 6 dígitos
- ✅ Muestra el módulo y la acción solicitada

### 3. **Configuración del Correo en Laravel**

**Ubicación:** `config/mail.php`

**Configuración en código:**
```php
'default' => env('MAIL_MAILER', 'log'),
```

**✅ ESTADO ACTUAL (En Servidor):**
- El proyecto **YA está configurado** para enviar correos en el servidor
- El valor por defecto `'log'` en el código es solo un **fallback** para desarrollo local
- **En el servidor**, el archivo `.env` tiene configurado `MAIL_MAILER=smtp` (o similar)
- Por lo tanto, **SÍ se están enviando correos reales** al email del usuario

**El sistema funciona así:**
1. Si existe `MAIL_MAILER` en `.env` → Usa esa configuración (servidor)
2. Si NO existe → Usa `'log'` por defecto (desarrollo local)

**Configuración típica en servidor (`.env`):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com          # O tu servidor SMTP
MAIL_PORT=587
MAIL_USERNAME=tu-email@dominio.com
MAIL_PASSWORD=tu-contraseña-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="ModuStackPet"
```

**✅ CONCLUSIÓN:**
- En **servidor en producción**: Los correos **SÍ se envían** por email real
- En **desarrollo local**: Si no hay `.env` configurado, se guarda en logs

### 4. **Plantilla del Correo**

**Ubicación:** `resources/views/emails/module-verification.blade.php`

El correo contiene:
- ✅ **Asunto:** "Código de verificación para [activar/desactivar] módulo"
- ✅ **Código destacado** en un recuadro rojo
- ✅ **Nombre del módulo** y acción solicitada
- ✅ **Advertencia:** El código expira en 10 minutos
- ✅ **Diseño HTML** profesional

### 5. **Validación del Código**

Cuando el usuario ingresa el código:

```php
// app/Models/ModuleVerification.php
public static function findByCode(string $code, int $userId): ?self
{
    return self::where('verification_code', $code)
        ->where('user_id', $userId)          // Solo para el usuario que lo solicitó
        ->whereNull('used_at')               // No usado aún
        ->where('expires_at', '>', now())    // No expirado
        ->first();
}
```

**Validaciones:**
- ✅ Código debe existir
- ✅ Debe pertenecer al usuario correcto
- ✅ No debe haber sido usado antes
- ✅ No debe estar expirado (máximo 10 minutos)

### 6. **Limpieza Automática**

Existe un comando para limpiar códigos expirados:
```bash
php artisan module:clean-verifications
```

## 📋 Resumen del Flujo

1. **Usuario hace clic en "Activar/Desactivar"** módulo
2. **Sistema genera código** de 6 dígitos automáticamente
3. **Sistema guarda código** en BD con expiración de 10 min
4. **Sistema envía correo** al email del usuario con el código **📧 (FUNCIONA EN SERVIDOR)**
5. **Usuario recibe correo** y copia el código
6. **Usuario ingresa código** en el formulario
7. **Sistema valida código** (usuario, no usado, no expirado)
8. **Si es válido:** Se activa/desactiva el módulo y se marca el código como usado
9. **Si es inválido:** Se registra intento fallido en logs

## ⚙️ Estado Actual (En Servidor)

- ✅ **Código generado automáticamente:** Funciona
- ✅ **Guardado en BD:** Funciona
- ✅ **Envío de correo:** **ACTIVO** - El servidor ya tiene SMTP configurado en `.env`
- ✅ **Correos reales:** Los correos **SÍ están llegando** al email del usuario

**Flujo en Producción:**
1. Usuario hace clic en "Activar/Desactivar" módulo
2. Sistema genera código de 6 dígitos
3. Sistema envía correo **real por SMTP** al email del usuario autenticado
4. Usuario recibe correo con el código
5. Usuario ingresa código y confirma
6. Módulo se activa/desactiva

## 📧 Verificación del Envío

**Si los correos NO están llegando, revisa:**
1. **Logs del sistema:** `storage/logs/laravel.log` - busca errores de correo
2. **Carpeta de spam:** A veces los correos llegan a spam
3. **Configuración SMTP:** Verifica que las credenciales en `.env` sean correctas
4. **Email del usuario:** Verifica que el usuario tenga un email válido en la tabla `users`

## 🔍 Troubleshooting

**Problema: No llegan los correos**
1. Revisar `storage/logs/laravel.log` para ver errores SMTP
2. Verificar que el email del usuario sea correcto: `SELECT email FROM users WHERE id = ?`
3. Verificar que el código se generó: `SELECT * FROM module_verifications ORDER BY created_at DESC LIMIT 1`
4. Revisar configuración SMTP en `.env` del servidor

**Problema: Código expirado**
- Los códigos expiran en 10 minutos
- Si expira, hacer clic nuevamente en "Activar/Desactivar" para recibir un nuevo código

**Problema: Código inválido**
- Verificar que el código tenga exactamente 6 dígitos
- Verificar que sea el código del usuario correcto
- Verificar que no haya sido usado antes

---

*Documentación generada automáticamente - ModuStackPet Sistema de Documentación*

