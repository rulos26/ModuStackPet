<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\OAuthProvider;
use App\Models\OAuthTestLog;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect user to provider's authentication page
     */
    public function redirect(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($provider);
        $this->configureProvider($provider);

        // Si es una prueba (viene del simulador)
        $isTest = $request->has('test_session_id');
        $testSessionId = $request->get('test_session_id', Str::uuid()->toString());
        
        if ($isTest) {
            $oauthProvider = OAuthProvider::where('provider', $provider)->first();
            
            // Registrar paso 1: URL generada
            OAuthTestLog::create([
                'oauth_provider_id' => $oauthProvider->id,
                'test_session_id' => $testSessionId,
                'step' => 'redirect_generated',
                'step_data' => json_encode([
                    'provider' => $provider,
                    'redirect_uri' => $oauthProvider->redirect_uri,
                    'timestamp' => now()->toDateTimeString(),
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Agregar parámetro de prueba usando state (mejor compatibilidad con OAuth)
            $driver = Socialite::driver($provider);
            $driver->with(['state' => 'test_' . $testSessionId]);
            
            return $driver->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback from provider
     */
    public function callback(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($provider);
        $this->configureProvider($provider);

        // Detectar si es una prueba (el state viene como "test_{session_id}")
        $state = $request->get('state');
        $isTest = !empty($state) && str_starts_with($state, 'test_');
        $testSessionId = $isTest ? str_replace('test_', '', $state) : null;
        $oauthProvider = OAuthProvider::where('provider', $provider)->first();

        try {
            // Paso 2: Callback recibido
            if ($isTest && $oauthProvider) {
                OAuthTestLog::create([
                    'oauth_provider_id' => $oauthProvider->id,
                    'test_session_id' => $testSessionId,
                    'step' => 'callback_received',
                    'step_data' => json_encode([
                        'provider' => $provider,
                        'timestamp' => now()->toDateTimeString(),
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $socialUser = Socialite::driver($provider)->user();

            // Paso 3: Datos del usuario obtenidos
            if ($isTest && $oauthProvider) {
                OAuthTestLog::create([
                    'oauth_provider_id' => $oauthProvider->id,
                    'test_session_id' => $testSessionId,
                    'step' => 'user_data_retrieved',
                    'step_data' => json_encode([
                        'provider_id' => $socialUser->getId(),
                        'email' => $socialUser->getEmail(),
                        'name' => $socialUser->getName(),
                        'has_avatar' => !empty($socialUser->getAvatar()),
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            // Verificar si el usuario ya tiene una cuenta social con este provider
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($socialAccount) {
                // Usuario ya existe con esta cuenta social, hacer login
                if ($isTest && $oauthProvider) {
                    OAuthTestLog::create([
                        'oauth_provider_id' => $oauthProvider->id,
                        'user_id' => $socialAccount->user->id,
                        'test_session_id' => $testSessionId,
                        'step' => 'user_logged_in',
                        'step_data' => json_encode([
                            'action' => 'existing_user_login',
                            'user_id' => $socialAccount->user->id,
                            'email' => $socialAccount->user->email,
                        ]),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
                
                Auth::login($socialAccount->user);
                
                if ($isTest) {
                    return $this->handleTestRedirect($request, $socialAccount->user, $testSessionId, $oauthProvider);
                }
                
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

                if ($isTest && $oauthProvider) {
                    OAuthTestLog::create([
                        'oauth_provider_id' => $oauthProvider->id,
                        'user_id' => $user->id,
                        'test_session_id' => $testSessionId,
                        'step' => 'user_logged_in',
                        'step_data' => json_encode([
                            'action' => 'existing_user_linked',
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'social_account_created' => true,
                        ]),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                Auth::login($user);
                
                if ($isTest) {
                    return $this->handleTestRedirect($request, $user, $testSessionId, $oauthProvider);
                }
                
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

            // Paso 4: Usuario creado (solo para nuevos usuarios)
            if ($isTest && $oauthProvider) {
                OAuthTestLog::create([
                    'oauth_provider_id' => $oauthProvider->id,
                    'user_id' => $user->id,
                    'test_session_id' => $testSessionId,
                    'step' => 'user_created',
                    'step_data' => json_encode([
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'role' => 'Cliente',
                        'cliente_profile_created' => true,
                        'social_account_created' => true,
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                OAuthTestLog::create([
                    'oauth_provider_id' => $oauthProvider->id,
                    'user_id' => $user->id,
                    'test_session_id' => $testSessionId,
                    'step' => 'user_logged_in',
                    'step_data' => json_encode([
                        'action' => 'new_user_registered',
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'session_created' => true,
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            Auth::login($user);

            if ($isTest) {
                return $this->handleTestRedirect($request, $user, $testSessionId, $oauthProvider);
            }

            return redirect()->route('cliente.dashboard')
                ->with('success', '¡Bienvenido! Te has registrado exitosamente con ' . ucfirst($provider) . '.');

        } catch (\Exception $e) {
            // Registrar error en logs de prueba
            if ($isTest && $oauthProvider) {
                OAuthTestLog::create([
                    'oauth_provider_id' => $oauthProvider->id,
                    'test_session_id' => $testSessionId ?? 'unknown',
                    'step' => 'error',
                    'error_message' => $e->getMessage(),
                    'step_data' => json_encode([
                        'error_type' => get_class($e),
                        'trace' => $e->getTraceAsString(),
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            \Log::error('Error en autenticación OAuth', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'test_session_id' => $testSessionId ?? null,
            ]);

            if ($isTest) {
                return redirect()->route('superadmin.oauth-providers.test-results', [
                    'session_id' => $testSessionId,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('login')
                ->with('error', 'Error al autenticar con ' . ucfirst($provider) . '. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Manejar redirección después de login en modo prueba
     */
    protected function handleTestRedirect(Request $request, User $user, string $testSessionId, OAuthProvider $oauthProvider): RedirectResponse
    {
        // Verificar sesión
        if (Auth::check()) {
            OAuthTestLog::create([
                'oauth_provider_id' => $oauthProvider->id,
                'user_id' => $user->id,
                'test_session_id' => $testSessionId,
                'step' => 'session_verified',
                'step_data' => json_encode([
                    'user_id' => $user->id,
                    'session_active' => true,
                    'roles' => $user->roles->pluck('name')->toArray(),
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirigir al dashboard (paso 5)
            $dashboardRoute = $user->hasRole('Paseador') ? 'paseador.dashboard' : 'cliente.dashboard';
            
            OAuthTestLog::create([
                'oauth_provider_id' => $oauthProvider->id,
                'user_id' => $user->id,
                'test_session_id' => $testSessionId,
                'step' => 'redirected_to_dashboard',
                'step_data' => json_encode([
                    'dashboard_route' => $dashboardRoute,
                    'user_id' => $user->id,
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Marcar prueba como completada
            OAuthTestLog::where('test_session_id', $testSessionId)
                ->where('step', '!=', 'completed')
                ->update(['completed_at' => now()]);

            OAuthTestLog::create([
                'oauth_provider_id' => $oauthProvider->id,
                'user_id' => $user->id,
                'test_session_id' => $testSessionId,
                'step' => 'completed',
                'step_data' => json_encode([
                    'all_steps_completed' => true,
                    'total_steps' => 8,
                ]),
                'completed_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirigir a resultados de prueba en lugar del dashboard
            return redirect()->route('superadmin.oauth-providers.test-results', [
                'session_id' => $testSessionId,
            ])->with('success', '¡Prueba de conexión OAuth completada exitosamente!');
        }

        return redirect()->route('superadmin.oauth-providers.test-results', [
            'session_id' => $testSessionId,
            'error' => 'No se pudo verificar la sesión',
        ]);
    }

    /**
     * Validate that the provider is allowed and active
     */
    protected function validateProvider(string $provider): void
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)->first();

        if (!$oauthProvider) {
            abort(404, 'Proveedor OAuth no encontrado. Por favor, configúralo primero en el panel de administración.');
        }

        if (!$oauthProvider->is_active) {
            abort(403, 'Este proveedor OAuth está desactivado.');
        }

        if (!$oauthProvider->isConfigured()) {
            abort(403, 'Este proveedor OAuth no está completamente configurado. Faltan credenciales.');
        }
    }

    /**
     * Configure Socialite driver with credentials from database
     */
    protected function configureProvider(string $provider): void
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('is_active', true)
            ->first();

        if ($oauthProvider && $oauthProvider->isConfigured()) {
            // Configurar Socialite dinámicamente
            config([
                "services.{$provider}.client_id" => $oauthProvider->client_id,
                "services.{$provider}.client_secret" => $oauthProvider->client_secret,
                "services.{$provider}.redirect" => $oauthProvider->redirect_uri,
            ]);
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
