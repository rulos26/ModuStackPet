# Auditoría de diseño frontend

**Fecha:** 2026-10-03  
**Rama auditada:** `origin/main` en `60e8debd`  
**Metodología:** revisión estática inspirada en *Web Design Guidelines* de Vercel  
**Alcance:** 149 vistas Blade, layout principal, autenticación, estilos y JavaScript del frontend  
**Fuera de alcance:** cambios de implementación, lógica de negocio y validación visual en navegador autenticado

## Resumen ejecutivo

El frontend es funcional y parte de patrones conocidos de Bootstrap/AdminLTE, pero todavía no ofrece una experiencia coherente ni suficientemente accesible. La deuda más importante está en la arquitectura visual: se mezclan componentes pensados para versiones distintas de Bootstrap, existen dos mecanismos incompatibles para el tema oscuro y la hoja que define sus colores no está integrada al punto de entrada de Vite. Esto puede provocar interfaces distintas según la página y estados visuales que parecen funcionar en el código, pero no llegan al usuario.

La prioridad recomendada es estabilizar primero el sistema base —layout, navegación, tema y accesibilidad del teclado— antes de pulir pantallas individuales. Una vez resuelto, conviene consolidar autenticación y extraer estilos repetidos a componentes y tokens compartidos.

### Resultado por dimensión

| Dimensión | Estado | Síntesis |
|---|---|---|
| Coherencia visual | Deficiente | Login, registro y aplicación usan estilos, tipografías y estructuras diferentes. |
| Accesibilidad | Deficiente | Faltan landmarks, textos alternativos y nombres accesibles; algunos focos son débiles. |
| Navegación | Crítico | Hay enlaces sin destino y el menú de administrador está desactivado. |
| Responsive | Mejorable | La base es adaptable, pero registro usa altura rígida y hay pantallas densas con estilos locales. |
| Mantenibilidad visual | Deficiente | Se encontraron 239 estilos inline y dependencias repetidas en vistas. |
| Rendimiento percibido | Mejorable | CSS/JS de terceros se carga desde CDN y DataTables se repite por pantalla. |

## Hallazgos priorizados

### P0 — Corregir antes de publicar

#### 1. El tema oscuro tiene implementaciones incompatibles y un color transparente

**Evidencia**

- `resources/views/layouts/app.blade.php:123-128` aplica `data-theme` al elemento raíz.
- `resources/js/app.js:8-25` agrega o quita la clase `dark-theme`.
- `resources/css/app.css:5` define estilos mediante `.dark-theme`.
- `public/css/app.css:8-10` usa `[data-theme="dark"]`, pero declara `--text-color: #0000`, que es totalmente transparente.
- `resources/js/app.js:1` importa JavaScript, pero no importa `resources/css/app.css`; el layout solo incluye `resources/js/app.js` mediante Vite en `resources/views/layouts/app.blade.php:134`.

**Impacto:** el control de tema puede no cambiar los estilos o puede ocultar texto si se carga la hoja pública. También multiplica estados difíciles de probar.

**Recomendación:** elegir un único contrato (`data-theme` o clase), centralizar los tokens de color en una sola hoja importada por Vite y añadir pruebas de contraste para ambos temas. Corregir `#0000` por un color opaco con contraste WCAG AA.

#### 2. La navegación principal está incompleta o contiene destinos nulos

**Evidencia**

- La inclusión del menú de administrador está comentada en `resources/views/layouts/sidebar.blade.php:12`.
- El layout solo incluye explícitamente menús para Cliente y Superadmin (`sidebar.blade.php:16,20`).
- “Perfil” apunta a `#` en `resources/views/layouts/navbar.blade.php:98-99`.
- El enlace de marca del pie también apunta a `#` en `resources/views/layouts/app.blade.php:76`.

**Impacto:** algunos roles pueden quedar sin navegación útil y los enlaces aparentan acciones que no hacen nada. Esto afecta directamente la encontrabilidad y la confianza.

**Recomendación:** definir la matriz rol → menú, habilitar cada menú con rutas reales y retirar elementos que todavía no tengan destino. Añadir una prueba de renderizado por rol.

### P1 — Alta prioridad

#### 3. Se mezclan Bootstrap 5.3.2 y AdminLTE 3.2 en el mismo layout

**Evidencia:** `resources/views/layouts/app.blade.php:17,22,82,84` carga Bootstrap 5.3.2 y AdminLTE 3.2, mientras el layout usa simultáneamente atributos `data-bs-*` y convenciones de AdminLTE.

