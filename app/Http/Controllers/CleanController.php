<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Exception;

class CleanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vista para gestionar comandos de limpieza
     */
    public function index()
    {
        // Solo superadmin puede acceder
        $user = Auth::user();
        if (!$user || !$user->hasRole('Superadmin')) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return view('clean.index');
    }

    /**
     * Ejecutar un comando de limpieza específico
     */
    public function execute(Request $request)
    {
        // Solo superadmin puede ejecutar
        $user = Auth::user();
        if (!$user || !$user->hasRole('Superadmin')) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'No tienes permisos para ejecutar comandos de limpieza.');
        }

        $request->validate([
            'tipo' => 'required|in:cache,config,route,view,compiled,autoload,all'
        ]);

        $tipo = $request->get('tipo');
        $resultados = [];

        try {
            switch ($tipo) {
                case 'cache':
                    $resultados[] = $this->ejecutarComando('cache:clear', [], 'Limpiar Cache');
                    break;
                case 'config':
                    $resultados[] = $this->ejecutarComando('config:clear', [], 'Limpiar Configuración');
                    break;
                case 'route':
                    $resultados[] = $this->ejecutarComando('route:clear', [], 'Limpiar Rutas');
                    break;
                case 'view':
                    $resultados[] = $this->ejecutarComando('view:clear', [], 'Limpiar Vistas');
                    break;
                case 'compiled':
                    $resultados[] = $this->ejecutarComando('clear-compiled', [], 'Limpiar Archivos Compilados');
                    break;
                case 'autoload':
                    $resultados[] = $this->ejecutarComandoComposer('dump-autoload', 'Actualizar Autoload de Composer');
                    break;
                case 'all':
                    // Ejecutar todos los comandos de limpieza
                    $resultados[] = $this->ejecutarComando('cache:clear', [], 'Limpiar Cache');
                    $resultados[] = $this->ejecutarComando('config:clear', [], 'Limpiar Configuración');
                    $resultados[] = $this->ejecutarComando('route:clear', [], 'Limpiar Rutas');
                    $resultados[] = $this->ejecutarComando('view:clear', [], 'Limpiar Vistas');
                    $resultados[] = $this->ejecutarComando('clear-compiled', [], 'Limpiar Archivos Compilados');
                    $resultados[] = $this->ejecutarComandoComposer('dump-autoload', 'Actualizar Autoload de Composer');
                    break;
            }

            $todosExitosos = collect($resultados)->every(function($resultado) {
                return $resultado['success'];
            });

            Log::info('Comandos de limpieza ejecutados desde interfaz web', [
                'user_id' => Auth::id(),
                'tipo' => $tipo,
                'todos_exitosos' => $todosExitosos,
                'cantidad_comandos' => count($resultados)
            ]);

            $mensaje = $todosExitosos
                ? 'Limpieza ejecutada correctamente.'
                : 'Algunos comandos tuvieron errores. Revisa los resultados.';

            return redirect()->route('superadmin.clean.index')
                ->with('resultados', $resultados)
                ->with($todosExitosos ? 'success' : 'warning', $mensaje);

        } catch (Exception $e) {
            Log::error('Error al ejecutar comandos de limpieza desde interfaz', [
                'user_id' => Auth::id(),
                'tipo' => $tipo,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('superadmin.clean.index')
                ->with('error', 'Error al ejecutar la limpieza: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar comando Artisan
     */
    private function ejecutarComando($comando, $opciones = [], $descripcion = '')
    {
        try {
            $output = new \Symfony\Component\Console\Output\BufferedOutput;
            $exitCode = Artisan::call($comando, $opciones, $output);

            return [
                'comando' => $comando,
                'descripcion' => $descripcion ?: $comando,
                'opciones' => $opciones,
                'exit_code' => $exitCode,
                'output' => $output->fetch(),
                'success' => $exitCode === 0,
                'tipo' => 'artisan'
            ];
        } catch (Exception $e) {
            return [
                'comando' => $comando,
                'descripcion' => $descripcion ?: $comando,
                'opciones' => $opciones,
                'exit_code' => 1,
                'output' => 'Error: ' . $e->getMessage(),
                'success' => false,
                'tipo' => 'artisan'
            ];
        }
    }

    /**
     * Ejecutar comando de Composer
     */
    private function ejecutarComandoComposer($comando, $descripcion = '')
    {
        try {
            $comandoCompleto = 'composer ' . $comando;

            // Usar Process de Symfony en lugar de exec()
            // Esto es más seguro y no requiere que exec() esté habilitada
            $process = new Process(
                ['composer', $comando],
                base_path(),
                null,
                null,
                60 // Timeout de 60 segundos
            );

            $process->run();

            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();
            $exitCode = $process->getExitCode();

            // Combinar salida estándar y errores
            $outputString = trim($output);
            if (!empty($errorOutput)) {
                $outputString .= (!empty($outputString) ? "\n" : '') . $errorOutput;
            }

            // Si no hay salida, mostrar mensaje informativo
            if (empty($outputString)) {
                $outputString = $exitCode === 0
                    ? 'Comando ejecutado correctamente (sin salida visible)'
                    : 'Error al ejecutar el comando (código de salida: ' . $exitCode . ')';
            }

            return [
                'comando' => $comandoCompleto,
                'descripcion' => $descripcion ?: $comandoCompleto,
                'opciones' => [],
                'exit_code' => $exitCode ?? 1,
                'output' => $outputString,
                'success' => ($exitCode ?? 1) === 0,
                'tipo' => 'composer'
            ];
        } catch (Exception $e) {
            return [
                'comando' => 'composer ' . $comando,
                'descripcion' => $descripcion ?: 'composer ' . $comando,
                'opciones' => [],
                'exit_code' => 1,
                'output' => 'Error: ' . $e->getMessage() .
                    (strpos($e->getMessage(), 'Process') !== false
                        ? "\n\nNota: Asegúrate de que 'composer' esté disponible en el PATH del servidor."
                        : ''),
                'success' => false,
                'tipo' => 'composer'
            ];
        }
    }
}

