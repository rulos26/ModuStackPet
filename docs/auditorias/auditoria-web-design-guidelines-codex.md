# Auditoría Web Design Guidelines — Codex

Fecha: 2026-10-03  
Base revisada: `origin/main` (`60e8debd`)  
Reglas: [Vercel Web Interface Guidelines](https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md), consultadas el 2026-10-03  
Alcance: las 149 vistas de `resources/views/`, `resources/css/app.css`, todos los archivos de `resources/js/` y `public/js/`  
Método: revisión estática independiente; no se consultaron auditorías frontend existentes del repositorio.

Severidades: **Alta** impide o dificulta seriamente una tarea; **Media** degrada accesibilidad, interacción o estabilidad visual; **Baja** afecta calidad, consistencia o rendimiento sin bloquear el flujo.

## resources/views/layouts/app.blade.php

- `resources/views/layouts/app.blade.php:6` — **Media** — Falta `<meta name="theme-color">`; la interfaz del navegador puede no coincidir con el tema. **Recomendación:** declarar el color y actualizarlo al cambiar de tema.
- `resources/views/layouts/app.blade.php:17` — **Baja** — Se usan recursos de `cdn.jsdelivr.net` sin `preconnect`. **Recomendación:** añadir `preconnect` para los orígenes críticos o empaquetar las dependencias.
- `resources/views/layouts/app.blade.php:18` — **Baja** — Se usa `cdnjs.cloudflare.com` sin `preconnect`. **Recomendación:** añadir `preconnect` o servir Font Awesome desde el bundle.
- `resources/views/layouts/app.blade.php:44` — **Alta** — No existe enlace para saltar al contenido principal. **Recomendación:** insertar un skip link como primer control enfocable del `<body>`.
- `resources/views/layouts/app.blade.php:66` — **Alta** — El contenido principal usa `<section>` sin landmark `<main>`. **Recomendación:** usar `<main id="contenido-principal" tabindex="-1">` y apuntar allí el skip link.
- `resources/views/layouts/app.blade.php:76` — **Media** — El enlace de copyright usa `href="#"` sin destino. **Recomendación:** asignar una URL real o renderizar texto no interactivo.
- `resources/views/layouts/app.blade.php:89` — **Alta** — Los mensajes toast asíncronos no garantizan una región `aria-live`. **Recomendación:** exponer éxito con `role="status"`/`aria-live="polite"` y error persistente con `role="alert"`.
- `resources/views/layouts/app.blade.php:93` — **Media** — Los toasts desaparecen tras 3 segundos, incluso si contienen errores. **Recomendación:** no autocerrar mensajes que requieran acción y ofrecer cierre manual.
- `resources/views/layouts/app.blade.php:121` — **Media** — El tema oscuro cambia un atributo propio, pero no establece `color-scheme: dark`. **Recomendación:** sincronizar `color-scheme` en `<html>` para controles y scrollbars nativos.

## resources/views/layouts/navbar.blade.php

- `resources/views/layouts/navbar.blade.php:5` — **Alta** — El control del sidebar es un enlace de acción, solo icono y sin `aria-label`. **Recomendación:** usar `<button type="button">`, añadir nombre accesible y actualizar `aria-expanded`.
- `resources/views/layouts/navbar.blade.php:6` — **Baja** — El icono decorativo se anuncia a tecnologías de asistencia. **Recomendación:** añadir `aria-hidden="true"`.
- `resources/views/layouts/navbar.blade.php:15` — **Alta** — El botón de notificaciones es un enlace `href="#"`, solo icono y sin nombre accesible. **Recomendación:** usar botón con `aria-label="Notificaciones"`, `aria-expanded` y `aria-controls`.
- `resources/views/layouts/navbar.blade.php:16` — **Baja** — La campana decorativa no tiene `aria-hidden="true"`. **Recomendación:** ocultarla al árbol accesible.
- `resources/views/layouts/navbar.blade.php:84` — **Media** — El avatar no declara atributos HTML `width` y `height`; el estilo no reserva dimensiones antes de cargar. **Recomendación:** añadir `width="30" height="30"`.
- `resources/views/layouts/navbar.blade.php:98` — **Media** — “Perfil” apunta a `#`, por lo que no admite navegación real ni apertura en otra pestaña. **Recomendación:** usar la ruta del perfil o retirar la opción.

