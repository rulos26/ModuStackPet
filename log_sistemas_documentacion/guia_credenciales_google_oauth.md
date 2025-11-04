# 🔑 Guía: Obtener Credenciales de Google OAuth

## 📋 Pasos para Obtener Client ID y Client Secret

### Paso 1: Acceder a Google Cloud Console
1. Ve a: https://console.cloud.google.com/
2. Inicia sesión con tu cuenta de Google
3. Crea un nuevo proyecto (si no tienes):
   - Click en el selector de proyecto (arriba)
   - "NUEVO PROYECTO"
   - Nombre: `ModuStackPet`
   - Click en "CREAR"

### Paso 2: Habilitar API de Google+
1. Menú ☰ → "APIs y servicios" → "Biblioteca"
2. Busca: "Google+ API" o "Google Identity Services"
3. Click en "HABILITAR"

**Alternativa (recomendada):**
- Busca: "Identity Toolkit API" o "Google Identity"
- Habilita la API que encuentres

### Paso 3: Configurar Pantalla de Consentimiento
1. Menú ☰ → "APIs y servicios" → "Pantalla de consentimiento de OAuth"
2. Selecciona: **"Externo"** (o "Interno" si solo para tu organización)
3. Completa la información:
   - **Nombre de la aplicación:** `ModuStackPet`
   - **Correo de soporte:** tu email
   - **Dominio de la aplicación:** `rulossoluciones.com`
   - **Correo del desarrollador:** tu email
4. **Alcances (scopes):**
   - Email
   - Perfil
   - Información básica del perfil
5. **Usuarios de prueba** (si está en modo "Prueba"):
   - Agrega emails que puedan probar
6. Click en "GUARDAR Y CONTINUAR"
7. Revisa y vuelve al panel

### Paso 4: Crear Credenciales OAuth
1. Menú ☰ → "APIs y servicios" → "Credenciales"
2. Click en **"+ CREAR CREDENCIALES"** → **"ID de cliente de OAuth 2.0"**
3. **Tipo de aplicación:** Selecciona **"Aplicación web"**
4. **Nombre:** `ModuStackPet Web Client`
5. **Orígenes JavaScript autorizados:**
   ```
   https://rulossoluciones.com
   ```
6. **URI de redirección autorizados:**
   ```
   https://rulossoluciones.com/ModuStackPet/auth/google/callback
   ```
   **Nota:** Agrega también la URL de desarrollo si la usas:
   ```
   http://localhost/ModuStackPet/auth/google/callback
   ```
7. Click en **"CREAR"**
8. **¡IMPORTANTE!** Te mostrará:
   - **ID de cliente:** `xxxxxxxxxxxx.apps.googleusercontent.com`
   - **Secreto de cliente:** `GOCSPX-xxxxxxxxxxxx`
   - **COPIA AMBOS** (el secreto solo se muestra una vez)

### Paso 5: Configurar en tu Panel
1. Ve a: **Superadmin → Proveedores OAuth**
2. Busca "Google" y click en **"Editar"** (o crea uno nuevo)
3. Completa los campos:
   ```
   Provider: google
   Nombre: Google
   Client ID: [Pega el ID de cliente que copiaste]
   Client Secret: [Pega el Secreto de cliente que copiaste]
   Redirect URI: https://rulossoluciones.com/ModuStackPet/auth/google/callback
   ```
4. ✅ Activa el checkbox **"Activar este proveedor"**
5. Click en **"Guardar Proveedor"**

### Paso 6: Verificar
1. Ve a **Login** o **Register**
2. Deberías ver el botón **"Continuar con Google"**
3. Prueba iniciar sesión con Google

---

## ⚠️ Notas Importantes

### Seguridad
- **NUNCA compartas tu Client Secret**
- Mantén las credenciales seguras
- No las subas a repositorios públicos

### Redirect URI
- Debe coincidir **EXACTAMENTE** con el configurado en Google
- Incluye: protocolo (https://), dominio, subdirectorio, y ruta completa
- Ejemplo: `https://rulossoluciones.com/ModuStackPet/auth/google/callback`

### Modo Prueba vs Producción
- **Modo Prueba:** Solo funciona para usuarios agregados como "Usuarios de prueba"
- **Producción:** Funciona para todos los usuarios (requiere verificación de Google)

### Para Publicar en Producción
1. Ve a "Pantalla de consentimiento de OAuth"
2. Click en "PUBLICAR APP"
3. Google revisará tu aplicación (puede tardar varios días)
4. Una vez aprobada, funcionará para todos los usuarios

---

## 🔧 Solución de Problemas

### Error: "redirect_uri_mismatch"
- Verifica que el Redirect URI en Google coincida EXACTAMENTE con el de tu panel
- Asegúrate de incluir el subdirectorio si lo tienes: `/ModuStackPet/auth/google/callback`

### Error: "Access blocked"
- Si estás en modo "Prueba", agrega tu email a "Usuarios de prueba"
- O publica la app para producción

### No aparece el botón de Google
- Verifica que el provider esté activo en el panel
- Verifica que tenga todas las credenciales configuradas
- Revisa los logs del servidor para errores

---

## 📞 URLs Importantes

- **Google Cloud Console:** https://console.cloud.google.com/
- **Credenciales:** https://console.cloud.google.com/apis/credentials
- **Pantalla de Consentimiento:** https://console.cloud.google.com/apis/credentials/consent
- **Biblioteca de APIs:** https://console.cloud.google.com/apis/library

---

## ✅ Checklist

- [ ] Proyecto creado en Google Cloud Console
- [ ] API de Google+ habilitada
- [ ] Pantalla de consentimiento configurada
- [ ] Credenciales OAuth creadas
- [ ] Client ID y Client Secret copiados
- [ ] Redirect URI configurado correctamente
- [ ] Provider configurado en el panel OAuth
- [ ] Provider activado
- [ ] Botón de Google visible en login/register
- [ ] Prueba de login exitosa

---

**Última actualización:** 2025-01-30

