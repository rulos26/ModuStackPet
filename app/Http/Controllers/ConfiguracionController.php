<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra la lista de configuraciones del sistema
     */
    public function index()
    {
        $configuraciones = Configuracion::orderBy('categoria')
            ->orderBy('clave')
            ->get()
            ->groupBy('categoria');

        return view('configuracion.index', compact('configuraciones'));
    }

    /**
     * Muestra el formulario para editar una configuración
     */
    public function edit($id)
    {
        $configuracion = Configuracion::findOrFail($id);

        return view('configuracion.edit', compact('configuracion'));
    }

    /**
     * Actualiza una configuración del sistema
     */
    public function update(Request $request, $id)
    {
        $configuracion = Configuracion::findOrFail($id);

        $request->validate([
            'valor' => ['required'],
        ], [
            'valor.required' => 'El valor de la configuración es obligatorio.',
        ]);

        try {
            $valorAnterior = $configuracion->valor;
            $configuracion->actualizarValor($request->valor);

            // Limpiar cache global de configuraciones
            Cache::flush();

            Log::info("Configuración actualizada: {$configuracion->clave} = {$request->valor} (anterior: {$valorAnterior})");

            return redirect()->route('superadmin.configuraciones.index')
                ->with('success', "Configuración '{$configuracion->descripcion}' actualizada correctamente.");
        } catch (\Exception $e) {
            Log::error('Error al actualizar configuración: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al actualizar la configuración. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Actualiza el timeout de sesión específicamente
     */
    public function updateSessionTimeout(Request $request)
    {
        $request->validate([
            'session_timeout' => ['required', 'integer', 'min:60', 'max:14400'], // Entre 1 minuto y 4 horas
        ], [
            'session_timeout.required' => 'El tiempo de sesión es obligatorio.',
            'session_timeout.integer' => 'El tiempo de sesión debe ser un número.',
            'session_timeout.min' => 'El tiempo mínimo es 60 segundos (1 minuto).',
            'session_timeout.max' => 'El tiempo máximo es 14400 segundos (4 horas).',
        ]);

        try {
            $configuracion = Configuracion::where('clave', 'session_timeout')->firstOrFail();
            $valorAnterior = $configuracion->valor;

            $configuracion->actualizarValor($request->session_timeout);
            Cache::flush();

            $minutos = round($request->session_timeout / 60);
            $minutosAnterior = round($valorAnterior / 60);

            Log::info("Timeout de sesión actualizado: {$request->session_timeout}s ({$minutos} min) - Anterior: {$valorAnterior}s ({$minutosAnterior} min)");

            return redirect()->route('superadmin.configuraciones.index')
                ->with('success', "Tiempo de sesión actualizado a {$minutos} minutos ({$request->session_timeout} segundos).");
        } catch (\Exception $e) {
            Log::error('Error al actualizar timeout de sesión: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al actualizar el tiempo de sesión. Por favor, intente nuevamente.')
                ->withInput();
        }
    }
}
