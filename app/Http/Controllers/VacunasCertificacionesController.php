<?php

namespace App\Http\Controllers;

use App\Models\VacunasCertificacione;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VacunasCertificacionesController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Genera la ruta para almacenar los documentos de la mascota.
     *
     * @param string $cedula
     * @param string $nombreMascota
     * @param string $tipoDocumento
     * @return string
     */
    private function generarRutaDocumento($cedula, $nombreMascota, $tipoDocumento)
    {
        $nombreArchivo = Str::slug($nombreMascota . '_' . $tipoDocumento . '_' . time());
        return "documentos_mascotas/" . Str::slug($cedula) . "/{$nombreArchivo}";
    }

    /**
     * Maneja el almacenamiento de un archivo.
     *
     * @param \Illuminate\Http\UploadedFile $archivo
     * @param string $ruta
     * @return string
     */
    private function almacenarArchivo($archivo, $ruta)
    {
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = $ruta . '.' . $extension;

        // Asegurar que el directorio existe
        $directorioBase = dirname($nombreArchivo);
        if (!Storage::disk('public')->exists($directorioBase)) {
            Storage::disk('public')->makeDirectory($directorioBase, 0755, true);
        }

        Storage::disk('public')->put($nombreArchivo, file_get_contents($archivo));
        return $nombreArchivo;
    }

    /**
     * Muestra una lista de los registros de vacunas y certificaciones.
     * Solo los administradores ven todos los registros.
     * Los demás usuarios solo ven las vacunas de sus propias mascotas.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Si es administrador (Superadmin o Admin), mostrar todas las vacunas
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            $vacunasCertificaciones = VacunasCertificacione::with('mascota')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Si no es administrador, solo mostrar las vacunas de las mascotas del usuario autenticado
            $vacunasCertificaciones = VacunasCertificacione::whereHas('mascota', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('mascota')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        
        $i = ($vacunasCertificaciones->currentPage() - 1) * $vacunasCertificaciones->perPage();
        return view('vacunas_certificaciones.index', compact('vacunasCertificaciones', 'i'));
    }

    /**
     * Muestra el formulario para crear un nuevo registro.
     * Solo los administradores pueden ver todas las mascotas.
     * Los demás usuarios solo ven sus propias mascotas.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        // Si es administrador (Superadmin o Admin), mostrar todas las mascotas
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            $mascotas = Mascota::with('raza')->orderBy('nombre')->get();
        } else {
            // Si no es administrador, solo mostrar las mascotas del usuario autenticado
            $mascotas = Mascota::where('user_id', $user->id)
                ->with('raza')
                ->orderBy('nombre')
                ->get();
        }
        
        return view('vacunas_certificaciones.create', compact('mascotas'));
    }

    /**
     * Almacena un nuevo registro de vacunas y certificaciones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mascota' => 'required|exists:mascotas,id',
            'fecha_ultima_vacuna' => 'required|date|before_or_equal:today',
            'operaciones' => 'nullable|string',
            'certificado_veterinario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cedula_propietario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();
        $mascota = Mascota::findOrFail($request->id_mascota);
        
        // Validar que el usuario tenga permiso sobre esta mascota (a menos que sea admin)
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascota->user_id !== $user->id) {
                return redirect()->route('vacunas_certificaciones.create')
                    ->with('error', 'No tienes permiso para crear registros para esta mascota.')
                    ->withInput();
            }
        }

        try {
            DB::beginTransaction();
            
            $data = $request->all();

            // Manejar el archivo del certificado veterinario
            if ($request->hasFile('certificado_veterinario')) {
                $ruta = $this->generarRutaDocumento(
                    $user->cedula ?? $user->id,
                    $mascota->nombre,
                    'vacunas'
                );
                $data['certificado_veterinario'] = $this->almacenarArchivo(
                    $request->file('certificado_veterinario'),
                    $ruta
                );
            }

            // Manejar el archivo de la cédula del propietario
            if ($request->hasFile('cedula_propietario')) {
                $ruta = $this->generarRutaDocumento(
                    $user->cedula ?? $user->id,
                    $mascota->nombre,
                    'cedula'
                );
                $data['cedula_propietario'] = $this->almacenarArchivo(
                    $request->file('cedula_propietario'),
                    $ruta
                );
            }

            VacunasCertificacione::create($data);
            
            DB::commit();

            return redirect()->route('vacunas_certificaciones.index')
                ->with('success', 'Registro de vacunas y certificaciones creado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Eliminar archivos subidos si hubo error
            if (isset($data['certificado_veterinario']) && Storage::disk('public')->exists($data['certificado_veterinario'])) {
                Storage::disk('public')->delete($data['certificado_veterinario']);
            }
            if (isset($data['cedula_propietario']) && Storage::disk('public')->exists($data['cedula_propietario'])) {
                Storage::disk('public')->delete($data['cedula_propietario']);
            }
            
            return redirect()->route('vacunas_certificaciones.create')
                ->with('error', 'Error al crear el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de un registro específico.
     * Valida que el usuario tenga permiso para ver este registro.
     *
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function show(VacunasCertificacione $vacunasCertificacione)
    {
        $user = Auth::user();
        
        // Validar que el usuario tenga permiso sobre esta mascota (a menos que sea admin)
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($vacunasCertificacione->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para ver este registro.');
            }
        }
        
        $vacunasCertificacione->load('mascota');
        return view('vacunas_certificaciones.show', compact('vacunasCertificacione'));
    }

    /**
     * Muestra el formulario para editar un registro.
     * Valida que el usuario tenga permiso para editar este registro.
     *
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function edit(VacunasCertificacione $vacunasCertificacione)
    {
        $user = Auth::user();
        
        // Validar que el usuario tenga permiso sobre esta mascota (a menos que sea admin)
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($vacunasCertificacione->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para editar este registro.');
            }
        }
        
        // Si es administrador, mostrar todas las mascotas; si no, solo las del usuario
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            $mascotas = Mascota::orderBy('nombre')->get();
        } else {
            $mascotas = Mascota::where('user_id', $user->id)
                ->orderBy('nombre')
                ->get();
        }
        
        $vacunasCertificacione->load('mascota');
        return view('vacunas_certificaciones.edit', compact('vacunasCertificacione', 'mascotas'));
    }

    /**
     * Actualiza un registro específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VacunasCertificacione $vacunasCertificacione)
    {
        $request->validate([
            'id_mascota' => 'required|exists:mascotas,id',
            'fecha_ultima_vacuna' => 'required|date|before_or_equal:today',
            'operaciones' => 'nullable|string',
            'certificado_veterinario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cedula_propietario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();
        
        // Validar que el usuario tenga permiso sobre este registro (a menos que sea admin)
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($vacunasCertificacione->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para editar este registro.');
            }
        }
        
        $mascota = Mascota::findOrFail($request->id_mascota);
        
        // Validar que el usuario tenga permiso sobre la nueva mascota (a menos que sea admin)
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascota->user_id !== $user->id) {
                return redirect()->route('vacunas_certificaciones.edit', $vacunasCertificacione)
                    ->with('error', 'No tienes permiso para asignar esta mascota.')
                    ->withInput();
            }
        }

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            $archivosEliminados = [];

            // Manejar el archivo del certificado veterinario
            if ($request->hasFile('certificado_veterinario')) {
                // Eliminar el archivo anterior si existe
                if ($vacunasCertificacione->certificado_veterinario && Storage::disk('public')->exists($vacunasCertificacione->certificado_veterinario)) {
                    $archivosEliminados[] = $vacunasCertificacione->certificado_veterinario;
                    Storage::disk('public')->delete($vacunasCertificacione->certificado_veterinario);
                }
                $ruta = $this->generarRutaDocumento(
                    $user->cedula ?? $user->id,
                    $mascota->nombre,
                    'vacunas'
                );
                $data['certificado_veterinario'] = $this->almacenarArchivo(
                    $request->file('certificado_veterinario'),
                    $ruta
                );
            }

            // Manejar el archivo de la cédula del propietario
            if ($request->hasFile('cedula_propietario')) {
                // Eliminar el archivo anterior si existe
                if ($vacunasCertificacione->cedula_propietario && Storage::disk('public')->exists($vacunasCertificacione->cedula_propietario)) {
                    $archivosEliminados[] = $vacunasCertificacione->cedula_propietario;
                    Storage::disk('public')->delete($vacunasCertificacione->cedula_propietario);
                }
                $ruta = $this->generarRutaDocumento(
                    $user->cedula ?? $user->id,
                    $mascota->nombre,
                    'cedula'
                );
                $data['cedula_propietario'] = $this->almacenarArchivo(
                    $request->file('cedula_propietario'),
                    $ruta
                );
            }

            $vacunasCertificacione->update($data);
            
            DB::commit();

            return redirect()->route('vacunas_certificaciones.index')
                ->with('success', 'Registro de vacunas y certificaciones actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Eliminar archivos nuevos si hubo error
            if (isset($data['certificado_veterinario']) && Storage::disk('public')->exists($data['certificado_veterinario'])) {
                Storage::disk('public')->delete($data['certificado_veterinario']);
            }
            if (isset($data['cedula_propietario']) && Storage::disk('public')->exists($data['cedula_propietario'])) {
                Storage::disk('public')->delete($data['cedula_propietario']);
            }
            
            return redirect()->route('vacunas_certificaciones.edit', $vacunasCertificacione)
                ->with('error', 'Error al actualizar el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Elimina un registro específico.
     * Valida que el usuario tenga permiso para eliminar este registro.
     *
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function destroy(VacunasCertificacione $vacunasCertificacione)
    {
        $user = Auth::user();
        
        // Validar que el usuario tenga permiso sobre este registro (a menos que sea admin)
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($vacunasCertificacione->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para eliminar este registro.');
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Eliminar los archivos si existen
            if ($vacunasCertificacione->certificado_veterinario && Storage::disk('public')->exists($vacunasCertificacione->certificado_veterinario)) {
                Storage::disk('public')->delete($vacunasCertificacione->certificado_veterinario);
            }
            if ($vacunasCertificacione->cedula_propietario && Storage::disk('public')->exists($vacunasCertificacione->cedula_propietario)) {
                Storage::disk('public')->delete($vacunasCertificacione->cedula_propietario);
            }

            $vacunasCertificacione->delete();
            
            DB::commit();

            return redirect()->route('vacunas_certificaciones.index')
                ->with('success', 'Registro de vacunas y certificaciones eliminado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('vacunas_certificaciones.index')
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }
}
