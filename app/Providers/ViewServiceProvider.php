<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Compartir notificaciones con la vista del navbar
        View::composer('layouts.navbar', function ($view) {
            if (auth()->check()) {
                $notificaciones = auth()->user()->notifications()->take(5)->get();
                $view->with('notificaciones', $notificaciones);
            } else {
                $view->with('notificaciones', collect());
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
