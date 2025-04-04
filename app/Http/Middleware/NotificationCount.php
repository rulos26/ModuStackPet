<?php

namespace App\Http\Middleware;

use App\Models\NotificacionesChecklist;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NotificationCount
{
    public function handle(Request $request, Closure $next)
    {
        $notificationCount = NotificacionesChecklist::contarNuevas();
        View::share('notificationCount', $notificationCount);
       /*  $notificacionesNuevas = NotificacionesChecklist::contarNuevas();
        view()->share('notificacionesNuevas', $notificacionesNuevas); */
        return $next($request);
    }
}
