<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class MigrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vista para gestionar migraciones
     */
    public function index()
    {
        // Solo superadmin puede acceder
        $user = Auth::user();
        if (!$user || !$user->hasRole('Superadmin')) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $migrationStatus = $this->getMigrationStatus();

        return view('migration.index', compact('migrationStatus'));
    }

    /**
     * Ejecutar una migración específica
     */
    public function execute(Request $request)
    {
        // Solo superadmin puede ejecutar
        $user = Auth::user();
        if (!$user || !$user->hasRole('Superadmin')) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'No tienes permisos para ejecutar migraciones.');
        }

        $request->validate([
            'tipo' => 'required|in:status,run,rollback,refresh,configuraciones'
        ]);

        $tipo = $request->get('tipo');
        $resultado = null;

        try {
            switch ($tipo) {
                case 'status':
                    $resultado = $this->ejecutarComando('migrate:status');
                    break;
                case 'run':
                    $resultado = $this->ejecutarComando('migrate');
                    break;
                case 'rollback':
                    $resultado = $this->ejecutarComando('migrate:rollback');
                    break;
                case 'refresh':
                    $resultado = $this->ejecutarComando('migrate:refresh');
                    break;
                case 'configuraciones':
                    $resultado = $this->ejecutarComando('migrate', [
                        '--path' => 'database/migrations/2025_10_29_091432_create_configuraciones_table.php'
                    ]);
                    break;
            }

            Log::info('Migración ejecutada desde interfaz web', [
                'user_id' => Auth::id(),
                'tipo' => $tipo,
                'resultado' => $resultado['success']
            ]);

            return redirect()->route('superadmin.migrations.index')
                ->with('resultado', $resultado)
                ->with('success', 'Migración ejecutada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al ejecutar migración desde interfaz', [
                'user_id' => Auth::id(),
                'tipo' => $tipo,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('superadmin.migrations.index')
                ->with('error', 'Error al ejecutar la migración: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar comando Artisan
     */
    private function ejecutarComando($comando, $opciones = [])
    {
        try {
            $output = new \Symfony\Component\Console\Output\BufferedOutput;
            $exitCode = Artisan::call($comando, $opciones, $output);

            return [
                'comando' => $comando,
                'opciones' => $opciones,
                'exit_code' => $exitCode,
                'output' => $output->fetch(),
                'success' => $exitCode === 0
            ];
        } catch (Exception $e) {
            return [
                'comando' => $comando,
                'opciones' => $opciones,
                'exit_code' => 1,
                'output' => 'Error: ' . $e->getMessage(),
                'success' => false
            ];
        }
    }

    /**
     * Obtener estado de las migraciones
     */
    private function getMigrationStatus()
    {
        try {
            $output = new \Symfony\Component\Console\Output\BufferedOutput;
            Artisan::call('migrate:status', [], $output);

            return [
                'output' => $output->fetch(),
                'success' => true
            ];
        } catch (Exception $e) {
            return [
                'output' => 'Error: ' . $e->getMessage(),
                'success' => false
            ];
        }
    }
}

