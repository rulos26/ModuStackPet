<?php

namespace App\Http\Controllers;

use App\Models\Siniestro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SiniestroRequest;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SiniestroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $siniestros = Siniestro::paginate();

        return view('siniestro.index', compact('siniestros'))
            ->with('i', ($request->input('page', 1) - 1) * $siniestros->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $siniestro = new Siniestro();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        $depa=Departamento::all();
        $muni=Municipio::all();
        return view('siniestro.create', compact('siniestro','cedula','nombre','depa','muni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiniestroRequest $request): RedirectResponse
    {
        Siniestro::create($request->validated());

        return Redirect::route('hechos-ocurrencias.create')
            ->with('success', 'Siniestro created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $siniestro = Siniestro::find($id);

        return view('siniestro.show', compact('siniestro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $siniestro = Siniestro::find($id);

        return view('siniestro.edit', compact('siniestro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiniestroRequest $request, Siniestro $siniestro): RedirectResponse
    {
        $siniestro->update($request->validated());

        return Redirect::route('siniestros.index')
            ->with('success', 'Siniestro updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Siniestro::find($id)->delete();

        return Redirect::route('siniestros.index')
            ->with('success', 'Siniestro deleted successfully');
    }
}
