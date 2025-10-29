<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    protected $timeout = 1800; // Tiempo en segundos (30 minutos)

    public function handle($request, Closure $next)
    {
        // Excluir rutas de autenticación y logout del timeout de sesión
        if ($request->is('login', 'logout', 'register', 'password/*', 'email/verify*')) {
            return $next($request);
        }

        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $this->timeout)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                // Redirigir al login con mensaje claro
                return redirect()->route('login')->with('message', 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.');
            }

            session(['last_activity' => $currentTime]);
        }

        return $next($request);
    }
}
