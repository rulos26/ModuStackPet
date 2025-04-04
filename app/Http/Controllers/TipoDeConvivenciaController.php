<?php

namespace App\Http\Controllers;

use App\Models\TipoDeConvivencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipoDeConvivenciaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoDeConvivenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipoDeConvivencias = TipoDeConvivencia::paginate();

        return view('tipo-de-convivencia.index', compact('tipoDeConvivencias'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoDeConvivencias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoDeConvivencia = new TipoDeConvivencia();

        return view('tipo-de-convivencia.create', compact('tipoDeConvivencia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoDeConvivenciaRequest $request): RedirectResponse
    {
        TipoDeConvivencia::create($request->validated());

        return Redirect::route('tipo-de-convivencias.index')
            ->with('success', 'TipoDeConvivencia created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tipoDeConvivencia = TipoDeConvivencia::find($id);

        return view('tipo-de-convivencia.show', compact('tipoDeConvivencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tipoDeConvivencia = TipoDeConvivencia::find($id);

        return view('tipo-de-convivencia.edit', compact('tipoDeConvivencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoDeConvivenciaRequest $request, TipoDeConvivencia $tipoDeConvivencia): RedirectResponse
    {
        $tipoDeConvivencia->update($request->validated());

        return Redirect::route('tipo-de-convivencias.index')
            ->with('success', 'TipoDeConvivencia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TipoDeConvivencia::find($id)->delete();

        return Redirect::route('tipo-de-convivencias.index')
            ->with('success', 'TipoDeConvivencia deleted successfully');
    }
}
