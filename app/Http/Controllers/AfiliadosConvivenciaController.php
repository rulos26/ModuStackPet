<?php

namespace App\Http\Controllers;

use App\Models\AfiliadosConvivencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AfiliadosConvivenciaRequest;
use App\Models\EstadosCivile;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AfiliadosConvivenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $afiliadosConvivencias = AfiliadosConvivencia::paginate();

        return view('afiliados-convivencia.index', compact('afiliadosConvivencias'))
            ->with('i', ($request->input('page', 1) - 1) * $afiliadosConvivencias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
       $afiliadosConvivencia = new AfiliadosConvivencia();
        $estado_c = EstadosCivile::all();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('afiliados-convivencia.create', compact('afiliadosConvivencia','estado_c','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AfiliadosConvivenciaRequest $request): RedirectResponse
    {
        
        AfiliadosConvivencia::create($request->validated());

        return Redirect::route('datos-basicos-hijos.create')
            ->with('success', 'AfiliadosConvivencia created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $afiliadosConvivencia = AfiliadosConvivencia::find($id);

        return view('afiliados-convivencia.show', compact('afiliadosConvivencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $afiliadosConvivencia = AfiliadosConvivencia::find($id);

        return view('afiliados-convivencia.edit', compact('afiliadosConvivencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AfiliadosConvivenciaRequest $request, AfiliadosConvivencia $afiliadosConvivencia): RedirectResponse
    {
        $afiliadosConvivencia->update($request->validated());

        return Redirect::route('afiliados-convivencias.index')
            ->with('success', 'AfiliadosConvivencia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        AfiliadosConvivencia::find($id)->delete();

        return Redirect::route('afiliados-convivencias.index')
            ->with('success', 'AfiliadosConvivencia deleted successfully');
    }
}
