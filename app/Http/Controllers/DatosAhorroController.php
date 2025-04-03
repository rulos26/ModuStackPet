<?php

namespace App\Http\Controllers;

use App\Models\DatosAhorro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DatosAhorroRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DatosAhorroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $datosAhorros = DatosAhorro::paginate();

        return view('datos-ahorro.index', compact('datosAhorros'))
            ->with('i', ($request->input('page', 1) - 1) * $datosAhorros->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $datosAhorro = new DatosAhorro();

        return view('datos-ahorro.create', compact('datosAhorro'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DatosAhorroRequest $request): RedirectResponse
    {
        DatosAhorro::create($request->validated());

        return Redirect::route('datos-ahorros.index')
            ->with('success', 'DatosAhorro created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $datosAhorro = DatosAhorro::find($id);

        return view('datos-ahorro.show', compact('datosAhorro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $datosAhorro = DatosAhorro::find($id);

        return view('datos-ahorro.edit', compact('datosAhorro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DatosAhorroRequest $request, DatosAhorro $datosAhorro): RedirectResponse
    {
        $datosAhorro->update($request->validated());

        return Redirect::route('datos-ahorros.index')
            ->with('success', 'DatosAhorro updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DatosAhorro::find($id)->delete();

        return Redirect::route('datos-ahorros.index')
            ->with('success', 'DatosAhorro deleted successfully');
    }
}
