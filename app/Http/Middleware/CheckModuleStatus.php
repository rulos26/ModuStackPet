<?php

namespace App\Http\Middleware;

use App\Models\Module;
use App\Models\ModuleLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckModuleStatus
{
    public function handle(Request $request, Closure $next, string $moduleSlug)
    {
        $module = Module::where('slug', $moduleSlug)->first();

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



