<?php

namespace App\Http\Controllers;

use App\Models\EmpleosAfiliado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EmpleosAfiliadoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EmpleosAfiliadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $empleosAfiliados = EmpleosAfiliado::paginate();

        return view('empleos-afiliado.index', compact('empleosAfiliados'))
            ->with('i', ($request->input('page', 1) - 1) * $empleosAfiliados->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $empleosAfiliado = new EmpleosAfiliado();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('empleos-afiliado.create', compact('empleosAfiliado','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        EmpleosAfiliado::create($request->all());
       
        return Redirect::route('afiliados-coberturas-saluds.create')
            ->with('success', 'EmpleosAfiliado created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $empleosAfiliado = EmpleosAfiliado::find($id);

        return view('empleos-afiliado.show', compact('empleosAfiliado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $empleosAfiliado = EmpleosAfiliado::find($id);

        return view('empleos-afiliado.edit', compact('empleosAfiliado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmpleosAfiliadoRequest $request, EmpleosAfiliado $empleosAfiliado): RedirectResponse
    {
        $empleosAfiliado->update($request->validated());

        return Redirect::route('empleos-afiliados.index')
            ->with('success', 'EmpleosAfiliado updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EmpleosAfiliado::find($id)->delete();

        return Redirect::route('empleos-afiliados.index')
            ->with('success', 'EmpleosAfiliado deleted successfully');
    }
}
