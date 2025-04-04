<?php

namespace App\Http\Controllers;

use App\Models\Conclusione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConclusioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ConclusioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $conclusiones = Conclusione::paginate();
       
        return view('conclusione.index', compact('conclusiones'))
            ->with('i', ($request->input('page', 1) - 1) * $conclusiones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $conclusione = new Conclusione();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('conclusione.create', compact('conclusione','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConclusioneRequest $request): RedirectResponse
    {
        Conclusione::create($request->all());

        return Redirect::route('reclamantes-afiliados.create ')
            ->with('success', 'Conclusione created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $conclusione = Conclusione::find($id);

        return view('conclusione.show', compact('conclusione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $conclusione = Conclusione::find($id);

        return view('conclusione.edit', compact('conclusione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConclusioneRequest $request, Conclusione $conclusione): RedirectResponse
    {
        $conclusione->update($request->validated());

        return Redirect::route('conclusiones.index')
            ->with('success', 'Conclusione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Conclusione::find($id)->delete();

        return Redirect::route('conclusiones.index')
            ->with('success', 'Conclusione deleted successfully');
    }
}