**Impacto:** AdminLTE 3 fue construido alrededor de convenciones de Bootstrap 4. La mezcla aumenta el riesgo de espaciados, dropdowns, formularios y scripts inconsistentes, además de duplicar estilos base.

**Recomendación:** adoptar una combinación oficialmente compatible. Mientras se decide la migración, crear una página de catálogo para comprobar navbar, sidebar, modal, dropdown, alertas, formularios y tablas en todos sus estados.

#### 4. Controles importantes carecen de nombre accesible

**Evidencia**

- El botón visual para abrir el sidebar es solo un icono y no tiene `aria-label` en `resources/views/layouts/navbar.blade.php:5`.
- La campana de notificaciones tampoco tiene nombre accesible en `navbar.blade.php:15-16`.
- El botón para mostrar la contraseña usa únicamente el emoji `👁️`, sin `aria-label` ni `aria-pressed`, en `resources/views/auth/login.blade.php:104`.

**Impacto:** lectores de pantalla anuncian controles sin propósito; el estado mostrar/ocultar contraseña no se comunica.

**Recomendación:** añadir nombres accesibles, reflejar estados con `aria-expanded`/`aria-pressed` y mantener un área táctil mínima de 44 × 44 px.

#### 5. Falta una estructura semántica clara para navegación por teclado

**Evidencia:** el contenido principal está dentro de `<section class="content">` (`resources/views/layouts/app.blade.php:66`), no hay landmark `<main>` ni enlace “Saltar al contenido”; el body comienza en `app.blade.php:44`.

**Impacto:** usuarios de teclado y tecnologías de asistencia deben recorrer toda la navegación en cada pantalla.

**Recomendación:** añadir un enlace de salto visible al recibir foco, usar `<main id="contenido-principal" tabindex="-1">` y verificar una jerarquía única y ordenada de encabezados.

#### 6. Trece imágenes no tienen atributo `alt`

**Evidencia:** análisis estático de etiquetas `<img>` en 149 vistas. Aparecen en:

- `empresa/form.blade.php`, `empresa/pdf.blade.php`, `empresa/show.blade.php`
- `mascota/form.blade.php`
- `mensaje-de-bienvenida/index.blade.php`, `mensaje-de-bienvenida/show.blade.php`
- `user/show.blade.php`
- formularios o detalles de Admin, Cliente, Paseador y Superadmin

**Impacto:** se pierde información para lectores de pantalla y también el contexto cuando una imagen no carga.

**Recomendación:** usar un texto alternativo descriptivo cuando la imagen aporte información y `alt=""` cuando sea decorativa. No derivarlo del nombre del archivo.

#### 7. Login y registro no comparten sistema visual

**Evidencia**

- Login carga Bootstrap 5.3.0 y una estructura propia (`resources/views/auth/login.blade.php:8`).
- Registro usa CSS embebido y Arial (`resources/views/auth/register.blade.php:9`).
- La aplicación usa Source Sans Pro (`resources/views/layouts/app.blade.php:27`).
- Registro elimina el outline nativo en `register.blade.php:50` y no marca errores con `aria-invalid` ni los enlaza mediante `aria-describedby` (`register.blade.php:147-173`).

**Impacto:** la primera experiencia del producto cambia de aspecto entre pasos y los errores son menos claros para teclado y lector de pantalla.

**Recomendación:** crear un layout compartido de autenticación, reutilizar tipografía, espaciado, botones y mensajes; conservar un indicador de foco de alto contraste y asociar cada error a su campo.

### P2 — Prioridad media

#### 8. El registro puede desbordarse en pantallas bajas

**Evidencia:** `resources/views/auth/register.blade.php:16` fija `height: 100vh` mientras la tarjeta contiene cuatro campos, validaciones y opciones OAuth.

**Impacto:** en móviles, teclado virtual, zoom de texto o mensajes de error pueden dejar contenido fuera del viewport.

**Recomendación:** usar `min-height: 100dvh`, permitir scroll vertical y añadir padding adaptable. Probar a 320 px de ancho, 200 % de zoom y orientación horizontal.

#### 9. Los estilos locales impiden una experiencia consistente

**Evidencia:** se detectaron 239 atributos `style` en vistas Blade. Los archivos con más casos son `mascota/show.blade.php` (40), `user/cliente/show.blade.php` (27), `auth/register.blade.php` (16) y `cliente/arbol-genealogico.blade.php` (16).

**Impacto:** colores, espaciados, estados hover y responsive evolucionan de forma distinta por pantalla. Los cambios globales se vuelven costosos.

