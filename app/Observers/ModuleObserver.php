<?php

namespace App\Observers;

use App\Models\Module;
use App\Models\ModuleLog;
use Illuminate\Support\Facades\Log;

class ModuleObserver
{
    /**
     * Handle the Module "created" event.
     */
    public function created(Module $module): void
    {
        Log::info('Nuevo módulo creado', [
            'module_id' => $module->id,
            'module_name' => $module->name,
            'module_slug' => $module->slug,
            'status' => $module->status,
        ]);

        // Log the creation
        ModuleLog::createLog(
            auth()->id() ?? 0,
            $module->id,
            'module_created',
            request()->ip(),
            request()->userAgent()
        );
    }

    /**
     * Handle the Module "updated" event.
     */
    public function updated(Module $module): void
    {
        $changes = $module->getChanges();

        // Log specific changes
        if (isset($changes['status'])) {
            $action = $changes['status'] ? 'activated' : 'deactivated';

            ModuleLog::createLog(
                auth()->id() ?? 0,
                $module->id,
                $action,
                request()->ip(),
                request()->userAgent()
            );

            Log::info('Estado de módulo actualizado', [
                'module_id' => $module->id,
                'module_name' => $module->name,
                'old_status' => !$changes['status'],
                'new_status' => $changes['status'],
            ]);
        }

        if (isset($changes['name']) || isset($changes['description'])) {
            ModuleLog::createLog(
                auth()->id() ?? 0,
                $module->id,
                'module_updated',
                request()->ip(),
                request()->userAgent()
            );

            Log::info('Módulo actualizado', [
                'module_id' => $module->id,
                'module_name' => $module->name,
                'changes' => $changes,
            ]);
        }
    }

    /**
     * Handle the Module "deleted" event.
     */
    public function deleted(Module $module): void
    {
        ModuleLog::createLog(
            auth()->id() ?? 0,
            $module->id,
            'module_deleted',
            request()->ip(),
            request()->userAgent()
        );

        Log::warning('Módulo eliminado', [
            'module_id' => $module->id,
            'module_name' => $module->name,
            'module_slug' => $module->slug,
        ]);
    }

    /**
     * Handle the Module "restored" event.
     */
    public function restored(Module $module): void
    {
        ModuleLog::createLog(
            auth()->id() ?? 0,
            $module->id,
            'module_restored',
            request()->ip(),
            request()->userAgent()
        );

        Log::info('Módulo restaurado', [
            'module_id' => $module->id,
            'module_name' => $module->name,
        ]);
    }

    /**
     * Handle the Module "force deleted" event.
     */
    public function forceDeleted(Module $module): void
    {
        ModuleLog::createLog(
            auth()->id() ?? 0,
            $module->id,
            'module_force_deleted',
            request()->ip(),
            request()->userAgent()
        );

        Log::warning('Módulo eliminado permanentemente', [
            'module_id' => $module->id,
            'module_name' => $module->name,
        ]);
    }
}
