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
}
