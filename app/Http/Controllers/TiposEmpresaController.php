<?php

namespace App\Http\Controllers;

use App\Models\TipoEmpresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TiposEmpresaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TiposEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tiposEmpresas = TipoEmpresa::paginate();

        return view('tipos-empresa.index', compact('tiposEmpresas'))
            ->with('i', ($request->input('page', 1) - 1) * $tiposEmpresas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tiposEmpresa = new TipoEmpresa();

        return view('tipos-empresa.create', compact('tiposEmpresa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposEmpresaRequest $request): RedirectResponse
    {
        TipoEmpresa::create($request->validated());

        return Redirect::route('tipos-empresas.index')
            ->with('success', 'TiposEmpresa created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tiposEmpresa = TipoEmpresa::findOrFail($id);

        return view('tipos-empresa.show', compact('tiposEmpresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tiposEmpresa = TipoEmpresa::findOrFail($id);

        return view('tipos-empresa.edit', compact('tiposEmpresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposEmpresaRequest $request, TipoEmpresa $tiposEmpresa): RedirectResponse
    {
        $tiposEmpresa->update($request->validated());

        return Redirect::route('tipos-empresas.index')
            ->with('success', 'TiposEmpresa updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TipoEmpresa::findOrFail($id)->delete();

        return Redirect::route('tipos-empresas.index')
            ->with('success', 'TiposEmpresa deleted successfully');
    }
}
