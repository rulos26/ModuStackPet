<?php

namespace App\Http\Controllers;

use App\Models\Verificacione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\VerificacioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class VerificacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $verificaciones = Verificacione::paginate();

        return view('verificacione.index', compact('verificaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $verificaciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $verificacione = new Verificacione();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('verificacione.create', compact('verificacione','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VerificacioneRequest $request): RedirectResponse
    {
        Verificacione::create($request->validated());

        return Redirect::route('imagenes.index')
            ->with('success', 'Verificacione created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $verificacione = Verificacione::find($id);

        return view('verificacione.show', compact('verificacione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $verificacione = Verificacione::find($id);

        return view('verificacione.edit', compact('verificacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VerificacioneRequest $request, Verificacione $verificacione): RedirectResponse
    {
        $verificacione->update($request->validated());

        return Redirect::route('verificaciones.index')
            ->with('success', 'Verificacione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Verificacione::find($id)->delete();

        return Redirect::route('verificaciones.index')
            ->with('success', 'Verificacione deleted successfully');
    }
}