## resources/views/layouts/sidebar.blade.php

- `resources/views/layouts/sidebar.blade.php:5` — **Media** — El logo no declara `width` y `height` como atributos. **Recomendación:** reservar sus dimensiones explícitamente.

## resources/views/superadmin/sidebar.blade.php

- `resources/views/superadmin/sidebar.blade.php:44` — **Media** — Un elemento de navegación usa `href="#"` como disparador. **Recomendación:** usar botón para expandir el submenú, con `aria-expanded` y `aria-controls`.
- `resources/views/superadmin/sidebar.blade.php:75` — **Media** — Submenú activado mediante enlace sin destino. **Recomendación:** cambiarlo por botón semántico y comunicar su estado.
- `resources/views/superadmin/sidebar.blade.php:95` — **Media** — Submenú activado mediante enlace sin destino. **Recomendación:** cambiarlo por botón semántico y comunicar su estado.
- `resources/views/superadmin/sidebar.blade.php:105` — **Media** — Submenú activado mediante enlace sin destino. **Recomendación:** cambiarlo por botón semántico y comunicar su estado.
- `resources/views/superadmin/sidebar.blade.php:164` — **Media** — Elemento de navegación usa `href="#"`. **Recomendación:** enlazar a una ruta real o usar botón si solo controla UI.
- `resources/views/superadmin/sidebar.blade.php:173` — **Media** — Elemento de navegación usa `href="#"`. **Recomendación:** enlazar a una ruta real o usar botón.
- `resources/views/superadmin/sidebar.blade.php:179` — **Media** — Elemento de navegación usa `href="#"`. **Recomendación:** enlazar a una ruta real o usar botón.

## resources/views/auth/login.blade.php

- `resources/views/auth/login.blade.php:17` — **Media** — El logo no declara `width` y `height`. **Recomendación:** añadir dimensiones HTML para evitar cambio acumulativo de layout.
- `resources/views/auth/login.blade.php:96` — **Media** — El correo no declara `autocomplete="email"` ni `spellcheck="false"`. **Recomendación:** añadir ambos atributos.
- `resources/views/auth/login.blade.php:103` — **Media** — La contraseña no declara `autocomplete="current-password"`. **Recomendación:** indicar el propósito al navegador y gestores de contraseñas.
- `resources/views/auth/login.blade.php:104` — **Alta** — El botón de visibilidad es solo un emoji, no tiene `aria-label` ni comunica estado. **Recomendación:** añadir nombre dinámico y `aria-pressed`; marcar el icono decorativo como oculto.
- `resources/views/auth/login.blade.php:135` — **Baja** — Quedó instrumentación de depuración en producción. **Recomendación:** retirar los `console.log` del flujo de autenticación.
- `resources/views/auth/login.blade.php:164` — **Media** — Se registra en consola el correo y la longitud de contraseña introducidos. **Recomendación:** no registrar datos de formularios de autenticación.

## resources/views/auth/register.blade.php

- `resources/views/auth/register.blade.php:16` — **Media** — `height: 100vh` puede cortar el formulario con teclado móvil, zoom o errores. **Recomendación:** usar `min-height: 100dvh`, padding y scroll vertical.
- `resources/views/auth/register.blade.php:50` — **Alta** — `outline: none` elimina el foco sin sustituto equivalente. **Recomendación:** definir un estilo `:focus-visible` de alto contraste.
- `resources/views/auth/register.blade.php:148` — **Media** — Nombre sin `autocomplete="name"`. **Recomendación:** declarar autocomplete adecuado.
- `resources/views/auth/register.blade.php:156` — **Media** — Correo sin `autocomplete="email"` ni `spellcheck="false"`. **Recomendación:** añadir ambos atributos.
- `resources/views/auth/register.blade.php:164` — **Media** — Contraseña sin `autocomplete="new-password"`. **Recomendación:** declarar el token apropiado.
- `resources/views/auth/register.blade.php:172` — **Media** — Confirmación sin `autocomplete="new-password"`. **Recomendación:** declarar el token apropiado.

## resources/views/auth/reset-password.blade.php

