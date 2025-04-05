<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    protected $timeout = 1800; // Tiempo en segundos (30 minutos)

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $this->timeout)) {
                Auth::logout();
                session()->invalidate();

                return redirect('/')->with('message', 'Sesión cerrada por inactividad.');
            }

            session(['last_activity' => $currentTime]);
        }

        return $next($request);
    }
}
