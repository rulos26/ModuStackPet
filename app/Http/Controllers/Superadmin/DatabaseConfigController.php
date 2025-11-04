<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\DatabaseConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DatabaseConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $configs = DatabaseConfig::orderBy('created_at', 'desc')->get();
        return view('superadmin.database-configs.index', compact('configs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $config = new DatabaseConfig();
        return view('superadmin.database-configs.create', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'connection' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Si se marca como activo, desactivar los demás
        if ($request->has('is_active') && $request->is_active) {
            DatabaseConfig::where('id', '!=', 0)->update(['is_active' => false]);
        }

        $config = DatabaseConfig::create($validated);

        Log::info('Configuración de Base de Datos creada', [
            'id' => $config->id,
            'host' => $config->host,
            'database' => $config->database,
        ]);

        return redirect()
            ->route('superadmin.database-configs.index')
            ->with('success', 'Configuración de base de datos creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatabaseConfig $databaseConfig): View
    {
        return view('superadmin.database-configs.edit', compact('databaseConfig'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatabaseConfig $databaseConfig): RedirectResponse
    {
        $validated = $request->validate([
            'connection' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255', // Opcional para no cambiar si está vacío
            'is_active' => 'boolean',
        ]);

        // Si no se proporciona password, mantener el actual
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Si se marca como activo, desactivar los demás
        if ($request->has('is_active') && $request->is_active) {
            DatabaseConfig::where('id', '!=', $databaseConfig->id)->update(['is_active' => false]);
        }

        $databaseConfig->update($validated);

        Log::info('Configuración de Base de Datos actualizada', [
            'id' => $databaseConfig->id,
            'host' => $databaseConfig->host,
        ]);

        return redirect()
            ->route('superadmin.database-configs.index')
            ->with('success', 'Configuración de base de datos actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatabaseConfig $databaseConfig): RedirectResponse
    {
        if ($databaseConfig->is_active) {
            return redirect()
                ->route('superadmin.database-configs.index')
                ->with('error', 'No se puede eliminar la configuración activa. Desactívala primero.');
        }

        $databaseConfig->delete();

        Log::info('Configuración de Base de Datos eliminada', [
            'id' => $databaseConfig->id,
        ]);

        return redirect()
            ->route('superadmin.database-configs.index')
            ->with('success', 'Configuración eliminada exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, DatabaseConfig $databaseConfig): RedirectResponse
    {
        // Si se activa, desactivar los demás
        if (!$databaseConfig->is_active) {
            DatabaseConfig::where('id', '!=', $databaseConfig->id)->update(['is_active' => false]);
        }

        $databaseConfig->update(['is_active' => !$databaseConfig->is_active]);

        return redirect()
            ->route('superadmin.database-configs.index')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Test database connection
     */
    public function test(Request $request, DatabaseConfig $databaseConfig)
    {
        try {
            $config = $databaseConfig->toConfigArray();

            // Crear conexión temporal
            $tempConnection = 'test_' . time();
            config(["database.connections.{$tempConnection}" => $config]);

            // Intentar conectar
            DB::connection($tempConnection)->getPdo();

            $result = [
                'success' => true,
                'message' => 'Conexión exitosa a la base de datos',
                'details' => [
                    'host' => $config['host'],
                    'database' => $config['database'],
                    'username' => $config['username'],
                    'port' => $config['port'],
                ],
            ];

            // Guardar resultado
            $databaseConfig->update([
                'last_test_result' => json_encode($result),
                'last_tested_at' => now(),
            ]);

            // Limpiar conexión temporal
            DB::purge($tempConnection);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($result);
            }

            return redirect()
                ->route('superadmin.database-configs.index')
                ->with('test_result', $result);

        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'message' => 'Error al conectar: ' . $e->getMessage(),
                'details' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
            ];

            // Guardar resultado
            $databaseConfig->update([
                'last_test_result' => json_encode($result),
                'last_tested_at' => now(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($result, 500);
            }

            return redirect()
                ->route('superadmin.database-configs.index')
                ->with('test_result', $result);
        }
    }
}
