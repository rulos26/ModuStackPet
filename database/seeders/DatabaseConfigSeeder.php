<?php

namespace Database\Seeders;

use App\Models\DatabaseConfig;
use Illuminate\Database\Seeder;

class DatabaseConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear configuración de base de datos desde .env
        DatabaseConfig::updateOrCreate(
            [
                'host' => env('DB_HOST', '127.0.0.1'),
                'database' => env('DB_DATABASE', ''),
            ],
            [
                'connection' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', 3306),
                'database' => env('DB_DATABASE', ''),
                'username' => env('DB_USERNAME', ''),
                'password' => env('DB_PASSWORD', ''),
                'is_active' => true,
            ]
        );

        $this->command->info('Configuración de base de datos creada desde .env');
    }
}
