<?php

namespace App\Http\Controllers;

use App\Models\HallazgosObservacione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HallazgosObservacioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class HallazgosObservacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $hallazgosObservaciones = HallazgosObservacione::paginate();

        return view('hallazgos-observacione.index', compact('hallazgosObservaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $hallazgosObservaciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $hallazgosObservacione = new HallazgosObservacione();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        return view('hallazgos-observacione.create', compact('hallazgosObservacione','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HallazgosObservacioneRequest $request): RedirectResponse
    {
        HallazgosObservacione::create($request->validated());

        return Redirect::route('siniestros.create')
            ->with('success', 'HallazgosObservacione created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $hallazgosObservacione = HallazgosObservacione::find($id);

        return view('hallazgos-observacione.show', compact('hallazgosObservacione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $hallazgosObservacione = HallazgosObservacione::find($id);

        return view('hallazgos-observacione.edit', compact('hallazgosObservacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HallazgosObservacioneRequest $request, HallazgosObservacione $hallazgosObservacione): RedirectResponse
    {
        $hallazgosObservacione->update($request->validated());

        return Redirect::route('hallazgos-observaciones.index')
            ->with('success', 'HallazgosObservacione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        HallazgosObservacione::find($id)->delete();

        return Redirect::route('hallazgos-observaciones.index')
            ->with('success', 'HallazgosObservacione deleted successfully');
    }
}