- `resources/views/auth/reset-password.blade.php:50` — **Alta** — `outline: none` elimina el indicador de foco. **Recomendación:** reemplazarlo por `:focus-visible` perceptible.
- `resources/views/auth/reset-password.blade.php:104` — **Media** — Correo sin `autocomplete="email"` ni `spellcheck="false"`. **Recomendación:** añadir ambos atributos.
- `resources/views/auth/reset-password.blade.php:107` — **Media** — Nueva contraseña sin `autocomplete="new-password"`. **Recomendación:** declarar el propósito.
- `resources/views/auth/reset-password.blade.php:110` — **Media** — Confirmación sin `autocomplete="new-password"`. **Recomendación:** declarar el propósito.

## resources/views/auth/passwords/email.blade.php

- `resources/views/auth/passwords/email.blade.php:50` — **Alta** — `outline: none` elimina el foco visible. **Recomendación:** conservar outline o proporcionar un reemplazo `:focus-visible`.
- `resources/views/auth/passwords/email.blade.php:92` — **Media** — Correo sin `autocomplete="email"` ni `spellcheck="false"`. **Recomendación:** añadir ambos atributos.

## resources/views/configuracion/index.blade.php

- `resources/views/configuracion/index.blade.php:12` — **Media** — `transition: all` anima propiedades imprevistas. **Recomendación:** enumerar únicamente `transform`, `box-shadow` u `opacity`.
- `resources/views/configuracion/index.blade.php:166` — **Media** — Se actualiza texto asíncrono sin región viva. **Recomendación:** añadir `aria-live="polite"` al contenedor del valor.

## resources/views/clean/index.blade.php

- `resources/views/clean/index.blade.php:12` — **Media** — Usa `transition: all`. **Recomendación:** enumerar las propiedades que realmente cambian.

## resources/views/migration/index.blade.php

- `resources/views/migration/index.blade.php:12` — **Media** — Usa `transition: all`. **Recomendación:** enumerar las propiedades que realmente cambian.

## resources/views/cliente/arbol-genealogico.blade.php

- `resources/views/cliente/arbol-genealogico.blade.php:48` — **Baja** — El texto de carga usa tres puntos ASCII. **Recomendación:** reemplazar `...` por el carácter `…`.
- `resources/views/cliente/arbol-genealogico.blade.php:132` — **Media** — La animación no contempla `prefers-reduced-motion`. **Recomendación:** deshabilitarla o reducirla dentro de la media query correspondiente.
- `resources/views/cliente/arbol-genealogico.blade.php:158` — **Alta** — El error cargado dinámicamente en SVG no se anuncia. **Recomendación:** reflejar el estado en un contenedor HTML con `role="alert"`.

## resources/views/modules/index.blade.php

- `resources/views/modules/index.blade.php:35` — **Alta** — El buscador depende del placeholder y carece de etiqueta accesible. **Recomendación:** añadir `<label>` visible o `aria-label`.
- `resources/views/modules/index.blade.php:38` — **Alta** — El filtro de estado carece de etiqueta accesible. **Recomendación:** asociar un `<label>` mediante `for`/`id`.
- `resources/views/modules/index.blade.php:90` — **Alta** — El campo de código depende del placeholder y carece de etiqueta. **Recomendación:** añadir label, `inputmode="numeric"`, `autocomplete="one-time-code"` y `spellcheck="false"`.
- `resources/views/modules/index.blade.php:95` — **Alta** — Los mensajes insertados por JavaScript no tienen `aria-live`. **Recomendación:** declarar el contenedor como `role="status" aria-live="polite"` y usar `role="alert"` para fallos.
- `resources/views/modules/index.blade.php:171` — **Media** — Al mostrar el campo de verificación no se mueve el foco al control nuevo. **Recomendación:** enfocar el código tras revelar el formulario.

## resources/views/superadmin/oauth-providers/visual-simulator.blade.php

- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:62` — **Alta** — Botón de cierre sin nombre accesible. **Recomendación:** añadir `aria-label="Cerrar simulador"`.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:68` — **Alta** — Un `<div onclick>` actúa como opción y no funciona por teclado. **Recomendación:** usar `<button>` o implementar rol, tabindex y eventos de teclado.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:69` — **Media** — Avatar remoto sin dimensiones explícitas. **Recomendación:** añadir `width` y `height`.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:85` — **Alta** — Otro `<div onclick>` actúa como control sin semántica ni teclado. **Recomendación:** usar `<button>`.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:115` — **Alta** — Botón de cierre sin `aria-label`. **Recomendación:** añadir un nombre accesible específico.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:191` — **Media** — Avatar sin `width` y `height`. **Recomendación:** reservar dimensiones.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:198` — **Alta** — Botón de cerrar sesión solo icono y sin `aria-label`. **Recomendación:** añadir nombre accesible y ocultar el icono decorativo.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:306` — **Media** — `transition: all` puede animar layout. **Recomendación:** limitar la transición a `background-color` y `box-shadow`.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:562` — **Media** — Segundo uso de `transition: all`. **Recomendación:** enumerar propiedades.
- `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:315` — **Media** — El modal no contiene overscroll. **Recomendación:** aplicar `overscroll-behavior: contain` al panel desplazable.

## resources/views/superadmin/oauth-providers/index.blade.php

- `resources/views/superadmin/oauth-providers/index.blade.php:153` — **Alta** — Botón de eliminar solo icono sin `aria-label`; `title` no sustituye el nombre accesible de forma robusta. **Recomendación:** añadir `aria-label="Eliminar proveedor …"`.
- `resources/views/superadmin/oauth-providers/index.blade.php:195` — **Baja** — Estado de carga usa `...`. **Recomendación:** utilizar `…`.
- `resources/views/superadmin/oauth-providers/index.blade.php:222` — **Media** — Estado asíncrono de pruebas sin región viva verificable. **Recomendación:** envolver resultados en `aria-live="polite"`.
- `resources/views/superadmin/oauth-providers/index.blade.php:417` — **Media** — Enlace con `target="_blank"` no declara `rel`. **Recomendación:** añadir `rel="noopener noreferrer"`.

## resources/views/superadmin/dashboard.blade.php

- `resources/views/superadmin/dashboard.blade.php:12` — **Media** — Logo sin dimensiones HTML. **Recomendación:** declarar `width` y `height`.
- `resources/views/superadmin/dashboard.blade.php:70` — **Media** — `transition: all` anima cualquier cambio. **Recomendación:** listar solo las propiedades necesarias.

## resources/views/superadmin/database-configs/index.blade.php

- `resources/views/superadmin/database-configs/index.blade.php:178` — **Media** — Resultado de prueba de conexión se actualiza asíncronamente sin `aria-live` verificable. **Recomendación:** usar una región de estado y mover foco al error cuando requiera acción.

## resources/views/superadmin/email-configs/index.blade.php

- `resources/views/superadmin/email-configs/index.blade.php:235` — **Media** — El resultado de envío se reemplaza mediante `innerHTML` sin región viva. **Recomendación:** declarar `role="status" aria-live="polite"` y `role="alert"` para errores.

## Formularios con controles sin etiqueta asociada

- `resources/views/barrio/form.blade.php:16` — **Alta** — Campo `nombre` sin `id`/label asociado; el placeholder no es etiqueta. **Recomendación:** añadir `id` y `<label for>`.
- `resources/views/barrio/form.blade.php:22` — **Alta** — Campo `localidad` sin etiqueta asociada. **Recomendación:** añadir `id` y `<label for>`.
- `resources/views/ciudade/form.blade.php:5` — **Alta** — Campo `municipio` sin etiqueta asociada. **Recomendación:** añadir label clicable.
- `resources/views/ciudade/form.blade.php:17` — **Alta** — Selector de departamento sin etiqueta asociada. **Recomendación:** añadir `id` y `<label for>`.
- `resources/views/empresa/form.blade.php:12` — **Alta** — Nombre legal sin etiqueta asociada mediante `for`/`id`. **Recomendación:** enlazar label y control.
- `resources/views/empresa/form.blade.php:21` — **Alta** — Representante legal sin etiqueta asociada. **Recomendación:** enlazar label y control.
- `resources/views/empresa/form.blade.php:33` — **Alta** — NIT sin etiqueta asociada. **Recomendación:** enlazar label y control.
- `resources/views/empresa/form.blade.php:44` — **Alta** — Dígito de verificación sin nombre accesible. **Recomendación:** añadir `<label>` o `aria-label`.
- `resources/views/empresa/form.blade.php:107` — **Alta** — Teléfono sin etiqueta asociada. **Recomendación:** enlazar label y añadir `autocomplete="tel"`.
- `resources/views/empresa/form.blade.php:117` — **Alta** — Correo sin etiqueta asociada. **Recomendación:** enlazar label, añadir `autocomplete="email"` y `spellcheck="false"`.
- `resources/views/mensaje-de-bienvenida/form.blade.php:6` — **Alta** — Título sin etiqueta asociada. **Recomendación:** añadir `id` y `<label for>`.
- `resources/views/tipo-documento/form.blade.php:6` — **Alta** — Nombre depende del placeholder. **Recomendación:** añadir etiqueta asociada.
- `resources/views/tipos-empresa/form.blade.php:6` — **Alta** — Nombre depende del placeholder. **Recomendación:** añadir etiqueta asociada.
- `resources/views/livewire/modules/toggle-button.blade.php:13` — **Alta** — Código de verificación sin label. **Recomendación:** añadir etiqueta y `autocomplete="one-time-code"`.

## Botones de icono sin nombre accesible

- `resources/views/barrio/index.blade.php:92` — **Alta** — Eliminar solo muestra icono. **Recomendación:** añadir `aria-label` contextual.
- `resources/views/cliente/index.blade.php:94` — **Alta** — Eliminar solo muestra icono. **Recomendación:** añadir `aria-label` contextual.
- `resources/views/departamento/index.blade.php:94` — **Alta** — Eliminar solo muestra icono. **Recomendación:** añadir `aria-label` contextual.
- `resources/views/document-requirements/index.blade.php:30` — **Alta** — Botón de cierre sin `aria-label`. **Recomendación:** añadir nombre accesible.
- `resources/views/document-requirements/index.blade.php:37` — **Alta** — Botón de cierre sin `aria-label`. **Recomendación:** añadir nombre accesible.
- `resources/views/document-requirements/index.blade.php:89` — **Alta** — Eliminar solo muestra icono. **Recomendación:** añadir `aria-label` contextual.
- `resources/views/mascota/index.blade.php:101` — **Alta** — Eliminar solo muestra icono. **Recomendación:** añadir `aria-label` contextual.
- `resources/views/mascota-documents/index.blade.php:56` — **Alta** — Botón de cierre sin nombre accesible. **Recomendación:** añadir `aria-label="Cerrar"`.
- `resources/views/mascota-documents/index.blade.php:132` — **Alta** — Aprobar solo muestra icono. **Recomendación:** añadir `aria-label` con documento o mascota.
- `resources/views/modules/all-logs.blade.php:209` — **Alta** — Botón de cierre sin nombre accesible. **Recomendación:** añadir `aria-label="Cerrar detalles"`.
- `resources/views/superadmin/backup-configs/create.blade.php:123` — **Alta** — Mostrar contraseña solo icono, sin estado accesible. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/backup-configs/edit.blade.php:124` — **Alta** — Mostrar contraseña solo icono, sin estado accesible. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/database-configs/create.blade.php:94` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/database-configs/edit.blade.php:95` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/email-configs/create.blade.php:98` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/email-configs/edit.blade.php:99` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/oauth-providers/create.blade.php:100` — **Alta** — Mostrar secreto solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/superadmin/oauth-providers/edit.blade.php:94` — **Alta** — Mostrar secreto solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/form.blade.php:396` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/form.blade.php:435` — **Alta** — Mostrar confirmación solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/admin/form.blade.php:135` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/cliente/form.blade.php:149` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/paseador/form.blade.php:135` — **Alta** — Mostrar contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/superadmin/show.blade.php:34` — **Alta** — Botón de información solo icono. **Recomendación:** añadir `aria-label="Información del rol Superadmin"`.
- `resources/views/user/superadmin/show.blade.php:156` — **Alta** — Mostrar contraseña actual solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/superadmin/show.blade.php:169` — **Alta** — Mostrar nueva contraseña solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.
- `resources/views/user/superadmin/show.blade.php:192` — **Alta** — Mostrar confirmación solo icono. **Recomendación:** añadir label dinámico y `aria-pressed`.

## Imágenes sin dimensiones explícitas

- `resources/views/welcome.blade.php:24` — **Media** — Imagen sin `width`/`height`. **Recomendación:** declarar dimensiones para evitar CLS.
- `resources/views/admin/dashboard.blade.php:12` — **Media** — Logo sin dimensiones HTML. **Recomendación:** añadir `width` y `height`.
- `resources/views/cliente/dashboard.blade.php:44` — **Media** — Avatar sin dimensiones HTML. **Recomendación:** reservar dimensiones.
- `resources/views/mascota/show.blade.php:44` — **Media** — Imagen sin dimensiones HTML. **Recomendación:** reservar dimensiones y usar `loading="lazy"` si queda bajo el pliegue.
- `resources/views/paseador/dashboard.blade.php:13` — **Media** — Logo sin dimensiones. **Recomendación:** añadir `width` y `height`.
- `resources/views/pdf/mascotas.blade.php:54` — **Media** — Imagen sin dimensiones. **Recomendación:** declarar dimensiones estables.
- `resources/views/user/form.blade.php:499` — **Media** — Previsualización sin dimensiones. **Recomendación:** reservar el espacio antes de cargar.
- `resources/views/user/cliente/show.blade.php:43` — **Media** — Avatar sin dimensiones HTML. **Recomendación:** declarar `width` y `height`.

## resources/css/app.css

- `resources/css/app.css:5` — **Media** — La clase de tema oscuro no declara `color-scheme: dark`. **Recomendación:** añadir la propiedad o aplicarla en `<html>` al activar el tema.
- `resources/css/app.css:5` — **Media** — El tema solo define colores generales; no fija colores explícitos para `<select>` nativos en Windows. **Recomendación:** declarar `background-color` y `color` para controles en ambos temas.
- `resources/css/app.css:5` — **Baja** — No existe una regla global para `prefers-reduced-motion`. **Recomendación:** incluir una reducción segura para transiciones y animaciones no esenciales.
- `resources/css/app.css:5` — **Baja** — No se define `touch-action: manipulation` para controles táctiles. **Recomendación:** aplicarlo a botones y enlaces interactivos.

## resources/js/app.js

- `resources/js/app.js:8` — **Media** — Activa `.dark-theme` sin sincronizar `color-scheme`. **Recomendación:** actualizar `document.documentElement.style.colorScheme` junto con la clase.
- `resources/js/app.js:10` — **Media** — La preferencia del sistema se guarda como elección explícita y deja de distinguir preferencia manual de automática. **Recomendación:** persistir solo elecciones del usuario; seguir al sistema cuando no exista override.
- `resources/js/app.js:33` — **Media** — Un cambio del sistema sobrescribe una elección previamente guardada. **Recomendación:** atender el evento solo cuando el usuario esté en modo “sistema”.

## public/js/app.js

- `public/js/app.js:7` — **Media** — La copia pública repite el sistema de tema sin establecer `color-scheme`. **Recomendación:** usar un único módulo compilado y sincronizar la propiedad nativa.
- `public/js/app.js:32` — **Media** — Cambio del sistema sobrescribe una preferencia guardada. **Recomendación:** separar modo automático de preferencia manual.
- `public/js/app.js:41` — **Baja** — `console.log` de depuración permanece en el artefacto público. **Recomendación:** retirarlo del build de producción.

## resources/js/bootstrap.js

- `resources/js/bootstrap.js:1` — ✓ Sin hallazgos aplicables a las reglas revisadas.

## public/js/bootstrap.js

- `public/js/bootstrap.js:1` — ✓ Sin hallazgos aplicables a las reglas revisadas.

## Cobertura y limitaciones

- Se revisaron todos los archivos del alcance, incluidos los que no aparecen arriba; se omiten archivos sin hallazgos verificables para mantener señal alta.
- La auditoría es estática. Contraste efectivo, orden de foco, tamaños táctiles, overflow a 320 px, zoom al 200 % y comportamiento por rol requieren una pasada posterior en navegador.
- No se modificó ningún archivo del frontend.
