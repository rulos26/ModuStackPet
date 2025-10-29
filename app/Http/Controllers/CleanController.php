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

            // Preparar variables de entorno para Composer
            // Composer requiere HOME o COMPOSER_HOME para funcionar correctamente
            $env = $_ENV;

            // Establecer HOME si no está definida (usando el directorio home del usuario o storage)
            if (empty($env['HOME']) && empty($env['COMPOSER_HOME'])) {
                // Intentar obtener el home del usuario del sistema
                $home = \getenv('HOME') ?: \getenv('USERPROFILE'); // USERPROFILE para Windows

                if (empty($home)) {
                    // Si no hay HOME del sistema, usar storage como alternativa
                    $home = storage_path();
                    $env['COMPOSER_HOME'] = storage_path('composer');
                } else {
                    $env['HOME'] = $home;
                }
            } elseif (!empty($env['HOME'])) {
                // Si HOME está definida, usarla
                $env['HOME'] = $env['HOME'];
            } elseif (!empty($env['COMPOSER_HOME'])) {
                // Si COMPOSER_HOME está definida, usarla
                $env['COMPOSER_HOME'] = $env['COMPOSER_HOME'];
            }

            // Asegurar que COMPOSER_HOME esté definida incluso si HOME existe
            if (empty($env['COMPOSER_HOME']) && !empty($env['HOME'])) {
                $env['COMPOSER_HOME'] = $env['HOME'] . '/.composer';
            }

            // Usar Process de Symfony en lugar de exec()
            // Esto es más seguro y no requiere que exec() esté habilitada
            $process = new Process(
                ['composer', $comando],
                base_path(),
                $env,
                null,
                120 // Timeout de 120 segundos (composer puede tardar más)
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
            $errorMessage = 'Error: ' . $e->getMessage();

            // Mensaje específico para errores de entorno de Composer
            if (strpos($e->getMessage(), 'HOME') !== false || strpos($e->getMessage(), 'COMPOSER_HOME') !== false) {
                $errorMessage .= "\n\nSolución: Configure la variable de entorno HOME o COMPOSER_HOME en el servidor.";
            }

            return [
                'comando' => 'composer ' . $comando,
                'descripcion' => $descripcion ?: 'composer ' . $comando,
                'opciones' => [],
                'exit_code' => 1,
                'output' => $errorMessage .
                    (strpos($e->getMessage(), 'Process') !== false
                        ? "\n\nNota: Asegúrate de que 'composer' esté disponible en el PATH del servidor."
                        : ''),
                'success' => false,
                'tipo' => 'composer'
            ];
        }
    }
}

