<?php

namespace App\Http\Controllers;

use App\Models\DireccionesVivienda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DireccionesViviendaRequest;
use App\Models\TiposDePropiedade;
use App\Models\TiposDeVivienda;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DireccionesViviendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $direccionesViviendas = DireccionesVivienda::paginate();

        return view('direcciones-vivienda.index', compact('direccionesViviendas'))
            ->with('i', ($request->input('page', 1) - 1) * $direccionesViviendas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $direccionesVivienda = new DireccionesVivienda();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        $tipo_propiedad=TiposDePropiedade::all();
        $tipos_vivienda=TiposDeVivienda::all();
        return view('direcciones-vivienda.create', compact('direccionesVivienda','cedula','tipo_propiedad','tipos_vivienda'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DireccionesVivienda::create($request->all());

        return Redirect::route('empleos-afiliados.create')
            ->with('success', 'DireccionesVivienda created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $direccionesVivienda = DireccionesVivienda::find($id);

        return view('direcciones-vivienda.show', compact('direccionesVivienda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $direccionesVivienda = DireccionesVivienda::find($id);

        return view('direcciones-vivienda.edit', compact('direccionesVivienda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DireccionesViviendaRequest $request, DireccionesVivienda $direccionesVivienda): RedirectResponse
    {
        $direccionesVivienda->update($request->validated());

        return Redirect::route('direcciones-viviendas.index')
            ->with('success', 'DireccionesVivienda updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DireccionesVivienda::find($id)->delete();

        return Redirect::route('direcciones-viviendas.index')
            ->with('success', 'DireccionesVivienda deleted successfully');
    }
}
