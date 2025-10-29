<?php

namespace App\Http\Middleware;

use App\Models\Module;
use App\Models\ModuleLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CheckModuleStatus
{
    public function handle(Request $request, Closure $next, string $moduleSlug)
    {
        // Si la tabla 'modules' aún no existe (entorno sin migraciones), permitir el paso
        if (!Schema::hasTable('modules')) {
            Log::warning('Tabla modules no existe, se permite acceso temporal', [
                'slug' => $moduleSlug,
                'path' => $request->path(),
            ]);
            return $next($request);
        }

        try {
            $module = Module::where('slug', $moduleSlug)->first();
        } catch (\Throwable $e) {
            // Si hay error de conexión o tabla, permitir paso para no romper UX
            Log::error('Error consultando tabla modules en middleware', [
                'error' => $e->getMessage(),
                'slug' => $moduleSlug,
            ]);
            return $next($request);
        }

        if (!$module || !$module->status) {
            // Log access denied attempt
            if ($module) {
                ModuleLog::createLog(
                    optional($request->user())->id,
                    $module->id,
                    ModuleLog::ACTION_ACCESS_DENIED,
                    $request->ip(),
                    $request->userAgent()
                );
            }

            Log::warning('Intento de acceso a módulo inactivo o inexistente', [
                'slug' => $moduleSlug,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => optional($request->user())->id,
                'module_id' => $module?->id,
            ]);

            return response()->view('modules.access-denied', [
                'moduleName' => $module?->name ?? $moduleSlug,
            ], 403);
        }

        return $next($request);
    }
}



