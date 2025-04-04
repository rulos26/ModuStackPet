<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipoDocumentoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipoDocumentos = TipoDocumento::paginate();

        return view('tipo-documento.index', compact('tipoDocumentos'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoDocumentos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoDocumento = new TipoDocumento();

        return view('tipo-documento.create', compact('tipoDocumento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoDocumentoRequest $request): RedirectResponse
    {
        TipoDocumento::create($request->validated());

        return Redirect::route('tipo-documentos.index')
            ->with('success', 'TipoDocumento created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tipoDocumento = TipoDocumento::find($id);

        return view('tipo-documento.show', compact('tipoDocumento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tipoDocumento = TipoDocumento::find($id);

        return view('tipo-documento.edit', compact('tipoDocumento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoDocumentoRequest $request, TipoDocumento $tipoDocumento): RedirectResponse
    {
        $tipoDocumento->update($request->validated());

        return Redirect::route('tipo-documentos.index')
            ->with('success', 'TipoDocumento updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TipoDocumento::find($id)->delete();

        return Redirect::route('tipo-documentos.index')
            ->with('success', 'TipoDocumento deleted successfully');
    }
}
