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
}
