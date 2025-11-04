<?php

namespace Database\Seeders;

use App\Models\EmailConfig;
use Illuminate\Database\Seeder;

class EmailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear configuración de correo desde .env
        EmailConfig::updateOrCreate(
            [
                'host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'from_address' => env('MAIL_FROM_ADDRESS', ''),
            ],
            [
                'mailer' => env('MAIL_MAILER', 'smtp'),
                'host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'port' => env('MAIL_PORT', 587),
                'username' => env('MAIL_USERNAME', ''),
                'password' => env('MAIL_PASSWORD', ''),
                'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'from_address' => env('MAIL_FROM_ADDRESS', ''),
                'from_name' => env('MAIL_FROM_NAME', config('app.name')),
                'is_active' => true,
            ]
        );

        $this->command->info('Configuración de correo electrónico creada desde .env');
    }
}
