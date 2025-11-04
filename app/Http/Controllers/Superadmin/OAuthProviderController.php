<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OAuthProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $providers = OAuthProvider::orderBy('name')->get();
        return view('superadmin.oauth-providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $provider = new OAuthProvider();
        return view('superadmin.oauth-providers.create', compact('provider'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => 'required|string|max:255|unique:oauth_providers,provider',
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|string|max:500',
            'client_secret' => 'nullable|string|max:500',
            'redirect_uri' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $provider = OAuthProvider::create($validated);

        Log::info('OAuth Provider creado', [
            'provider' => $provider->provider,
            'name' => $provider->name,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('superadmin.oauth-providers.index')
            ->with('success', "Proveedor OAuth '{$provider->name}' creado exitosamente.");
    }

    /**
     * Display the specified resource.
     */
    public function show(OAuthProvider $oauthProvider): View
    {
        return view('superadmin.oauth-providers.show', compact('oauthProvider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OAuthProvider $oauthProvider): View
    {
        return view('superadmin.oauth-providers.edit', compact('oauthProvider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OAuthProvider $oauthProvider): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|string|max:500',
            'client_secret' => 'nullable|string|max:500',
            'redirect_uri' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        // Si client_secret está vacío, mantener el valor actual
        if (empty($validated['client_secret'])) {
            unset($validated['client_secret']);
        }

        $oauthProvider->update($validated);

        Log::info('OAuth Provider actualizado', [
            'provider' => $oauthProvider->provider,
            'name' => $oauthProvider->name,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('superadmin.oauth-providers.index')
            ->with('success', "Proveedor OAuth '{$oauthProvider->name}' actualizado exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OAuthProvider $oauthProvider): RedirectResponse
    {
        $name = $oauthProvider->name;
        $provider = $oauthProvider->provider;
        
        $oauthProvider->delete();

        Log::info('OAuth Provider eliminado', [
            'provider' => $provider,
            'name' => $name,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('superadmin.oauth-providers.index')
            ->with('success', "Proveedor OAuth '{$name}' eliminado exitosamente.");
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, OAuthProvider $oauthProvider): RedirectResponse
    {
        $oauthProvider->is_active = !$oauthProvider->is_active;
        $oauthProvider->save();

        $status = $oauthProvider->is_active ? 'activado' : 'desactivado';

        return redirect()
            ->route('superadmin.oauth-providers.index')
            ->with('success', "Proveedor OAuth '{$oauthProvider->name}' {$status} exitosamente.");
    }

    /**
     * Test OAuth provider configuration
     */
    public function test(Request $request, OAuthProvider $oauthProvider)
    {
        $results = [
            'provider' => $oauthProvider->name,
            'tests' => [],
            'all_passed' => true,
        ];

        // Test 1: Verificar si está activo
        $results['tests'][] = [
            'name' => 'Provider Activo',
            'status' => $oauthProvider->is_active,
            'message' => $oauthProvider->is_active 
                ? 'El provider está activo' 
                : 'El provider está desactivado',
        ];
        if (!$oauthProvider->is_active) {
            $results['all_passed'] = false;
        }

        // Test 2: Verificar Client ID
        $results['tests'][] = [
            'name' => 'Client ID Configurado',
            'status' => !empty($oauthProvider->client_id),
            'message' => !empty($oauthProvider->client_id)
                ? 'Client ID está configurado correctamente'
                : 'Client ID no está configurado',
        ];
        if (empty($oauthProvider->client_id)) {
            $results['all_passed'] = false;
        }

        // Test 3: Verificar Client Secret
        $results['tests'][] = [
            'name' => 'Client Secret Configurado',
            'status' => !empty($oauthProvider->client_secret),
            'message' => !empty($oauthProvider->client_secret)
                ? 'Client Secret está configurado correctamente'
                : 'Client Secret no está configurado',
        ];
        if (empty($oauthProvider->client_secret)) {
            $results['all_passed'] = false;
        }

        // Test 4: Verificar Redirect URI
        $results['tests'][] = [
            'name' => 'Redirect URI Configurado',
            'status' => !empty($oauthProvider->redirect_uri),
            'message' => !empty($oauthProvider->redirect_uri)
                ? 'Redirect URI está configurado: ' . $oauthProvider->redirect_uri
                : 'Redirect URI no está configurado',
        ];
        if (empty($oauthProvider->redirect_uri)) {
            $results['all_passed'] = false;
        }

        // Test 5: Validar formato de Redirect URI
        $redirectUriValid = filter_var($oauthProvider->redirect_uri, FILTER_VALIDATE_URL) !== false;
        $results['tests'][] = [
            'name' => 'Formato de Redirect URI Válido',
            'status' => $redirectUriValid,
            'message' => $redirectUriValid
                ? 'El formato de Redirect URI es válido'
                : 'El formato de Redirect URI no es válido',
        ];
        if (!$redirectUriValid) {
            $results['all_passed'] = false;
        }

        // Test 6: Verificar que el provider está completamente configurado
        $isConfigured = $oauthProvider->isConfigured();
        $results['tests'][] = [
            'name' => 'Configuración Completa',
            'status' => $isConfigured,
            'message' => $isConfigured
                ? 'El provider está completamente configurado y listo para usar'
                : 'Faltan credenciales para completar la configuración',
        ];
        if (!$isConfigured) {
            $results['all_passed'] = false;
        }

        // Test 7: Intentar configurar Socialite (sin autenticar)
        try {
            if ($isConfigured && $oauthProvider->is_active) {
                config([
                    "services.{$oauthProvider->provider}.client_id" => $oauthProvider->client_id,
                    "services.{$oauthProvider->provider}.client_secret" => $oauthProvider->client_secret,
                    "services.{$oauthProvider->provider}.redirect" => $oauthProvider->redirect_uri,
                ]);
                
                $results['tests'][] = [
                    'name' => 'Configuración de Socialite',
                    'status' => true,
                    'message' => 'Configuración de Socialite aplicada correctamente',
                ];
            } else {
                $results['tests'][] = [
                    'name' => 'Configuración de Socialite',
                    'status' => false,
                    'message' => 'No se puede probar Socialite sin configuración completa',
                ];
                $results['all_passed'] = false;
            }
        } catch (\Exception $e) {
            $results['tests'][] = [
                'name' => 'Configuración de Socialite',
                'status' => false,
                'message' => 'Error al configurar Socialite: ' . $e->getMessage(),
            ];
            $results['all_passed'] = false;
        }

        // Siempre devolver JSON para peticiones AJAX
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json($results);
        }

        // Si no es AJAX, redirigir con resultados en sesión
        return redirect()
            ->route('superadmin.oauth-providers.index')
            ->with('test_results', $results);
    }

    /**
     * Simular conexión OAuth completa (generar URL de autorización)
     */
    public function simulateConnection(Request $request, OAuthProvider $oauthProvider)
    {
        try {
            // Verificar que el provider esté configurado
            if (!$oauthProvider->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El proveedor no está completamente configurado. Faltan credenciales.',
                    'authorization_url' => null,
                ], 400);
            }

            if (!$oauthProvider->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'El proveedor está desactivado. Actívalo primero.',
                    'authorization_url' => null,
                ], 400);
            }

            // Configurar Socialite con las credenciales del provider
            config([
                "services.{$oauthProvider->provider}.client_id" => $oauthProvider->client_id,
                "services.{$oauthProvider->provider}.client_secret" => $oauthProvider->client_secret,
                "services.{$oauthProvider->provider}.redirect" => $oauthProvider->redirect_uri,
            ]);

            // Generar la URL de autorización
            $driver = \Laravel\Socialite\Facades\Socialite::driver($oauthProvider->provider);
            
            // Configurar scopes según el provider
            $scopes = ['email', 'profile'];
            if ($oauthProvider->provider === 'google') {
                $scopes = ['openid', 'profile', 'email'];
            } elseif ($oauthProvider->provider === 'facebook') {
                $scopes = ['email', 'public_profile'];
            }
            
            $driver->scopes($scopes);
            
            // Generar URL de autorización (sin redirigir)
            $authorizationUrl = $driver->getTargetUrl();
            
            // Verificar que la URL es válida
            if (empty($authorizationUrl) || !filter_var($authorizationUrl, FILTER_VALIDATE_URL)) {
                throw new \Exception('No se pudo generar la URL de autorización válida');
            }

            // Generar ID único para esta prueba
            $testSessionId = \Illuminate\Support\Str::uuid()->toString();
            
            // Registrar inicio de prueba en BD
            try {
                \App\Models\OAuthTestLog::create([
                    'oauth_provider_id' => $oauthProvider->id,
                    'test_session_id' => $testSessionId,
                    'step' => 'redirect_generated',
                    'step_data' => json_encode([
                        'provider' => $oauthProvider->provider,
                        'redirect_uri' => $oauthProvider->redirect_uri,
                        'timestamp' => now()->toDateTimeString(),
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                // Si la tabla no existe, continuar sin registrar
                \Log::warning('No se pudo registrar log de prueba OAuth', ['error' => $e->getMessage()]);
            }
            
            // Agregar parámetro state para identificar la prueba (mejor compatibilidad con OAuth)
            $authorizationUrlWithTest = $authorizationUrl . (parse_url($authorizationUrl, PHP_URL_QUERY) ? '&' : '?') . 'state=test_' . $testSessionId;
            
            return response()->json([
                'success' => true,
                'message' => 'URL de autorización generada correctamente',
                'authorization_url' => $authorizationUrlWithTest,
                'test_session_id' => $testSessionId,
                'provider' => $oauthProvider->name,
                'redirect_uri' => $oauthProvider->redirect_uri,
                'instructions' => [
                    '1. Haz clic en "Abrir en Nueva Pestaña" para iniciar la prueba',
                    '2. Inicia sesión con tu cuenta de ' . $oauthProvider->name,
                    '3. Autoriza el acceso a la aplicación',
                    '4. Serás redirigido automáticamente al reporte de resultados',
                    '5. El sistema registrará cada paso del flujo OAuth',
                    '6. Verás un reporte completo con todos los pasos verificados',
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al simular conexión OAuth', [
                'provider' => $oauthProvider->provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al generar URL de autorización: ' . $e->getMessage(),
                'authorization_url' => null,
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar simulador visual del flujo OAuth
     */
    public function visualSimulator(OAuthProvider $oauthProvider): View
    {
        return view('superadmin.oauth-providers.visual-simulator', compact('oauthProvider'));
    }

    /**
     * Mostrar resultados de prueba de conexión OAuth
     */
    public function testResults(Request $request, string $sessionId): View
    {
        $logs = \App\Models\OAuthTestLog::where('test_session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        if ($logs->isEmpty()) {
            return redirect()
                ->route('superadmin.oauth-providers.index')
                ->with('error', 'No se encontraron resultados de prueba para esta sesión.');
        }

        $provider = $logs->first()->oauthProvider;
        $user = $logs->whereNotNull('user_id')->first()?->user;
        
        // Determinar si la prueba fue exitosa
        $completed = $logs->where('step', 'completed')->isNotEmpty();
        $hasError = $logs->where('step', 'error')->isNotEmpty();
        
        // Contar pasos
        $totalSteps = $logs->where('step', '!=', 'completed')->count();
        $completedSteps = $logs->where('step', '!=', 'error')->where('step', '!=', 'completed')->count();

        return view('superadmin.oauth-providers.test-results', compact(
            'logs',
            'provider',
            'user',
            'sessionId',
            'completed',
            'hasError',
            'totalSteps',
            'completedSteps'
        ));
    }
}
