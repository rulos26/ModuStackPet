<?php

namespace App\Http\Controllers;

use App\Models\HechosOcurrencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HechosOcurrenciaRequest;
use App\Models\MotivosMuerte;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class HechosOcurrenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $hechosOcurrencias = HechosOcurrencia::paginate();

        return view('hechos-ocurrencia.index', compact('hechosOcurrencias'))
            ->with('i', ($request->input('page', 1) - 1) * $hechosOcurrencias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $hechosOcurrencia = new HechosOcurrencia();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        $motivo=MotivosMuerte::all();
        return view('hechos-ocurrencia.create', compact('hechosOcurrencia','cedula','nombre','motivo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HechosOcurrenciaRequest $request): RedirectResponse
    {
        HechosOcurrencia::create($request->validated());

        return Redirect::route('conclusiones.create')
            ->with('success', 'HechosOcurrencia created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $hechosOcurrencia = HechosOcurrencia::find($id);

        return view('hechos-ocurrencia.show', compact('hechosOcurrencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $hechosOcurrencia = HechosOcurrencia::find($id);

        return view('hechos-ocurrencia.edit', compact('hechosOcurrencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HechosOcurrenciaRequest $request, HechosOcurrencia $hechosOcurrencia): RedirectResponse
    {
        $hechosOcurrencia->update($request->validated());

        return Redirect::route('hechos-ocurrencias.index')
            ->with('success', 'HechosOcurrencia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        HechosOcurrencia::find($id)->delete();

        return Redirect::route('hechos-ocurrencias.index')
            ->with('success', 'HechosOcurrencia deleted successfully');
    }
}
