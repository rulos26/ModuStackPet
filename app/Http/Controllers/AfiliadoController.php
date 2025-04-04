<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AfiliadoRequest;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AfiliadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $afiliados = Afiliado::paginate();

        return view('afiliado.index', compact('afiliados'))
            ->with('i', ($request->input('page', 1) - 1) * $afiliados->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $afiliado = new Afiliado();
        $depa=Departamento::all();
        $muni=Municipio::all();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('afiliado.create', compact('afiliado','depa','muni','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AfiliadoRequest $request): RedirectResponse
    {
        Afiliado::create($request->validated());

        return Redirect::route('afiliados-convivencias.create')
            ->with('success', 'Afiliado created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $afiliado = Afiliado::find($id);

        return view('afiliado.show', compact('afiliado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $afiliado = Afiliado::find($id);

        return view('afiliado.edit', compact('afiliado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AfiliadoRequest $request, Afiliado $afiliado): RedirectResponse
    {
        $afiliado->update($request->validated());

        return Redirect::route('afiliados.index')
            ->with('success', 'Afiliado updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Afiliado::find($id)->delete();

        return Redirect::route('afiliados.index')
            ->with('success', 'Afiliado deleted successfully');
    }
}
