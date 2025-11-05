<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequirement;
use App\Models\DocumentRequirementLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requirements = DocumentRequirement::ordenados()
            ->withCount('mascotaDocuments')
            ->get();

        return view('document-requirements.index', compact('requirements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('document-requirements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:document_requirements,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'obligatorio' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
            'tipo_validacion' => 'nullable|string',
            'dias_validez' => 'nullable|integer|min:1',
            'formatos_permitidos' => 'nullable|array',
            'tamaño_maximo_kb' => 'integer|min:1',
            'aplica_razas_peligrosas' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $requirement = DocumentRequirement::create($validated);

            // Registrar log
            $this->registrarLog($requirement, 'creado', null, $validated, 'Requisito creado');

            DB::commit();

            return redirect()->route('document-requirements.index')
                ->with('success', 'Requisito documental creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear requisito documental: ' . $e->getMessage());

            return redirect()->route('document-requirements.create')
                ->with('error', 'Error al crear el requisito: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentRequirement $documentRequirement)
    {
        $documentRequirement->load(['logs.user', 'mascotaDocuments.mascota']);
        
        return view('document-requirements.show', compact('documentRequirement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentRequirement $documentRequirement)
    {
        return view('document-requirements.edit', compact('documentRequirement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentRequirement $documentRequirement)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:document_requirements,codigo,' . $documentRequirement->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'obligatorio' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
            'tipo_validacion' => 'nullable|string',
            'dias_validez' => 'nullable|integer|min:1',
            'formatos_permitidos' => 'nullable|array',
            'tamaño_maximo_kb' => 'integer|min:1',
            'aplica_razas_peligrosas' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $valoresAnteriores = $documentRequirement->toArray();
            $documentRequirement->update($validated);

            // Registrar log
            $this->registrarLog($documentRequirement, 'modificado', $valoresAnteriores, $validated, $request->motivo ?? 'Requisito modificado');

            DB::commit();

            return redirect()->route('document-requirements.index')
                ->with('success', 'Requisito documental actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar requisito documental: ' . $e->getMessage());

            return redirect()->route('document-requirements.edit', $documentRequirement)
                ->with('error', 'Error al actualizar el requisito: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentRequirement $documentRequirement)
    {
        try {
            DB::beginTransaction();

            // Registrar log antes de eliminar
            $this->registrarLog($documentRequirement, 'eliminado', $documentRequirement->toArray(), null, 'Requisito eliminado');

            $documentRequirement->delete();

            DB::commit();

            return redirect()->route('document-requirements.index')
                ->with('success', 'Requisito documental eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar requisito documental: ' . $e->getMessage());

            return redirect()->route('document-requirements.index')
                ->with('error', 'Error al eliminar el requisito: ' . $e->getMessage());
        }
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleStatus(Request $request, DocumentRequirement $documentRequirement)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $valoresAnteriores = ['activo' => $documentRequirement->activo];
            $documentRequirement->activo = !$documentRequirement->activo;
            $documentRequirement->save();

            $accion = $documentRequirement->activo ? 'activado' : 'desactivado';
            $this->registrarLog(
                $documentRequirement,
                $accion,
                $valoresAnteriores,
                ['activo' => $documentRequirement->activo],
                $request->motivo ?? "Requisito {$accion}"
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Requisito {$accion} exitosamente.",
                'activo' => $documentRequirement->activo,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cambiar estado del requisito: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Registrar log de cambios
     */
    protected function registrarLog(
        DocumentRequirement $requirement,
        string $accion,
        ?array $valoresAnteriores,
        ?array $valoresNuevos,
        ?string $motivo = null
    ): void {
        DocumentRequirementLog::create([
            'document_requirement_id' => $requirement->id,
            'user_id' => Auth::id(),
            'accion' => $accion,
            'valores_anteriores' => $valoresAnteriores,
            'valores_nuevos' => $valoresNuevos,
            'motivo' => $motivo,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
