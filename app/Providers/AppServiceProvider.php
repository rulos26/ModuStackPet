<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Module;
use App\Observers\ModuleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Usar plantillas de paginación Bootstrap 5 (AdminLTE está basado en Bootstrap)
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $roles = Auth::user()->roles->pluck('name');
                $view->with('roles', $roles);
            }
        });

        // Register Module Observer
        Module::observe(ModuleObserver::class);
    }
}
