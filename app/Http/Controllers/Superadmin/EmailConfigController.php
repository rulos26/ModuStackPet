<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\EmailConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $configs = EmailConfig::orderBy('created_at', 'desc')->get();
        return view('superadmin.email-configs.index', compact('configs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $config = new EmailConfig();
        return view('superadmin.email-configs.create', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mailer' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'encryption' => 'required|string|in:tls,ssl',
            'from_address' => 'required|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Si se marca como activo, desactivar los demás
        if ($request->has('is_active') && $request->is_active) {
            EmailConfig::where('id', '!=', 0)->update(['is_active' => false]);
        }

        $config = EmailConfig::create($validated);

        Log::info('Configuración de Email creada', [
            'id' => $config->id,
            'host' => $config->host,
            'from_address' => $config->from_address,
        ]);

        return redirect()
            ->route('superadmin.email-configs.index')
            ->with('success', 'Configuración de correo creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailConfig $emailConfig): View
    {
        return view('superadmin.email-configs.edit', compact('emailConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailConfig $emailConfig): RedirectResponse
    {
        $validated = $request->validate([
            'mailer' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255', // Opcional para no cambiar si está vacío
            'encryption' => 'required|string|in:tls,ssl',
            'from_address' => 'required|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Si no se proporciona password, mantener el actual
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Si se marca como activo, desactivar los demás
        if ($request->has('is_active') && $request->is_active) {
            EmailConfig::where('id', '!=', $emailConfig->id)->update(['is_active' => false]);
        }

        $emailConfig->update($validated);

        Log::info('Configuración de Email actualizada', [
            'id' => $emailConfig->id,
            'host' => $emailConfig->host,
        ]);

        return redirect()
            ->route('superadmin.email-configs.index')
            ->with('success', 'Configuración de correo actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailConfig $emailConfig): RedirectResponse
    {
        if ($emailConfig->is_active) {
            return redirect()
                ->route('superadmin.email-configs.index')
                ->with('error', 'No se puede eliminar la configuración activa. Desactívala primero.');
        }

        $emailConfig->delete();

        Log::info('Configuración de Email eliminada', [
            'id' => $emailConfig->id,
        ]);

        return redirect()
            ->route('superadmin.email-configs.index')
            ->with('success', 'Configuración eliminada exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, EmailConfig $emailConfig): RedirectResponse
    {
        // Si se activa, desactivar los demás
        if (!$emailConfig->is_active) {
            EmailConfig::where('id', '!=', $emailConfig->id)->update(['is_active' => false]);
        }

        $emailConfig->update(['is_active' => !$emailConfig->is_active]);

        return redirect()
            ->route('superadmin.email-configs.index')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Test email configuration
     */
    public function test(Request $request, EmailConfig $emailConfig)
    {
        try {
            $config = $emailConfig->toConfigArray();

            // Configurar temporalmente
            foreach ($config as $key => $value) {
                config([$key => $value]);
            }

            // Obtener email de destino (del request o usar el from_address)
            $testEmail = $request->get('test_email', $emailConfig->from_address);

            // Enviar email de prueba
            Mail::raw('Este es un correo de prueba de configuración de ModuStackPet. Si recibes este mensaje, la configuración de correo está funcionando correctamente.', function ($message) use ($testEmail, $emailConfig) {
                $message->to($testEmail)
                        ->subject('Prueba de Configuración de Correo - ModuStackPet');
            });

            $result = [
                'success' => true,
                'message' => 'Correo de prueba enviado exitosamente',
                'details' => [
                    'to' => $testEmail,
                    'from' => $emailConfig->from_address,
                    'host' => $emailConfig->host,
                    'port' => $emailConfig->port,
                ],
            ];

            // Guardar resultado
            $emailConfig->update([
                'last_test_result' => json_encode($result),
                'last_tested_at' => now(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($result);
            }

            return redirect()
                ->route('superadmin.email-configs.index')
                ->with('test_result', $result);

        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'message' => 'Error al enviar correo: ' . $e->getMessage(),
                'details' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
            ];

            // Guardar resultado
            $emailConfig->update([
                'last_test_result' => json_encode($result),
                'last_tested_at' => now(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($result, 500);
            }

            return redirect()
                ->route('superadmin.email-configs.index')
                ->with('test_result', $result);
        }
    }
}
