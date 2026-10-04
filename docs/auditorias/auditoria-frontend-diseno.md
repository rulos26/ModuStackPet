# Auditoría de diseño y frontend (solo lectura)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/auditoria-frontend`

## Método y límites (leer primero)
- De la lista de skills propuesta **ninguna está instalada** en esta sesión (comprobado con `ListSkills`), así que no pude cargar ninguna. Apliqué por mi cuenta el enfoque de **`accessibility` + `web-quality-audit` (Addy Osmani)**: WCAG 2.2 AA, rendimiento, buenas prácticas y consistencia de diseño. Si quieres la auditoría "con" una skill concreta, instálala y la repito.
- **Análisis estático** de `resources/views` (149 vistas Blade), `resources/css`, `resources/js`, `package.json` y layouts. **No** ejecuté la app en navegador, ni Lighthouse, ni medí contraste real, ni probé lector de pantalla. Todo lo que dependa de eso está marcado *(verificar)*.
- No se modificó código de aplicación ni se leyó `.env`.

## Resumen
El frontend funciona pero es un **mosaico**: cada pantalla de autenticación es un HTML independiente con su propia versión de Bootstrap, el panel mezcla dos generaciones de librerías (AdminLTE 3 + Bootstrap 5 + jQuery), hay ~246 referencias a CDN sin SRI y el estilo se resuelve con 239 `style=""` en línea. Lo más importante: **faltan etiquetas accesibles en controles clave, las pantallas de login no muestran bien los errores y hay depuración con datos del formulario en `console.log`**.

Cifras medidas (grep sobre `resources/views`):

| Métrica | Valor |
|---|---|
| Vistas Blade | 149 |
| Documentos HTML completos fuera del layout (`<!DOCTYPE`) | 12 (5 de auth, 2 emails, 3 PDF, welcome…) |
| Recursos CDN sin atributo `integrity` (SRI) | 0 con integrity; ~246 referencias sin él |
| Vistas que re-cargan DataTables + pdfmake + jszip desde CDN | 14 |
| `<img>` / sin `alt` | 25 / 11 |
| Estilos en línea `style="..."` | 239 |
| `onclick=` en línea | 39 |
| `console.log` en vistas | 15 |
| `alert()` / `confirm()` nativos | 21 |
| Tablas (`<table>`) / con `scope` en `<th>` o `<caption>` | 34 / 0 |
| Controles de auth con `autocomplete` | 0 de 4 pantallas |

## Hallazgos (ordenados por prioridad)

### Alta
**F-01 · Pantallas de autenticación fuera del layout y desalineadas.** `login`, `register`, `forgot-password`, `reset-password` y `passwords/email` son HTML completo, cada una con su propio `<head>` y versiones distintas de Bootstrap (login usa 5.3.0, el layout 5.3.2). Resultado: estilos y comportamiento distintos entre pantallas, sin favicon/fuente común, mantenimiento x5. *Recomendación:* un layout `layouts/guest.blade.php` compartido.

**F-02 · Errores de formulario no accesibles / ausentes.** `forgot-password.blade.php` no tiene ningún `@error`/`$errors`/mensaje de estado (0 coincidencias): un correo inválido o el aviso "enlace enviado" no se muestran. En login/register/reset los errores existen pero sin `aria-describedby`, `aria-invalid` ni `role="alert"` (0–1 atributos `aria-` por pantalla). Incumple WCAG 3.3.1/3.3.3 y 4.1.3. *Recomendación:* bloque de errores con `role="alert"` y enlazado a cada campo.

**F-03 · Botón de mostrar/ocultar contraseña solo con emoji.** `login.blade.php` usa `<button onclick="togglePassword()">👁️</button>` sin `aria-label` ni estado `aria-pressed`: un lector de pantalla anuncia "botón" sin nombre (WCAG 4.1.2, 1.1.1). *Recomendación:* `aria-label="Mostrar contraseña"` + `aria-pressed`, y texto/ícono SVG.

**F-04 · Depuración con datos del formulario en producción.** `login.blade.php` registra en consola el correo digitado, longitud de la contraseña, método del formulario y presencia de CSRF (15 `console.log` en el proyecto). Es ruido y fuga menor de información; además contradice la política de logs del backend. *Recomendación:* eliminar los `console.log`/`console.error` de las vistas.

**F-05 · Navegación del panel con dos generaciones de librería mezcladas.** `layouts/navbar.blade.php` mezcla `data-widget="pushmenu"` (AdminLTE 3, basado en **Bootstrap 4** + jQuery) con `data-bs-toggle="dropdown"` (**Bootstrap 5**). AdminLTE 3.2 y Bootstrap 5.3 no están diseñados para convivir; es una fuente probable de dropdowns/estilos rotos *(verificar en navegador)*. Los enlaces `href="#"` con `role="button"` y el ícono de notificaciones (`<a ...><i class="far fa-bell"></i>`) no tienen nombre accesible (WCAG 4.1.2, 2.4.4). *Recomendación:* decidir una sola base (AdminLTE 4 / Bootstrap 5 puro, o AdminLTE 3 con Bootstrap 4) y dar `aria-label` a los controles solo-ícono.

**F-06 · Tablas sin semántica.** 34 tablas, 0 con `scope` en `<th>` ni `<caption>`/`aria-label`; los botones de acción son solo ícono (revisar nombres accesibles). WCAG 1.3.1. *Recomendación:* `scope="col"`, título de tabla y `aria-label` en las acciones ("Editar ciudad X").

