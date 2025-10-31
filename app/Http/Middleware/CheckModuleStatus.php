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
            
            // Si el módulo no existe, auto-crearlo con status=true por defecto
            if (!$module) {
                Log::info('Módulo no encontrado, auto-creando con status=true', [
                    'slug' => $moduleSlug,
                    'path' => $request->path(),
                ]);

                // Generar nombre legible desde el slug
                $name = ucwords(str_replace(['-', '_'], ' ', $moduleSlug));
                
                // Crear el módulo automáticamente con status=true por defecto
                $module = Module::create([
                    'name' => $name,
                    'slug' => $moduleSlug,
                    'description' => 'Módulo auto-registrado automáticamente',
                    'status' => true, // Por defecto activo según la regla del sistema
                ]);

                Log::info('Módulo auto-creado exitosamente', [
                    'module_id' => $module->id,
                    'slug' => $moduleSlug,
                    'name' => $name,
                ]);
            }
        } catch (\Throwable $e) {
            // Si hay error de conexión o tabla, permitir paso para no romper UX
            Log::error('Error consultando tabla modules en middleware', [
                'error' => $e->getMessage(),
                'slug' => $moduleSlug,
            ]);
            return $next($request);
        }

        // Verificar si el módulo está inactivo
        if (!$module->status) {
            // Log access denied attempt
            ModuleLog::createLog(
                optional($request->user())->id,
                $module->id,
                ModuleLog::ACTION_ACCESS_DENIED,
                $request->ip(),
                $request->userAgent()
            );

            Log::warning('Intento de acceso a módulo inactivo', [
                'slug' => $moduleSlug,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => optional($request->user())->id,
                'module_id' => $module->id,
            ]);

            return response()->view('modules.access-denied', [
                'moduleName' => $module->name ?? $moduleSlug,
            ], 403);
        }

        return $next($request);
    }
}



