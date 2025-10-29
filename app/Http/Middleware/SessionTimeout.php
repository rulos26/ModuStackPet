<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Obtener el timeout de sesión desde la configuración
     * Si no existe en BD, usar valor por defecto (1800 segundos = 30 minutos)
     */
    protected function getTimeout()
    {
        try {
            return \App\Models\Configuracion::getSessionTimeout();
        } catch (\Exception $e) {
            // Si hay error al leer la configuración, usar valor por defecto
            return 1800; // 30 minutos
        }
    }

    public function handle($request, Closure $next)
    {
        // Excluir rutas de autenticación y logout del timeout de sesión
        if ($request->is('login', 'logout', 'register', 'password/*', 'email/verify*')) {
            return $next($request);
        }

        if (Auth::check()) {
            $timeout = $this->getTimeout();
            $lastActivity = session('last_activity');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $timeout)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                // Convertir timeout a minutos para el mensaje
                $minutos = round($timeout / 60);

                // Redirigir al login con mensaje claro
                return redirect()->route('login')->with('message', "Tu sesión ha expirado por inactividad (más de {$minutos} minutos). Por favor, inicia sesión nuevamente.");
            }

            session(['last_activity' => $currentTime]);
        }

        return $next($request);
    }
}
