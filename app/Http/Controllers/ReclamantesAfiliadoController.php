<?php

namespace App\Http\Controllers;

use App\Models\ReclamantesAfiliado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReclamantesAfiliadoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ReclamantesAfiliadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $reclamantesAfiliados = ReclamantesAfiliado::paginate();

        return view('reclamantes-afiliado.index', compact('reclamantesAfiliados'))
            ->with('i', ($request->input('page', 1) - 1) * $reclamantesAfiliados->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reclamantesAfiliado = new ReclamantesAfiliado();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('reclamantes-afiliado.create', compact('reclamantesAfiliado','nombre','cedula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReclamantesAfiliadoRequest $request): RedirectResponse
    {
        ReclamantesAfiliado::create($request->validated());

        return Redirect::route('verificaciones.create')
            ->with('success', 'ReclamantesAfiliado created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $reclamantesAfiliado = ReclamantesAfiliado::find($id);

        return view('reclamantes-afiliado.show', compact('reclamantesAfiliado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reclamantesAfiliado = ReclamantesAfiliado::find($id);

        return view('reclamantes-afiliado.edit', compact('reclamantesAfiliado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReclamantesAfiliadoRequest $request, ReclamantesAfiliado $reclamantesAfiliado): RedirectResponse
    {
        $reclamantesAfiliado->update($request->validated());

        return Redirect::route('reclamantes-afiliados.index')
            ->with('success', 'ReclamantesAfiliado updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ReclamantesAfiliado::find($id)->delete();

        return Redirect::route('reclamantes-afiliados.index')
            ->with('success', 'ReclamantesAfiliado deleted successfully');
    }
}