### Media
**F-07 · CDN sin SRI y dependencias duplicadas.** Ningún `<script>`/`<link>` externo lleva `integrity` ni `crossorigin` (jQuery, Bootstrap, SweetAlert2, AdminLTE, Font Awesome, DataTables, pdfmake, jszip…): un CDN comprometido ejecutaría código en el panel de Superadmin. Además 14 vistas cargan por su cuenta DataTables + Buttons + pdfmake (~MB) en vez de una vez en un bundle. *Recomendación:* compilar con Vite (ya instalado, `package.json`) o añadir SRI; mover DataTables a un `@stack` solo en vistas que lo usen.

**F-08 · Rendimiento: assets pesados y bloqueantes.** Layout carga jQuery, Bootstrap bundle, SweetAlert2 y AdminLTE síncronos antes del contenido, más Google Fonts con `display=swap` (bien) pero 3+ CSS externos en el `<head>`. `public/css/app.css` y Vite/Tailwind (`@tailwind` en `resources/css/app.css`, tailwind en `package.json`) conviven con Bootstrap: **dos sistemas de estilos** declarados, solo uno parece usarse *(verificar)*. *Recomendación:* un único pipeline de CSS; `defer` en scripts; cargar pdfmake/jszip solo al exportar.

**F-09 · Estilo en línea masivo y bloques `<style>` por vista.** 239 `style=""`, 39 `onclick=` y bloques `<style>` en 8+ vistas (welcome, superadmin/dashboard, user/*/show…). Impide CSP estricta, theming y modo oscuro coherente (existe `.dark-theme` en `resources/css/app.css`/`app.js`, no verificado que esté conectado). *Recomendación:* clases utilitarias/CSS compartido; quitar `onclick` por listeners.

**F-10 · Imagen del logo con ruta sospechosa.** `login.blade.php`: `asset('public/storage/img/logo.jpg')` genera `/public/storage/img/...`; si el servidor sirve desde `public/`, esa URL da 404 *(verificar)*. `<img>` sin `alt`: 11 de 25 (el logo sí lo tiene como "Logo", poco descriptivo). WCAG 1.1.1. *Recomendación:* `asset('storage/img/logo.jpg')` y `alt` útil o `alt=""` si es decorativa.

**F-11 · Formularios sin `autocomplete` ni ayuda.** Ninguna pantalla de auth define `autocomplete` (`username`, `current-password`, `new-password`, `email`): peor experiencia con gestores de contraseñas y WCAG 1.3.5. 91 `placeholder` en el proyecto: revisar que no sustituyan a `<label>`. Los `required` no tienen indicador visual/textual más allá del atributo HTML.

**F-12 · Idioma mezclado.** Interfaz en español con restos en inglés de los generadores CRUD: `__('Create')`, `__('Update')`, `__('Show')`, `__('Back')`, `__('Edit')`, `__('Submit')` (≈33 usos) y mensajes "created successfully" en controladores; `<title>` genérico ("Iniciar Sesión"). Sin archivos de traducción activos, se ve inglés. WCAG 3.1.2 / consistencia. *Recomendación:* `lang/es.json` o reemplazar por textos propios.

### Baja
**F-13 · `alert()`/`confirm()` nativos (21).** Mezcla con SweetAlert2 (14 vistas) y 6 `onsubmit="return confirm(...)"`: confirmaciones inconsistentes y no estilizables. Unificar en un componente.

**F-14 · Contraste y jerarquía** *(verificar con herramienta)*: 157 usos de `text-muted` (en Bootstrap 5.3 es #6c757d sobre blanco ≈ 4.7:1, justo en el límite AA para texto pequeño; sobre `#f4f6f9` de las cabeceras de tabla baja), y 3 usos de `#ccc` como color. El layout usa `<h1>` en el encabezado de contenido y las pantallas de auth otro `<h1>`; revisar que no haya dos `<h1>` por página.

**F-15 · Enlaces y pie.** El pie usa `<a href="#">` para el nombre de la app; un `target="_blank"` sin `rel="noopener"`; versión fija "1.0.0" en el footer.

**F-16 · Foco y teclado** *(verificar)*: no se vio estilo `:focus-visible` propio; los dropdowns dependen del comportamiento de Bootstrap/AdminLTE (ver F-05). No hay "skip link" al contenido principal (WCAG 2.4.1).

## Lo que está bien
- `<html lang>` correcto y `viewport` sin bloquear zoom (0 `user-scalable`/`maximum-scale`).
- Tablas envueltas en `.table-responsive` (32 de 34) y DataTables con idioma español.
- Labels asociadas con `for`/`id` en las pantallas de auth; CSRF presente.
- `preconnect` + `display=swap` en Google Fonts.
- Íconos de acción de tabla: 0 botones solo-ícono sin `title` según el patrón buscado (aun así falta `aria-label`, F-06).

## Plan sugerido (tareas, no creadas)
1. **Layout de invitado + auth accesible** (F-01, F-02, F-03, F-04, F-10, F-11): una tarea acotada, alto impacto, riesgo bajo; tocan solo `resources/views/auth/*` y un layout nuevo.
2. **Decidir la base de UI** (F-05, F-08): decisión humana (¿AdminLTE 3 o Bootstrap 5/AdminLTE 4? ¿Tailwind sí/no?). Bloquea el resto.
3. **Bundle de assets con Vite + SRI** (F-07, F-08, F-09): quitar CDNs por vista.
4. **Tablas y acciones accesibles + textos en español** (F-06, F-12, F-13).
5. **Medición real**: Lighthouse/axe en `/login`, `/register`, un dashboard y un listado, y pasada con lector de pantalla para confirmar los puntos *(verificar)*.

## Preguntas para el humano
- ¿Qué skill de la lista quieres que use de verdad (hay que instalarla) y sobre qué pantallas (todas, o login + un panel)?
- ¿AdminLTE 3 + Bootstrap 5 es una decisión consciente o herencia? ¿Se mantiene Tailwind?
