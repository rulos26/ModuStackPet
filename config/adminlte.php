<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Título del Panel de Administración
    |--------------------------------------------------------------------------
    |
    | Configura el título que aparecerá en la pestaña del navegador.
    | Puedes agregar prefijos o sufijos según lo necesites.
    |
    */

    'title' => 'ModuStack Admin', // Título principal del panel
    'title_prefix' => '', // Prefijo opcional para el título
    'title_postfix' => ' - ModuStack', // Sufijo opcional para el título

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Controla si el panel usará solo un ícono `.ico` o permitirá otros formatos.
    |
    */

    'use_ico_only' => true, // Usa solo un favicon en formato .ico
    'use_full_favicon' => false, // Permite usar otros formatos como .png o .svg
    'favicon' => 'vendor/adminlte/dist/img/favico/modustack-auth-logo.ico',


    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>ModuStack</b>LTE',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menú de Usuario
    |--------------------------------------------------------------------------
    |
    | Configura las opciones del menú de usuario en la barra superior.
    | Puedes mostrar la imagen de perfil, un encabezado y un enlace al perfil.
    |
    */

    'usermenu_enabled' => true, // Habilita el menú de usuario en la barra superior

    'usermenu_header' => true, // Muestra un encabezado con el nombre del usuario
    'usermenu_header_class' => 'bg-dark', // Clase CSS para el encabezado del menú

    'usermenu_image' => true, // Muestra la imagen de perfil del usuario
    'usermenu_desc' => true, // Muestra la descripción o rol del usuario

    'usermenu_profile_url' => true, // Activa el enlace al perfil del usuario
    /*
|--------------------------------------------------------------------------
| Configuración del Layout
|--------------------------------------------------------------------------
|
| Aquí puedes personalizar la estructura visual de tu panel de administración.
| Puedes activar o desactivar diferentes opciones de diseño.
| 
| Más información en la documentación:
| https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
|
*/

    // Activar el diseño con barra de navegación superior en lugar del sidebar
    'layout_topnav' => false, // true = Navbar en la parte superior, false = Sidebar lateral

    // Activar el diseño en caja (boxed), ideal para pantallas grandes
    'layout_boxed' => false, // true = Contenido centrado con márgenes, false = Pantalla completa

    // Fijar el sidebar (menú lateral) para que no se desplace con el contenido
    'layout_fixed_sidebar' => true, // true = Sidebar fijo, false = Sidebar desplazable

    // Fijar la barra de navegación superior
    'layout_fixed_navbar' => true, // true = Navbar fija, false = Navbar desplazable

    // Fijar el footer (pie de página)
    'layout_fixed_footer' => false, // true = Footer fijo, false = Footer desplazable

    // Activar modo oscuro para toda la plantilla
    'layout_dark_mode' => false, // true = Modo oscuro activado, false = Modo claro

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    /* 'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary', */
    /*
|--------------------------------------------------------------------------
| Estilos para las Vistas de Autenticación
|--------------------------------------------------------------------------
|
| Aquí puedes personalizar el diseño de las vistas de login, registro y recuperación de contraseña.
| Puedes modificar las clases CSS aplicadas a diferentes elementos de la interfaz.
| 
| Más información en la documentación:
| https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
|
*/

    // Clases para la tarjeta (card) del formulario de autenticación
    //'classes_auth_card' => 'card-outline card-dark', // Puedes cambiar 'card-dark' por 'card-primary', 'card-info', etc.
    // 'card-outline card-danger' -> Borde rojo
    // 'card-outline card-success' -> Borde verde

    // Clases para la cabecera del formulario de autenticación
    //'classes_auth_header' => 'text-center text-bold', // Puedes agregar estilos como 'bg-primary' o 'text-white'

    // Clases para el cuerpo del formulario de autenticación
    //'classes_auth_body' => 'p-3', // Agrega padding para mejorar la visualización

    // Clases para el footer del formulario de autenticación
    //'classes_auth_footer' => 'text-center mt-3', // Añade margen superior para separar del contenido

    // Clases para los iconos dentro del formulario de autenticación (ej. iconos en inputs)
    //'classes_auth_icon' => 'fa-lg text-danger', // Puedes cambiar 'text-primary' por 'text-danger', 'text-success', etc.

    // Clases para los botones de autenticación (ej. botón de login)
    //'classes_auth_btn' => 'btn-flat btn-primary', // Puedes cambiar a 'btn-danger', 'btn-success', etc.

    /* 'classes_auth_card' => 'card-outline card-dark',
    'classes_auth_header' => 'text-center text-white bg-dark p-2',
    'classes_auth_body' => 'p-4',
    'classes_auth_footer' => 'text-center mt-4',
    'classes_auth_icon' => 'fa-lg text-light',
    'classes_auth_btn' => 'btn-flat btn-dark', */

    'classes_auth_card' => 'bg-gradient-dark card-outline card-danger',
    'classes_auth_header' => '',
    'classes_auth_body' => 'bg-gradient-dark',
    'classes_auth_footer' => 'text-center',
    'classes_auth_icon' => 'fa-fw text-light',
    'classes_auth_btn' => 'btn-flat btn-light',

    /*
|--------------------------------------------------------------------------
| Clases del Panel de Administración
|--------------------------------------------------------------------------
|
| Aquí puedes personalizar la apariencia del panel de administración, 
| ajustando colores, estilos y comportamiento de los elementos visuales.
|
| Más información en la documentación:
| https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
|
*/

    // Clases CSS aplicadas al cuerpo (body) del panel de administración
    'classes_body' => '', // Puedes agregar 'dark-mode' para activar el modo oscuro

    // Clases CSS para la barra lateral de la marca (logo y nombre del sistema)
    'classes_brand' => 'bg-dark card-outline card-danger', // Cambia el fondo del logo ('bg-dark', 'bg-success', etc.)

    // Clases CSS para el texto de la marca (nombre del sistema)
    'classes_brand_text' => 'text-white text-bold', // Ajusta el color y peso del texto

    // Clases CSS para el contenedor principal del contenido
    'classes_content_wrapper' => 'px-3', // Añade padding lateral para mayor separación

    // Clases CSS para la cabecera del contenido (títulos de cada sección)
    'classes_content_header' => 'mb-3', // Añade margen inferior para mejor separación

    // Clases CSS para el contenido principal
    'classes_content' => 'p-3', // Añade padding interno para mejorar el diseño

    // Clases CSS para la barra lateral
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    // Opción clara: 'sidebar-light-primary'
    // Opción oscura: 'sidebar-dark-primary'

    // Clases CSS para el menú de navegación dentro de la barra lateral
    'classes_sidebar_nav' => '', // Puedes agregar 'nav-flat' para un diseño sin bordes

    // Clases CSS para la barra de navegación superior (navbar)
    'classes_topnav' => 'navbar-white navbar-light',
    // Opción oscura: 'navbar-dark navbar-gray'

    // Clases CSS para los elementos dentro de la barra de navegación
    'classes_topnav_nav' => 'navbar-expand', // Puedes cambiar a '' si no quieres que sea expandible

    // Clases CSS para el contenedor de la barra de navegación
    'classes_topnav_container' => 'container', // Usa 'container-fluid' para ancho completo


    /* 'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container', */
    /*
|--------------------------------------------------------------------------
| Configuración de la Barra Lateral (Sidebar)
|--------------------------------------------------------------------------
|
| Aquí puedes modificar el comportamiento y la apariencia de la barra lateral.
|
| Más información en la documentación:
| https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
|
*/

    // Activa el modo mini sidebar ('true', 'false', 'md' o 'lg')
    'sidebar_mini' => 'lg', // 'lg' muestra iconos cuando está colapsada, 'md' los oculta

    // Define si la barra lateral debe estar colapsada al iniciar la sesión
    'sidebar_collapse' => false, // 'true' la deja minimizada por defecto

    // Ajusta automáticamente el tamaño de la barra lateral al colapsar
    'sidebar_collapse_auto_size' => false, // 'true' ajusta el tamaño dinámicamente

    // Recuerda si el usuario colapsó la barra lateral (requiere localStorage)
    'sidebar_collapse_remember' => false, // 'true' guarda el estado de la barra lateral

    // Evita transiciones visuales al recordar el colapso
    'sidebar_collapse_remember_no_transition' => true, // 'true' desactiva animaciones al recordar estado

    // Tema del scrollbar de la barra lateral ('os-theme-light', 'os-theme-dark', etc.)
    'sidebar_scrollbar_theme' => 'os-theme-light', // Opción oscura: 'os-theme-dark'

    // Define si la barra de desplazamiento debe ocultarse automáticamente ('l', 's', 'm', 'n' o 'false')
    'sidebar_scrollbar_auto_hide' => 'l', // 'l' para ocultarse lentamente, 'false' para siempre visible

    // Usa un menú tipo acordeón (expandir una sección colapsa las demás)
    'sidebar_nav_accordion' => true, // 'false' permite expandir varias secciones a la vez

    // Velocidad de la animación del menú lateral en milisegundos (300 por defecto)
    'sidebar_nav_animation_speed' => 300, // Ajusta a 200 para una animación más rápida


    /* 'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,
 */
    /*
|--------------------------------------------------------------------------
| Personalización de la Barra Lateral Derecha (Control Sidebar)
|--------------------------------------------------------------------------
|
| Configuración recomendada para activar y personalizar la barra lateral 
| derecha con un diseño oscuro, icono visible y sin empujar el contenido.
|
*/

    // Activa la barra lateral derecha por defecto
    'right_sidebar' => true, // 'true' para activarla, 'false' para ocultarla

    // Define el tema de la barra lateral ('dark' para oscuro, 'light' para claro)
    'right_sidebar_theme' => 'dark', // Usamos 'dark' para un fondo oscuro

    // Cambia el icono del botón que abre la barra lateral
    'right_sidebar_icon' => 'fas fa-tools', // Se usará un icono de herramientas (FontAwesome)

    // Hace que la barra lateral se deslice sin empujar el contenido
    'right_sidebar_slide' => true, // 'true' para que se superponga sin mover la pantalla

    // Evita que la barra lateral empuje el contenido al abrirse
    'right_sidebar_push' => false, // 'false' evita que el contenido principal se desplace

    // Define el tema de la barra de desplazamiento ('os-theme-light' o 'os-theme-dark')
    'right_sidebar_scrollbar_theme' => 'os-theme-dark', // Se adapta al fondo oscuro

    // Hace que la barra de desplazamiento siempre sea visible
    'right_sidebar_scrollbar_auto_hide' => 'false', // 'false' la mantiene visible en todo momento

    /* 'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l', */

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
    // 🔹 Elementos de la Barra de Navegación (Top Navbar)
    /* [
        'type' => 'navbar-search',
        'text' => 'Buscar...',
        'topnav_right' => true,
    ],
    [
        'type' => 'fullscreen-widget',
        'topnav_right' => true,
    ], */

    // 🔹 Elementos de la Barra Lateral (Sidebar)
    [
        'type' => 'sidebar-menu-search',
        'text' => 'Buscar...',
    ],

    // 🔹 Sección de Administración
    [
        'text' => 'Dashboard Generos',
        'route' => 'generos.index', // Usando una ruta nombrada
        'icon' => 'fas fa-user-friends',
        'can'   => 'Admin',
    ],
    [
        'text' => 'Dashboard Estados Civiles',
        'route' => 'estados-civiles.index', // Usando una ruta nombrada
        'icon' => 'fas fa-transgender-alt',
        'can'   => 'Admin',
    ],
    [
        'text' => 'Dashboard Metodos Ahorros',
        'route' => 'metodos-ahorros.index', // Usando una ruta nombrada
        'icon'=>'fas fa-money-bill-wave',    
        //'can'   => 'Admin',

    ],
    [
'text' => 'Dashboard Porcentajes Ahorros',
        'route' => 'porcentajes-ahorros.index', // Usando una ruta nombrada
        'icon'=>'fas fa-percentage',    
        //'can'   => 'Admin',
    ],
    // seccion cliente

    [
        'text' => 'Configuración',
        //'route' => 'admin.configuracion',
        'icon' => 'fas fa-cogs',
    ],

    // 🔹 Sección de Ajustes de Cuenta
    ['header' => 'Ajustes de Cuenta'],
    [
        'text' => 'Perfil',
        //'route' => 'admin.perfil',
        'icon' => 'fas fa-fw fa-user',
    ],
    [
        'text' => 'Cambiar Contraseña',
        //'route' => 'admin.cambiar-contrasena',
        'icon' => 'fas fa-fw fa-lock',
    ],

    // 🔹 Menú Multinivel
    [
        'text' => 'Menú Multinivel',
        'icon' => 'fas fa-fw fa-share',
        'submenu' => [
            [
                'text' => 'Nivel 1',
                //'route' => 'nivel1', // Ruta nombrada
            ],
            [
                'text' => 'Nivel 1',
                //'route' => 'nivel1.subnivel',
                'submenu' => [
                    [
                        'text' => 'Nivel 2',
                      //  'route' => 'nivel2',
                    ],
                    [
                        'text' => 'Nivel 2',
                        //'route' => 'nivel2.subnivel',
                        'submenu' => [
                            [
                                'text' => 'Nivel 3',
                         //       'route' => 'nivel3',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // 🔹 Etiquetas de Color
    ['header' => 'Etiquetas'],
    [
        'text' => 'Importante',
        'icon_color' => 'red',
        //'route' => 'importante',
    ],
    [
        'text' => 'Advertencia',
        'icon_color' => 'yellow',
        //'route' => 'advertencia',
    ],
    [
        'text' => 'Información',
        'icon_color' => 'cyan',
        //'route' => 'informacion',
    ],
],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
