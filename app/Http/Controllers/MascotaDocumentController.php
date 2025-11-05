<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\MascotaDocument;
use App\Models\DocumentRequirement;
use App\Services\DocumentValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MascotaDocumentController extends Controller
{
    protected DocumentValidationService $validationService;

    public function __construct(DocumentValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Si es administrador, ver todos los documentos
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            $documents = MascotaDocument::with(['mascota', 'documentRequirement', 'usuarioSubio'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Si no, solo los documentos de sus mascotas
            $documents = MascotaDocument::whereHas('mascota', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['mascota', 'documentRequirement', 'usuarioSubio'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }

        return view('mascota-documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $mascotaId = $request->mascota_id;
        
        if (!$mascotaId) {
            return redirect()->route('mascotas.index')
                ->with('error', 'Debe seleccionar una mascota.');
        }

        $mascota = Mascota::findOrFail($mascotaId);
        
        // Verificar propiedad (excepto admins)
        $user = Auth::user();
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para subir documentos de esta mascota.');
            }
        }

        // Obtener requisitos activos para esta mascota
        $requirements = $this->validationService->obtenerRequisitosActivos($mascota);
        
        // Obtener documentos ya subidos
        $documentosExistentes = MascotaDocument::where('mascota_id', $mascota->id)
            ->whereIn('document_requirement_id', $requirements->pluck('id'))
            ->get()
            ->keyBy('document_requirement_id');

        return view('mascota-documents.create', compact('mascota', 'requirements', 'documentosExistentes'));
    }

    /**
     * Store a newly created resource in storage.
     * Maneja múltiples documentos (uno por requisito)
     */
    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
        ]);

        $mascota = Mascota::findOrFail($request->mascota_id);
        $user = Auth::user();
        
        // Verificar propiedad
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascota->user_id !== $user->id) {
                return redirect()->back()
                    ->with('error', 'No tienes permiso para subir documentos de esta mascota.')
                    ->withInput();
            }
        }

        $documentosProcesados = 0;
        $errores = [];
        
        try {
            DB::beginTransaction();

            // Procesar cada requisito que tenga archivo
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'archivo_') === 0 && $request->hasFile($key)) {
                    $requirementId = str_replace('archivo_', '', $key);
                    $requirement = DocumentRequirement::find($requirementId);
                    
                    if (!$requirement) {
                        continue;
                    }

                    $archivo = $request->file($key);
                    $fechaEmision = $request->input("fecha_emision_{$requirementId}") 
                        ? Carbon::parse($request->input("fecha_emision_{$requirementId}")) 
                        : null;
                    $fechaVencimiento = $request->input("fecha_vencimiento_{$requirementId}") 
                        ? Carbon::parse($request->input("fecha_vencimiento_{$requirementId}")) 
                        : null;

                    // Validar documento
                    $validacion = $this->validationService->validarDocumento(
                        $mascota,
                        $requirement,
                        $archivo,
                        $fechaEmision,
                        $fechaVencimiento
                    );

                    if (!$validacion['valido']) {
                        $errores[] = "{$requirement->nombre}: " . implode(', ', $validacion['errores']);
                        continue;
                    }

                    // Almacenar documento
                    $datosArchivo = $this->validationService->almacenarDocumento(
                        $mascota,
                        $requirement,
                        $archivo,
                        $user->id
                    );

                    // Determinar estado inicial
                    $estado = 'pendiente';
                    if ($validacion['valido']) {
                        $estado = 'aprobado';
                    }

                    // Eliminar documento anterior si existe
                    $documentoAnterior = MascotaDocument::where('mascota_id', $mascota->id)
                        ->where('document_requirement_id', $requirement->id)
                        ->first();

                    if ($documentoAnterior) {
                        if ($documentoAnterior->ruta_archivo && Storage::disk('public')->exists($documentoAnterior->ruta_archivo)) {
                            Storage::disk('public')->delete($documentoAnterior->ruta_archivo);
                        }
                        $documentoAnterior->delete();
                    }

                    // Crear registro en BD
                    MascotaDocument::create([
                        'mascota_id' => $mascota->id,
                        'document_requirement_id' => $requirement->id,
                        'nombre_archivo' => $datosArchivo['nombre_archivo'],
                        'ruta_archivo' => $datosArchivo['ruta_archivo'],
                        'tipo_mime' => $datosArchivo['tipo_mime'],
                        'tamaño_bytes' => $datosArchivo['tamaño_bytes'],
                        'hash_archivo' => $datosArchivo['hash_archivo'],
                        'estado' => $estado,
                        'motivo_rechazo' => null,
                        'fecha_emision' => $fechaEmision,
                        'fecha_vencimiento' => $fechaVencimiento,
                        'validacion_automatica' => $validacion['valido'],
                        'detalles_validacion' => $validacion['detalles'] ?? [],
                        'usuario_subio_id' => $user->id,
                        'usuario_aprobo_id' => $estado === 'aprobado' ? $user->id : null,
                        'fecha_aprobacion' => $estado === 'aprobado' ? now() : null,
                        'notas' => $request->input("notas_{$requirementId}"),
                    ]);

                    $documentosProcesados++;
                }
            }

            DB::commit();

            if ($documentosProcesados > 0) {
                $mensaje = "Se procesaron {$documentosProcesados} documento(s) exitosamente.";
                if (!empty($errores)) {
                    $mensaje .= " Errores: " . implode('; ', $errores);
                }
                return redirect()->route('mascota-documents.index')
                    ->with('success', $mensaje);
            } else {
                return redirect()->back()
                    ->with('error', 'No se subieron documentos válidos.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al subir documentos: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al subir los documentos: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MascotaDocument $mascotaDocument)
    {
        $user = Auth::user();
        
        // Verificar propiedad
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascotaDocument->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para ver este documento.');
            }
        }

        $mascotaDocument->load(['mascota.user', 'documentRequirement', 'usuarioSubio', 'usuarioAprobo']);

        return view('mascota-documents.show', compact('mascotaDocument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MascotaDocument $mascotaDocument)
    {
        $user = Auth::user();
        
        // Verificar propiedad
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascotaDocument->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para editar este documento.');
            }
        }

        $mascotaDocument->load(['mascota', 'documentRequirement']);

        return view('mascota-documents.edit', compact('mascotaDocument'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MascotaDocument $mascotaDocument)
    {
        $validated = $request->validate([
            'fecha_emision' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:1000',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Verificar propiedad
            if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
                if ($mascotaDocument->mascota->user_id !== $user->id) {
                    return redirect()->back()
                        ->with('error', 'No tienes permiso para editar este documento.');
                }
            }

            // Si se sube un nuevo archivo
            if ($request->hasFile('archivo')) {
                // Eliminar archivo anterior
                if ($mascotaDocument->ruta_archivo && Storage::disk('public')->exists($mascotaDocument->ruta_archivo)) {
                    Storage::disk('public')->delete($mascotaDocument->ruta_archivo);
                }

                // Validar y almacenar nuevo archivo
                $fechaEmision = $validated['fecha_emision'] ? Carbon::parse($validated['fecha_emision']) : null;
                $fechaVencimiento = $validated['fecha_vencimiento'] ? Carbon::parse($validated['fecha_vencimiento']) : null;

                $validacion = $this->validationService->validarDocumento(
                    $mascotaDocument->mascota,
                    $mascotaDocument->documentRequirement,
                    $request->file('archivo'),
                    $fechaEmision,
                    $fechaVencimiento
                );

                $datosArchivo = $this->validationService->almacenarDocumento(
                    $mascotaDocument->mascota,
                    $mascotaDocument->documentRequirement,
                    $request->file('archivo'),
                    $user->id
                );

                $mascotaDocument->nombre_archivo = $datosArchivo['nombre_archivo'];
                $mascotaDocument->ruta_archivo = $datosArchivo['ruta_archivo'];
                $mascotaDocument->tipo_mime = $datosArchivo['tipo_mime'];
                $mascotaDocument->tamaño_bytes = $datosArchivo['tamaño_bytes'];
                $mascotaDocument->hash_archivo = $datosArchivo['hash_archivo'];
                $mascotaDocument->validacion_automatica = $validacion['valido'];
                $mascotaDocument->detalles_validacion = $validacion['detalles'] ?? [];

                // Re-evaluar estado
                if ($validacion['valido']) {
                    $mascotaDocument->estado = 'aprobado';
                    $mascotaDocument->usuario_aprobo_id = $user->id;
                    $mascotaDocument->fecha_aprobacion = now();
                    $mascotaDocument->motivo_rechazo = null;
                } else {
                    $mascotaDocument->estado = 'pendiente_correccion';
                    $mascotaDocument->motivo_rechazo = implode('; ', $validacion['errores']);
                }
            }

            // Actualizar fechas y notas
            $mascotaDocument->fecha_emision = $validated['fecha_emision'] ? Carbon::parse($validated['fecha_emision']) : null;
            $mascotaDocument->fecha_vencimiento = $validated['fecha_vencimiento'] ? Carbon::parse($validated['fecha_vencimiento']) : null;
            $mascotaDocument->notas = $validated['notas'] ?? null;
            $mascotaDocument->save();

            DB::commit();

            return redirect()->route('mascota-documents.show', $mascotaDocument)
                ->with('success', 'Documento actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar documento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al actualizar el documento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MascotaDocument $mascotaDocument)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Verificar propiedad
            if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
                if ($mascotaDocument->mascota->user_id !== $user->id) {
                    return redirect()->back()
                        ->with('error', 'No tienes permiso para eliminar este documento.');
                }
            }

            // Eliminar archivo físico
            if ($mascotaDocument->ruta_archivo && Storage::disk('public')->exists($mascotaDocument->ruta_archivo)) {
                Storage::disk('public')->delete($mascotaDocument->ruta_archivo);
            }

            $mascotaDocument->delete();

            DB::commit();

            return redirect()->route('mascota-documents.index')
                ->with('success', 'Documento eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar documento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al eliminar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Aprobar documento (solo admins)
     */
    public function aprobar(Request $request, MascotaDocument $mascotaDocument)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            abort(403, 'Solo los administradores pueden aprobar documentos.');
        }

        $request->validate([
            'notas' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $mascotaDocument->estado = 'aprobado';
            $mascotaDocument->usuario_aprobo_id = $user->id;
            $mascotaDocument->fecha_aprobacion = now();
            $mascotaDocument->motivo_rechazo = null;
            if ($request->notas) {
                $mascotaDocument->notas = $request->notas;
            }
            $mascotaDocument->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Documento aprobado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aprobar documento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al aprobar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar documento (solo admins)
     */
    public function rechazar(Request $request, MascotaDocument $mascotaDocument)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            abort(403, 'Solo los administradores pueden rechazar documentos.');
        }

        $request->validate([
            'motivo_rechazo' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $mascotaDocument->estado = 'rechazado';
            $mascotaDocument->motivo_rechazo = $request->motivo_rechazo;
            $mascotaDocument->usuario_aprobo_id = null;
            $mascotaDocument->fecha_aprobacion = null;
            $mascotaDocument->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Documento rechazado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al rechazar documento: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al rechazar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Descargar documento
     */
    public function descargar(MascotaDocument $mascotaDocument)
    {
        $user = Auth::user();
        
        // Verificar propiedad
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin')) {
            if ($mascotaDocument->mascota->user_id !== $user->id) {
                abort(403, 'No tienes permiso para descargar este documento.');
            }
        }

        if (!$mascotaDocument->ruta_archivo || !Storage::disk('public')->exists($mascotaDocument->ruta_archivo)) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('public')->download(
            $mascotaDocument->ruta_archivo,
            $mascotaDocument->nombre_archivo
        );
    }
}
