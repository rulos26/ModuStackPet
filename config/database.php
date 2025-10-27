<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Conexión predeterminada de base de datos
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar cuál de las conexiones de base de datos deseas
    | usar como predeterminada para las operaciones de base de datos. Esta es
    | la conexión que se utilizará a menos que se especifique otra conexión
    | explícitamente al ejecutar una consulta o instrucción.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Conexiones de base de datos
    |--------------------------------------------------------------------------
    |
    | A continuación se definen todas las conexiones de base de datos para tu
    | aplicación. Se proporciona una configuración de ejemplo para cada sistema
    | de base de datos compatible con Laravel. Puedes agregar o eliminar
    | conexiones según sea necesario.
    |
    | Laravel soporta bases de datos como MySQL, PostgreSQL, SQLite y SQL Server.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'u494150416_B33pE'),
            'username' => env('DB_USERNAME', 'u494150416_uJV3D'),
            'password' => env('DB_PASSWORD', 'evuMy6dD8z'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Tabla de migraciones
    |--------------------------------------------------------------------------
    |
    | Esta tabla realiza un seguimiento de todas las migraciones que ya se han
    | ejecutado para tu aplicación. Usando esta información, Laravel puede
    | determinar cuáles migraciones en disco aún no se han ejecutado.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Bases de datos Redis
    |--------------------------------------------------------------------------
    |
    | Redis es un sistema de almacenamiento clave-valor de código abierto,
    | rápido y avanzado. Laravel facilita su integración para usarlo como
    | caché o almacenamiento de sesiones.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
