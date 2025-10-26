<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\Sector;
use App\Models\TipoEmpresa;
use App\Http\Requests\EmpresaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    /**
     * Constructor del controlador
     * Aplica el middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirige a la vista apropiada según si existe una empresa o no
     */
    public function index()
    {
        $empresa = Empresa::first();

        if ($empresa) {
            // Si existe, redirigir a la vista de detalles
            return redirect()->route('empresas.show', $empresa);
        }

        // Si no existe, redirigir al formulario de creación
        return redirect()->route('empresas.create')
            ->with('info', 'No hay empresa registrada. Complete el formulario para crear la empresa.');
    }

    /**
     * Muestra el formulario para crear una nueva empresa
     */
    public function create()
    {
        $departamentos = DB::table('departamentos')
            ->where('estado', 1)
            ->select('id_departamento', 'nombre as departamento')
            ->orderBy('nombre')
            ->get();

        Log::info('Departamentos encontrados: ' . $departamentos->count());

        $tiposEmpresas = TipoEmpresa::orderBy('nombre')->get();
        $sectores = Sector::orderBy('nombre')->get();

        return view('empresa.create', compact('departamentos', 'tiposEmpresas', 'sectores'));
    }

    /**
     * Almacena una nueva empresa en la base de datos
     */
    public function store(EmpresaRequest $request)
    {
        // Verificar si ya existe una empresa
        if (Empresa::exists()) {
            return redirect()->route('empresas.create')
                ->with('error', 'Ya existe una empresa registrada.');
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['logo'] = $this->procesarLogo($request);

            // Crear la empresa
            $empresa = Empresa::create($data);

            DB::commit();

            return redirect()->route('empresas.show', $empresa)
                ->with('success', 'Empresa creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear empresa: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al crear la empresa. Intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de una empresa específica
     */
    public function show($id)
    {
        // Buscar la empresa con sus relaciones
        $empresa = Empresa::with(['tipoEmpresa', 'ciudad', 'departamento', 'sector'])
            ->findOrFail($id);

        return view('empresa.show', compact('empresa'));
    }

    /**
     * Muestra el formulario para editar una empresa
     */
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);

        $tiposEmpresas = TipoEmpresa::orderBy('nombre')->get();
        $departamentos = DB::table('departamentos')
            ->where('estado', 1)
            ->select('id_departamento', 'nombre as departamento')
            ->orderBy('nombre')
            ->get();
        $ciudades = Ciudad::where('estado', 1)->orderBy('municipio')->get();
        $sectores = Sector::orderBy('nombre')->get();

        return view('empresa.edit', compact('empresa', 'tiposEmpresas', 'departamentos', 'ciudades', 'sectores'));
    }

    /**
     * Actualiza una empresa en la base de datos
     */
    public function update(EmpresaRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar la empresa
            $empresa = Empresa::findOrFail($id);
            $data = $request->validated();
            $data['logo'] = $this->procesarLogo($request, $empresa);

            // Actualizar la empresa
            $empresa->update($data);

            DB::commit();

            return redirect()->route('empresas.show', $empresa)
                ->with('success', 'Empresa actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar empresa: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al actualizar la empresa. Intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Elimina una empresa de la base de datos
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Buscar la empresa
            $empresa = Empresa::findOrFail($id);

            // Eliminar el logo si existe
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }

            $empresa->delete();

            DB::commit();

            return redirect()->route('empresas.create')
                ->with('success', 'Empresa eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar empresa: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al eliminar la empresa. Intente nuevamente.');
        }
    }

    /**
     * Obtiene las ciudades de un departamento específico
     */
    public function getCiudades($departamentoId)
    {
        try {
            Log::info('Obteniendo ciudades para departamento: ' . $departamentoId);

            $ciudades = DB::table('ciudades')
                ->where('departamento_id', $departamentoId)
                ->where('estado', 1)
                ->select('id_municipio', 'municipio')
                ->orderBy('municipio')
                ->get();

            Log::info('Ciudades encontradas: ' . $ciudades->count());

            return response()->json($ciudades);
        } catch (\Exception $e) {
            Log::error('Error al cargar ciudades: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar las ciudades: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Genera un PDF con la información de la empresa
     */
    public function pdf($id)
    {
        try {
            $empresa = Empresa::with(['tipoEmpresa', 'ciudad', 'departamento', 'sector'])
                ->findOrFail($id);

            $pdf = \PDF::loadView('empresa.pdf', compact('empresa'));

            return $pdf->download('empresa-' . Str::slug($empresa->nombre_legal) . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar PDF de empresa: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al generar el PDF. Intente nuevamente.');
        }
    }

    /**
     * Procesa el logo subido por el usuario
     */
    private function procesarLogo($request, $empresa = null)
    {
        if (!$request->hasFile('logo')) {
            return $empresa ? $empresa->logo : null;
        }

        // Eliminar logo anterior si existe
        if ($empresa && $empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
            Storage::disk('public')->delete($empresa->logo);
        }

        $logo = $request->file('logo');
        $nombreLogo = Str::slug($request->nombre_legal) . '-' . time() . '.' . $logo->getClientOriginalExtension();

        return $logo->storeAs('logos', $nombreLogo, 'public');
    }
}
