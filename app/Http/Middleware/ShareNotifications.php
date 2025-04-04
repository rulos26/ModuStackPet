<?php

namespace App\Http\Middleware;

use App\Models\NotificacionesChecklist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareNotifications
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $notificacionesNuevas = NotificacionesChecklist::contarNuevas();
        view()->share('notificacionesNuevas', $notificacionesNuevas);

        return $next($request);
    }
}