**Recomendación:** definir tokens mínimos (color, tipografía, espaciado, radio, sombra) y extraer patrones recurrentes a componentes Blade y clases con nombres semánticos. Migrar primero los cuatro archivos más concentrados.

#### 10. Dependencias visuales se repiten dentro de las vistas

**Evidencia:** 15 vistas inicializan DataTables y varias cargan individualmente CSS/JS desde CDN; por ejemplo, `resources/views/vacunas_certificaciones/index.blade.php:9-11,120-131`.

**Impacto:** aumenta el parpadeo, la dependencia de red y el riesgo de diferencias de versión o configuración entre pantallas.

**Recomendación:** crear un componente o bundle único para tablas, cargarlo solo donde se use y centralizar idioma, responsive, estados vacío/cargando/error y acciones.

#### 11. Las notificaciones temporales necesitan una estrategia accesible

**Evidencia:** los mensajes globales usan toasts de SweetAlert con cierre automático a los 3000 ms en `resources/views/layouts/app.blade.php:89-105`.

**Impacto:** tres segundos pueden ser insuficientes para leer; mensajes importantes pueden desaparecer sin quedar disponibles para tecnologías de asistencia.

**Recomendación:** mantener visibles los errores que requieren acción, declarar regiones vivas apropiadas y permitir pausar/cerrar notificaciones no críticas. No depender solo del color.

#### 12. Quedó instrumentación de desarrollo visible en autenticación

**Evidencia:** `resources/views/auth/login.blade.php:135-164` registra en consola la carga, el formulario, el correo y la longitud de la contraseña.

**Impacto:** añade ruido, expone metadatos innecesarios en el navegador y reduce la percepción de acabado del producto.

**Recomendación:** retirar los logs del flujo normal o condicionarlos a un modo de depuración que nunca registre datos introducidos por el usuario.

## Fortalezas observadas

- Los formularios de autenticación sí usan etiquetas `label` enlazadas por `for`/`id`.
- Varias tablas ya adoptan contenedores responsive y DataTables responsive.
- El layout separa navbar, sidebar y contenido en parciales, una buena base para consolidar el sistema visual.
- Existen estados de validación y mensajes de sesión; falta reforzar su semántica accesible.
- Se usa el idioma español en la interfaz y en la configuración de tablas.

## Plan recomendado

### Fase 1 — Base segura y navegable

1. Resolver navegación por roles y enlaces sin destino.
2. Unificar el mecanismo de tema y corregir sus tokens de contraste.
3. Decidir una combinación compatible de framework visual.
4. Añadir nombres accesibles, enlace de salto, `<main>` y textos alternativos.

### Fase 2 — Coherencia del producto

1. Crear layout compartido para login, registro y recuperación.
2. Establecer tokens y componentes base para formulario, tarjeta, botón, alerta y tabla.
3. Migrar las vistas con mayor concentración de estilos inline.

### Fase 3 — Validación visual y accesible

1. Probar flujos por rol en 320, 768, 1024 y 1440 px.
2. Validar teclado completo, foco visible, zoom al 200 % y preferencia de movimiento reducido.
3. Ejecutar axe/Lighthouse y contrast checker sobre tema claro y oscuro.
4. Añadir pruebas visuales de estados: vacío, cargando, error, éxito, contenido largo y datos truncados.

## Criterios de aceptación sugeridos

- No hay enlaces interactivos con `href="#"` sin comportamiento equivalente y accesible.
- Todos los controles tienen nombre y estado accesibles.
- Todas las imágenes tienen `alt` correcto, incluso si es vacío por ser decorativas.
- El contenido principal puede alcanzarse con un enlace de salto.
- El foco siempre es visible y el flujo completo funciona solo con teclado.
- Texto normal cumple contraste mínimo 4.5:1; texto grande, 3:1.
- Login, registro y aplicación comparten tipografía, tokens y componentes.
- Tema claro/oscuro usa un único mecanismo y no produce texto transparente.
- Ninguna pantalla presenta scroll horizontal a 320 px salvo contenido intrínsecamente tabular controlado.
- Los mensajes importantes no desaparecen antes de que el usuario pueda actuar.

## Limitaciones de esta auditoría

La revisión fue estática y no se inició la aplicación ni se utilizaron credenciales. Por ello, los hallazgos de contraste, responsive y comportamiento dinámico deben confirmarse con una segunda pasada visual en un entorno local preparado. No se modificó ningún archivo del frontend durante esta auditoría.
