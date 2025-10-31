<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // Módulos principales del sistema
            [
                'name' => 'Módulos del Sistema',
                'slug' => 'modulos',
                'description' => 'Administración centralizada de todos los módulos del sistema',
                'status' => true,
            ],
            [
                'name' => 'Gestión de Mascotas',
                'slug' => 'mascotas',
                'description' => 'Módulo para gestionar información de mascotas, razas y vacunas',
                'status' => true,
            ],
            [
                'name' => 'Certificados y Vacunas',
                'slug' => 'certificados',
                'description' => 'Módulo para gestionar certificados médicos y vacunas de mascotas',
                'status' => true,
            ],
            [
                'name' => 'Reportes PDF',
                'slug' => 'reportes',
                'description' => 'Generación de reportes y documentos PDF',
                'status' => true,
            ],
            [
                'name' => 'Gestión de Empresas',
                'slug' => 'empresas',
                'description' => 'Administración de empresas y tipos de empresa',
                'status' => true,
            ],
            [
                'name' => 'Configuración del Sistema',
                'slug' => 'configuracion',
                'description' => 'Configuraciones generales del sistema',
                'status' => true,
            ],
            [
                'name' => 'Migraciones de Base de Datos',
                'slug' => 'migraciones',
                'description' => 'Gestión de migraciones de base de datos',
                'status' => true,
            ],
            [
                'name' => 'Ejecución de Seeders',
                'slug' => 'seeders',
                'description' => 'Gestión y ejecución segura de seeders del sistema',
                'status' => true,
            ],
            [
                'name' => 'Limpieza del Sistema',
                'slug' => 'clean',
                'description' => 'Herramientas de limpieza y mantenimiento del sistema',
                'status' => true,
            ],
            // Módulos adicionales (pueden estar desactivados inicialmente)
            [
                'name' => 'Geolocalización',
                'slug' => 'geolocalizacion',
                'description' => 'Módulo para servicios de geolocalización y mapas',
                'status' => false,
            ],
            [
                'name' => 'Notificaciones Electrónicas',
                'slug' => 'notificaciones',
                'description' => 'Sistema de notificaciones push y por correo electrónico',
                'status' => false,
            ],
            [
                'name' => 'Gestión de Usuarios',
                'slug' => 'usuarios',
                'description' => 'Administración de usuarios, roles y permisos',
                'status' => true,
            ],
        ];

        foreach ($modules as $moduleData) {
            Module::updateOrCreate(
                ['slug' => $moduleData['slug']],
                $moduleData
            );
        }

        $this->command->info('Módulos del sistema registrados correctamente.');
    }
}
