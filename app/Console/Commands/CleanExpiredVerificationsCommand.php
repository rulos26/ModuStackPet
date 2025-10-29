<?php

namespace App\Console\Commands;

use App\Models\ModuleVerification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanExpiredVerificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:clean-verifications
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia las verificaciones de módulos expiradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Limpiando verificaciones expiradas...');

        try {
            // Contar verificaciones expiradas
            $expiredCount = ModuleVerification::where('expires_at', '<', now())->count();

            if ($expiredCount === 0) {
                $this->info('✅ No hay verificaciones expiradas para limpiar');
                return Command::SUCCESS;
            }

            $this->info("📊 Encontradas {$expiredCount} verificaciones expiradas");

            if ($this->option('dry-run')) {
                $this->warn('🔍 Modo dry-run: No se eliminarán registros');

                $expiredVerifications = ModuleVerification::where('expires_at', '<', now())
                    ->with(['user', 'module'])
                    ->get();

                $this->table(
                    ['ID', 'Usuario', 'Módulo', 'Acción', 'Expira', 'Usado'],
                    $expiredVerifications->map(function ($verification) {
                        return [
                            $verification->id,
                            $verification->user?->name ?? 'N/A',
                            $verification->module?->name ?? 'N/A',
                            $verification->action,
                            $verification->expires_at->format('d/m/Y H:i:s'),
                            $verification->used_at ? 'Sí' : 'No'
                        ];
                    })->toArray()
                );

                return Command::SUCCESS;
            }

            // Confirmar eliminación
            if (!$this->confirm("¿Estás seguro de que quieres eliminar {$expiredCount} verificaciones expiradas?")) {
                $this->info('❌ Operación cancelada');
                return Command::SUCCESS;
            }

            // Eliminar verificaciones expiradas
            $deletedCount = ModuleVerification::cleanupExpired();

            $this->info("✅ Se eliminaron {$deletedCount} verificaciones expiradas");

            // Log de la operación
            Log::info('Verificaciones expiradas eliminadas', [
                'deleted_count' => $deletedCount,
                'executed_by' => 'artisan_command',
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error durante la limpieza: ' . $e->getMessage());

            Log::error('Error limpiando verificaciones expiradas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
