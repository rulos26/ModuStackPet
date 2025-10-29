<?php

namespace App\Console\Commands;

use App\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ModulesSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:sync
                            {--force : Force sync even if cache is fresh}
                            {--clear-cache : Clear module cache after sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza el estado de los módulos entre la base de datos y el caché';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando sincronización de módulos...');

        try {
            // Obtener todos los módulos de la base de datos
            $modules = Module::all();

            $this->info("📊 Encontrados {$modules->count()} módulos en la base de datos");

            // Crear caché de módulos activos
            $activeModules = $modules->where('status', true)->pluck('slug')->toArray();
            $inactiveModules = $modules->where('status', false)->pluck('slug')->toArray();

            // Guardar en caché
            Cache::put('modules.active', $activeModules, now()->addHours(24));
            Cache::put('modules.inactive', $inactiveModules, now()->addHours(24));

            $this->info("✅ Caché actualizado:");
            $this->line("   - Módulos activos: " . count($activeModules));
            $this->line("   - Módulos inactivos: " . count($inactiveModules));

            // Mostrar lista de módulos
            if ($this->option('verbose')) {
                $this->table(
                    ['Nombre', 'Slug', 'Estado'],
                    $modules->map(function ($module) {
                        return [
                            $module->name,
                            $module->slug,
                            $module->status ? '✅ Activo' : '❌ Inactivo'
                        ];
                    })->toArray()
                );
            }

            // Verificar inconsistencias
            $this->checkInconsistencies($modules);

            // Limpiar caché si se solicita
            if ($this->option('clear-cache')) {
                $this->clearModuleCache();
            }

            // Log de la operación
            Log::info('Sincronización de módulos completada', [
                'active_count' => count($activeModules),
                'inactive_count' => count($inactiveModules),
                'total_count' => $modules->count(),
            ]);

            $this->info('🎉 Sincronización completada exitosamente');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error durante la sincronización: ' . $e->getMessage());

            Log::error('Error en sincronización de módulos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Verificar inconsistencias en los módulos
     */
    private function checkInconsistencies($modules)
    {
        $this->info('🔍 Verificando inconsistencias...');

        $issues = [];

        // Verificar módulos sin slug
        $modulesWithoutSlug = $modules->where('slug', '');
        if ($modulesWithoutSlug->count() > 0) {
            $issues[] = "Módulos sin slug: " . $modulesWithoutSlug->pluck('name')->join(', ');
        }

        // Verificar slugs duplicados
        $duplicateSlugs = $modules->groupBy('slug')->filter(function ($group) {
            return $group->count() > 1;
        });
        if ($duplicateSlugs->count() > 0) {
            $issues[] = "Slugs duplicados: " . $duplicateSlugs->keys()->join(', ');
        }

        // Verificar módulos con nombres muy largos
        $longNames = $modules->filter(function ($module) {
            return strlen($module->name) > 100;
        });
        if ($longNames->count() > 0) {
            $issues[] = "Nombres muy largos: " . $longNames->pluck('name')->join(', ');
        }

        if (count($issues) > 0) {
            $this->warn('⚠️  Se encontraron inconsistencias:');
            foreach ($issues as $issue) {
                $this->line("   - {$issue}");
            }
        } else {
            $this->info('✅ No se encontraron inconsistencias');
        }
    }

    /**
     * Limpiar caché de módulos
     */
    private function clearModuleCache()
    {
        $this->info('🧹 Limpiando caché de módulos...');

        Cache::forget('modules.active');
        Cache::forget('modules.inactive');

        $this->info('✅ Caché limpiado');
    }
}
