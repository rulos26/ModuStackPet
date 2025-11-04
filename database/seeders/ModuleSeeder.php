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
			// Submódulos independientes solicitados
			[
				'name' => 'Bienvenida',
				'slug' => 'bienvenida',
				'description' => 'Módulo para gestionar el mensaje de bienvenida por rol',
				'status' => true,
			],
			[
				'name' => 'Departamentos',
				'slug' => 'departamentos',
				'description' => 'Gestión geográfica: departamentos',
				'status' => true,
			],
			[
				'name' => 'Ciudades',
				'slug' => 'ciudades',
				'description' => 'Gestión geográfica: ciudades',
				'status' => true,
			],
			[
				'name' => 'Sectores',
				'slug' => 'sectores',
				'description' => 'Gestión de sectores industriales o económicos',
				'status' => true,
			],
			[
				'name' => 'Tipos de Empresas',
				'slug' => 'tipos-empresas',
				'description' => 'Administración de tipos de empresas',
				'status' => true,
			],
			[
				'name' => 'Tipo Documentos',
				'slug' => 'tipo-documentos',
				'description' => 'Administración de tipos de documentos',
				'status' => true,
			],
			[
				'name' => 'Rutas de Documentos',
				'slug' => 'paths-documentos',
				'description' => 'Configuración de rutas de almacenamiento de documentos',
				'status' => true,
			],
			// Módulos de gestión de mascotas (independientes)
			[
				'name' => 'Razas',
				'slug' => 'razas',
				'description' => 'Gestión de razas de mascotas',
				'status' => true,
			],
			[
				'name' => 'Barrios',
				'slug' => 'barrios',
				'description' => 'Gestión de barrios por ciudad',
				'status' => true,
			],
			// Módulo de Proveedores OAuth
			[
				'name' => 'Proveedores OAuth',
				'slug' => 'oauth-providers',
				'description' => 'Gestión de proveedores OAuth para autenticación con redes sociales (Google, Facebook, GitHub, etc.)',
				'status' => true,
			],
			// Módulo de Configuración de Base de Datos
			[
				'name' => 'Configuración de Base de Datos',
				'slug' => 'database-config',
				'description' => 'Gestión y configuración de conexiones a bases de datos con test visual de conexión',
				'status' => true,
			],
			// Módulo de Configuración de Correo Electrónico
			[
				'name' => 'Configuración de Correo Electrónico',
				'slug' => 'email-config',
				'description' => 'Configuración de servidor SMTP y envío de correos electrónicos con test de envío',
				'status' => true,
			],
			// Módulo de Backup de Base de Datos
			[
				'name' => 'Backup de Base de Datos',
				'slug' => 'backup-config',
				'description' => 'Configuración y ejecución de copias de seguridad completas de la base de datos de producción',
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
