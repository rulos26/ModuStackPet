<?php

namespace App\Http\Controllers;

use App\Models\VacunaCertificacion;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VacunaCertificacionController extends Controller
{
    /**
     * Muestra la lista de vacunas y certificaciones con paginación
     * Incluye la relación con mascotas para optimizar consultas
     */
    public function index()
    {
        $vacunas = VacunaCertificacion::with('mascota')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('vacunas-certificaciones.index', compact('vacunas'));
    }

    /**
     * Muestra el formulario de creación
     * Obtiene la lista de mascotas para el select
     */
    public function create()
    {
        $mascotas = Mascota::orderBy('nombre')->get();
        return view('vacunas-certificaciones.create', compact('mascotas'));
    }

    /**
     * Almacena un nuevo registro de vacunas y certificaciones
     * Valida los datos y maneja la subida de archivos
     */
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'fecha_ultima_vacuna' => 'nullable|date',
            'operaciones' => 'nullable|string|max:1000',
            'certificado_veterinario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cedula_propietario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            // Obtener mascota y usuario para la ruta de archivos
            $mascota = Mascota::with('user')->findOrFail($request->mascota_id);
            $user = $mascota->user;
            $rutaBase = 'public/avatars/' . $user->cedula . '/documento_mascotas';

            // Preparar datos para crear el registro
            $data = $request->except(['certificado_veterinario', 'cedula_propietario']);

            // Manejar subida de certificado veterinario
            if ($request->hasFile('certificado_veterinario')) {
                $archivo = $request->file('certificado_veterinario');
                $nombreArchivo = strtolower(str_replace(' ', '_', $mascota->nombre)) . '_vacunas.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs($rutaBase, $nombreArchivo);
                $data['certificado_veterinario'] = str_replace('public/', '', $ruta);
            }

            // Manejar subida de cédula del propietario
            if ($request->hasFile('cedula_propietario')) {
                $archivo = $request->file('cedula_propietario');
                $nombreArchivo = strtolower(str_replace(' ', '_', $mascota->nombre)) . '_' . $user->cedula . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs($rutaBase, $nombreArchivo);
                $data['cedula_propietario'] = str_replace('public/', '', $ruta);
            }

            // Crear el registro
            VacunaCertificacion::create($data);

            return redirect()->route('vacunas-certificaciones.index')
                ->with('success', 'Registro de vacunas y certificaciones creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear vacunas y certificaciones: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al crear el registro. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de un registro específico
     */
    public function show(VacunaCertificacion $vacunaCertificacion)
    {
        return view('vacunas-certificaciones.show', compact('vacunaCertificacion'));
    }

    /**
     * Muestra el formulario de edición
     * Obtiene la lista de mascotas para el select
     */
    public function edit(VacunaCertificacion $vacunaCertificacion)
    {
        $mascotas = Mascota::orderBy('nombre')->get();
        return view('vacunas-certificaciones.edit', compact('vacunaCertificacion', 'mascotas'));
    }

    /**
     * Actualiza un registro existente
     * Valida los datos y maneja la actualización de archivos
     */
    public function update(Request $request, VacunaCertificacion $vacunaCertificacion)
    {
        // Validación de datos
        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'fecha_ultima_vacuna' => 'nullable|date',
            'operaciones' => 'nullable|string|max:1000',
            'certificado_veterinario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cedula_propietario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            // Obtener mascota y usuario para la ruta de archivos
            $mascota = Mascota::with('user')->findOrFail($request->mascota_id);
            $user = $mascota->user;
            $rutaBase = 'public/avatars/' . $user->cedula . '/documento_mascotas';

            // Preparar datos para actualizar el registro
            $data = $request->except(['certificado_veterinario', 'cedula_propietario']);

            // Manejar actualización de certificado veterinario
            if ($request->hasFile('certificado_veterinario')) {
                // Eliminar archivo anterior si existe
                if ($vacunaCertificacion->certificado_veterinario) {
                    Storage::delete('public/' . $vacunaCertificacion->certificado_veterinario);
                }

                $archivo = $request->file('certificado_veterinario');
                $nombreArchivo = strtolower(str_replace(' ', '_', $mascota->nombre)) . '_vacunas.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs($rutaBase, $nombreArchivo);
                $data['certificado_veterinario'] = str_replace('public/', '', $ruta);
            }

            // Manejar actualización de cédula del propietario
            if ($request->hasFile('cedula_propietario')) {
                // Eliminar archivo anterior si existe
                if ($vacunaCertificacion->cedula_propietario) {
                    Storage::delete('public/' . $vacunaCertificacion->cedula_propietario);
                }

                $archivo = $request->file('cedula_propietario');
                $nombreArchivo = strtolower(str_replace(' ', '_', $mascota->nombre)) . '_' . $user->cedula . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs($rutaBase, $nombreArchivo);
                $data['cedula_propietario'] = str_replace('public/', '', $ruta);
            }

            // Actualizar el registro
            $vacunaCertificacion->update($data);

            return redirect()->route('vacunas-certificaciones.index')
                ->with('success', 'Registro de vacunas y certificaciones actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar vacunas y certificaciones: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el registro. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Elimina un registro y sus archivos asociados
     */
    public function destroy(VacunaCertificacion $vacunaCertificacion)
    {
        try {
            // Eliminar archivos si existen
            if ($vacunaCertificacion->certificado_veterinario) {
                Storage::delete('public/' . $vacunaCertificacion->certificado_veterinario);
            }
            if ($vacunaCertificacion->cedula_propietario) {
                Storage::delete('public/' . $vacunaCertificacion->cedula_propietario);
            }

            // Eliminar el registro
            $vacunaCertificacion->delete();

            return redirect()->route('vacunas-certificaciones.index')
                ->with('success', 'Registro de vacunas y certificaciones eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar vacunas y certificaciones: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor, intente nuevamente.');
        }
    }
}
