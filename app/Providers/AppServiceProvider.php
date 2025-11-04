<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Models\Module;
use App\Models\DatabaseConfig;
use App\Models\EmailConfig;
use App\Observers\ModuleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Usar plantillas de paginación Bootstrap 5 (AdminLTE está basado en Bootstrap)
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $roles = Auth::user()->roles->pluck('name');
                $view->with('roles', $roles);
            }
        });

        // Register Module Observer
        Module::observe(ModuleObserver::class);

        // Cargar configuración de BD desde la tabla si existe y está activa
        $this->loadDatabaseConfigFromDatabase();

        // Cargar configuración de Email desde la tabla si existe y está activa
        $this->loadEmailConfigFromDatabase();
    }

    /**
     * Cargar configuración de base de datos desde la tabla database_configs
     * Si existe una configuración activa, se usa en lugar del .env
     */
    protected function loadDatabaseConfigFromDatabase(): void
    {
        try {
            // Verificar si la tabla existe (para evitar errores en migraciones iniciales)
            if (!Schema::hasTable('database_configs')) {
                return;
            }

            // Obtener configuración activa desde la BD
            $dbConfig = DatabaseConfig::getActive();

            if (!$dbConfig || !$dbConfig->isConfigured()) {
                // No hay configuración activa o no está completa, usar .env
                return;
            }

            // Obtener el driver de la configuración
            $driver = $dbConfig->connection ?? env('DB_CONNECTION', 'mysql');

            // Construir la configuración completa
            $config = $dbConfig->toConfigArray();

            // Aplicar la configuración a Laravel
            Config::set("database.connections.{$driver}", $config);
            Config::set('database.default', $driver);

            // Log para debugging (solo en desarrollo)
            if (config('app.debug')) {
                \Log::info('Configuración de BD cargada desde database_configs', [
                    'driver' => $driver,
                    'host' => $config['host'],
                    'database' => $config['database'],
                ]);
            }

        } catch (\Exception $e) {
            // Si hay error, usar .env (fallback seguro)
            if (config('app.debug')) {
                \Log::warning('Error al cargar configuración de BD desde tabla, usando .env', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Cargar configuración de correo electrónico desde la tabla email_configs
     * Si existe una configuración activa, se usa en lugar del .env
     */
    protected function loadEmailConfigFromDatabase(): void
    {
        try {
            // Verificar si la tabla existe (para evitar errores en migraciones iniciales)
            if (!Schema::hasTable('email_configs')) {
                return;
            }

            // Obtener configuración activa desde la BD
            $emailConfig = EmailConfig::getActive();

            if (!$emailConfig || !$emailConfig->isConfigured()) {
                // No hay configuración activa o no está completa, usar .env
                return;
            }

            // Aplicar la configuración a Laravel
            // Actualizar configuración del mailer por defecto
            Config::set('mail.default', $emailConfig->mailer);
            
            // Actualizar configuración del mailer SMTP específico
            Config::set('mail.mailers.smtp.host', $emailConfig->host);
            Config::set('mail.mailers.smtp.port', $emailConfig->port);
            Config::set('mail.mailers.smtp.username', $emailConfig->username);
            Config::set('mail.mailers.smtp.password', $emailConfig->password);
            Config::set('mail.mailers.smtp.encryption', $emailConfig->encryption);
            
            // Actualizar configuración global "from"
            Config::set('mail.from.address', $emailConfig->from_address);
            Config::set('mail.from.name', $emailConfig->from_name ?? config('app.name'));

            // Log para debugging (solo en desarrollo)
            if (config('app.debug')) {
                \Log::info('Configuración de Email cargada desde email_configs', [
                    'mailer' => $emailConfig->mailer,
                    'host' => $emailConfig->host,
                    'from_address' => $emailConfig->from_address,
                ]);
            }

        } catch (\Exception $e) {
            // Si hay error, usar .env (fallback seguro)
            if (config('app.debug')) {
                \Log::warning('Error al cargar configuración de Email desde tabla, usando .env', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
