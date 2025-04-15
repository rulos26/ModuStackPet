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
     * Muestra la información de la empresa o el formulario de creación
     */
    public function index()
    {
        // Verificar si ya existe una empresa
        $empresa = Empresa::first();

        if ($empresa) {
            // Si existe, mostrar la información de la empresa
            return view('empresa.show', compact('empresa'));
        }

        // Si no existe, mostrar el formulario de creación
        return $this->create();
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
            return redirect()->route('empresas.index')
                ->with('error', 'Ya existe una empresa registrada.');
        }

        try {
            $data = $request->validated();

            // Procesar el logo si se subió
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $nombreLogo = Str::slug($data['nombre_legal']) . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $rutaLogo = $logo->storeAs('logos', $nombreLogo, 'public');
                $data['logo'] = $rutaLogo;
            }

            // Crear la empresa
            $empresa = Empresa::create($data);

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la empresa: ' . $e->getMessage())
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
            // Buscar la empresa
            $empresa = Empresa::findOrFail($id);
            $data = $request->validated();

            // Procesar el logo si se subió uno nuevo
            if ($request->hasFile('logo')) {
                // Eliminar el logo anterior si existe
                if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                    Storage::disk('public')->delete($empresa->logo);
                }

                $logo = $request->file('logo');
                $nombreLogo = Str::slug($data['nombre_legal']) . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $rutaLogo = $logo->storeAs('logos', $nombreLogo, 'public');
                $data['logo'] = $rutaLogo;
            }

            // Actualizar la empresa
            $empresa->update($data);

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la empresa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Elimina una empresa de la base de datos
     */
    public function destroy($id)
    {
        try {
            // Buscar la empresa
            $empresa = Empresa::findOrFail($id);

            // Eliminar el logo si existe
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }

            $empresa->delete();

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la empresa: ' . $e->getMessage());
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
}
