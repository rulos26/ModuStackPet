<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nombre de la Aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor es el nombre de tu aplicación, que será utilizado cuando el
    | framework necesite colocar el nombre de la aplicación en una notificación
    | u otros elementos de la interfaz donde se necesite mostrar el nombre.
    |
    */

    'name' => env('APP_NAME', 'ModuStackPet'),


    /*
    |--------------------------------------------------------------------------
    | Entorno de la Aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor determina el "entorno" en el que tu aplicación se está ejecutando.
    | Esto puede determinar cómo prefieres configurar varios servicios que utiliza
    | la aplicación. Configúralo en tu archivo ".env".
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Modo de Depuración de la Aplicación
    |--------------------------------------------------------------------------
    |
    | Cuando tu aplicación está en modo de depuración, se mostrarán mensajes de
    | error detallados con trazas de pila cada vez que ocurra un error. Si está
    | deshabilitado, se mostrará una página de error genérica simple.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL de la Aplicación
    |--------------------------------------------------------------------------
    |
    | Esta URL es utilizada por la consola para generar correctamente URLs al usar
    | la herramienta de línea de comandos Artisan. Debes configurarla en la raíz
    | de la aplicación para que esté disponible en los comandos de Artisan.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Zona Horaria de la Aplicación
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar la zona horaria predeterminada para tu aplicación,
    | que será utilizada por las funciones de fecha y hora de PHP. La zona horaria
    | está configurada como "UTC" por defecto, ya que es adecuada para la mayoría
    | de los casos de uso.
    |
    */

    'timezone' => 'America/Bogota',


    /*
    |--------------------------------------------------------------------------
    | Configuración de Localización de la Aplicación
    |--------------------------------------------------------------------------
    |
    | La localización de la aplicación determina el idioma predeterminado que será
    | utilizado por los métodos de traducción/localización de Laravel. Esta opción
    | puede configurarse a cualquier idioma para el cual planees tener cadenas de
    | traducción.
    |
    */

    'locale' => env('APP_LOCALE', 'es'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'es'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'es_ES'),


    /*
    |--------------------------------------------------------------------------
    | Clave de Encriptación
    |--------------------------------------------------------------------------
    |
    | Esta clave es utilizada por los servicios de encriptación de Laravel y debe
    | configurarse como una cadena aleatoria de 32 caracteres para garantizar que
    | todos los valores encriptados sean seguros. Debes hacer esto antes de
    | desplegar la aplicación.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Controlador del Modo de Mantenimiento
    |--------------------------------------------------------------------------
    |
    | Estas opciones de configuración determinan el controlador utilizado para
    | gestionar el estado del "modo de mantenimiento" de Laravel. El controlador
    | "cache" permitirá que el modo de mantenimiento sea controlado en múltiples
    | máquinas.
    |
    | Controladores soportados: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    

];
