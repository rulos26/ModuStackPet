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
    public function test(OAuthProvider $oauthProvider)
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($results);
        }

        return redirect()
            ->route('superadmin.oauth-providers.index')
            ->with('test_results', $results);
    }
}
