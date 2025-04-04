<?php

namespace App\Http\Controllers;

use App\Models\AfiliadosCoberturasSalud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AfiliadosCoberturasSaludRequest;
use App\Models\CoberturasSalud;
use App\Models\RegimenesSalud;
use App\Models\TiposAfiliacione;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AfiliadosCoberturasSaludController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $afiliadosCoberturasSaluds = AfiliadosCoberturasSalud::paginate();

        return view('afiliados-coberturas-salud.index', compact('afiliadosCoberturasSaluds'))
            ->with('i', ($request->input('page', 1) - 1) * $afiliadosCoberturasSaluds->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $afiliadosCoberturasSalud = new AfiliadosCoberturasSalud();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        $eps=CoberturasSalud::all();
        $tipo_afiliacion=TiposAfiliacione::all();
        $regimen=RegimenesSalud::all();
        return view('afiliados-coberturas-salud.create', compact('afiliadosCoberturasSalud','cedula','nombre','eps','tipo_afiliacion','regimen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AfiliadosCoberturasSaludRequest $request): RedirectResponse
    {
        AfiliadosCoberturasSalud::create($request->validated());

        return Redirect::route('hallazgos-observaciones.create')
            ->with('success', 'AfiliadosCoberturasSalud created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $afiliadosCoberturasSalud = AfiliadosCoberturasSalud::find($id);

        return view('afiliados-coberturas-salud.show', compact('afiliadosCoberturasSalud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $afiliadosCoberturasSalud = AfiliadosCoberturasSalud::find($id);

        return view('afiliados-coberturas-salud.edit', compact('afiliadosCoberturasSalud'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AfiliadosCoberturasSaludRequest $request, AfiliadosCoberturasSalud $afiliadosCoberturasSalud): RedirectResponse
    {
        $afiliadosCoberturasSalud->update($request->validated());

        return Redirect::route('afiliados-coberturas-saluds.index')
            ->with('success', 'AfiliadosCoberturasSalud updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        AfiliadosCoberturasSalud::find($id)->delete();

        return Redirect::route('afiliados-coberturas-saluds.index')
            ->with('success', 'AfiliadosCoberturasSalud deleted successfully');
    }
}
