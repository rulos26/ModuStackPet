<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\CustomRegisterViewResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RegisterViewResponse::class, CustomRegisterViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar acciones de Fortify
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Configurar vistas personalizadas para las funcionalidades
        Fortify::loginView(function () {
            return view('auth.login'); // Vista para iniciar sesión
        });

        Fortify::registerView(function () {
            return view('auth.register'); // Vista para registrarse
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password'); // Vista para recuperar contraseña
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]); // Vista para restablecer contraseña
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email'); // Vista para verificar correo electrónico
        });

        // Configuración del RateLimiter para evitar abuso en inicio de sesión
        RateLimiter::for('login', function (Request $request) {
            // Genera una clave única basada en el nombre de usuario (por defecto, el email) y la IP del usuario
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            // Permite un máximo de 5 intentos por minuto
            return Limit::perMinute(5)->by($throttleKey);
        });

        // Configuración del RateLimiter para autenticación de dos factores
        RateLimiter::for('two-factor', function (Request $request) {
            // Limita los intentos de autenticación de dos factores a 5 por minuto
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
