<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect user to provider's authentication page
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback from provider
     */
    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();

            // Verificar si el usuario ya tiene una cuenta social con este provider
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($socialAccount) {
                // Usuario ya existe con esta cuenta social, hacer login
                Auth::login($socialAccount->user);
                return $this->redirectAfterLogin($socialAccount->user);
            }

            // Buscar usuario existente por email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Usuario existe pero no tiene esta cuenta social vinculada
                // Vincular la cuenta social
                $user->socialAccounts()->create([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar_url' => $socialUser->getAvatar(),
                ]);

                Auth::login($user);
                return $this->redirectAfterLogin($user);
            }

            // Crear nuevo usuario
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Usuario',
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(32)), // Password aleatorio
                'email_verified_at' => now(), // Verificado automáticamente con OAuth
                'activo' => true,
            ]);

            // Asignar rol "Cliente" automáticamente
            $user->assignRole('Cliente');

            // Crear perfil de cliente
            Cliente::create([
                'user_id' => $user->id,
                'nombre' => $user->name,
                'avatar' => $socialUser->getAvatar(),
            ]);

            // Guardar cuenta social
            $user->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar_url' => $socialUser->getAvatar(),
            ]);

            // Actualizar avatar del usuario si viene del provider
            if ($socialUser->getAvatar()) {
                $user->update(['avatar' => $socialUser->getAvatar()]);
            }

            Auth::login($user);

            return redirect()->route('cliente.dashboard')
                ->with('success', '¡Bienvenido! Te has registrado exitosamente con ' . ucfirst($provider) . '.');

        } catch (\Exception $e) {
            \Log::error('Error en autenticación OAuth', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Error al autenticar con ' . ucfirst($provider) . '. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Validate that the provider is allowed
     */
    protected function validateProvider(string $provider): void
    {
        $allowedProviders = ['google', 'facebook'];

        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'Provider no permitido');
        }
    }

    /**
     * Redirect user after login based on their role
     */
    protected function redirectAfterLogin(User $user): RedirectResponse
    {
        if ($user->hasRole('Paseador')) {
            return redirect()->route('paseador.dashboard')
                ->with('success', '¡Bienvenido de nuevo!');
        }

        return redirect()->route('cliente.dashboard')
            ->with('success', '¡Bienvenido de nuevo!');
    }
}
