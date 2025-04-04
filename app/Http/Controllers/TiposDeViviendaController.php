<?php

namespace App\Http\Controllers;

use App\Models\TiposDeVivienda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TiposDeViviendaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TiposDeViviendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tiposDeViviendas = TiposDeVivienda::paginate();

        return view('tipos-de-vivienda.index', compact('tiposDeViviendas'))
            ->with('i', ($request->input('page', 1) - 1) * $tiposDeViviendas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tiposDeVivienda = new TiposDeVivienda();

        return view('tipos-de-vivienda.create', compact('tiposDeVivienda'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposDeViviendaRequest $request): RedirectResponse
    {
        TiposDeVivienda::create($request->validated());

        return Redirect::route('tipos-de-viviendas.index')
            ->with('success', 'TiposDeVivienda created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tiposDeVivienda = TiposDeVivienda::find($id);

        return view('tipos-de-vivienda.show', compact('tiposDeVivienda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tiposDeVivienda = TiposDeVivienda::find($id);

        return view('tipos-de-vivienda.edit', compact('tiposDeVivienda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposDeViviendaRequest $request, TiposDeVivienda $tiposDeVivienda): RedirectResponse
    {
        $tiposDeVivienda->update($request->validated());

        return Redirect::route('tipos-de-viviendas.index')
            ->with('success', 'TiposDeVivienda updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TiposDeVivienda::find($id)->delete();

        return Redirect::route('tipos-de-viviendas.index')
            ->with('success', 'TiposDeVivienda deleted successfully');
    }
}
