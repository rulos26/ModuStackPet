<?php

namespace App\Http\Controllers;

use App\Models\TiposAfiliacione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TiposAfiliacioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TiposAfiliacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tiposAfiliaciones = TiposAfiliacione::paginate();

        return view('tipos-afiliacione.index', compact('tiposAfiliaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $tiposAfiliaciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tiposAfiliacione = new TiposAfiliacione();

        return view('tipos-afiliacione.create', compact('tiposAfiliacione'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposAfiliacioneRequest $request): RedirectResponse
    {
        TiposAfiliacione::create($request->validated());

        return Redirect::route('tipos-afiliaciones.index')
            ->with('success', 'TiposAfiliacione created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tiposAfiliacione = TiposAfiliacione::find($id);

        return view('tipos-afiliacione.show', compact('tiposAfiliacione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tiposAfiliacione = TiposAfiliacione::find($id);

        return view('tipos-afiliacione.edit', compact('tiposAfiliacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposAfiliacioneRequest $request, TiposAfiliacione $tiposAfiliacione): RedirectResponse
    {
        $tiposAfiliacione->update($request->validated());

        return Redirect::route('tipos-afiliaciones.index')
            ->with('success', 'TiposAfiliacione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TiposAfiliacione::find($id)->delete();

        return Redirect::route('tipos-afiliaciones.index')
            ->with('success', 'TiposAfiliacione deleted successfully');
    }
}
