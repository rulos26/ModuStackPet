<?php

namespace App\Http\Controllers;

use App\Models\TiposDePropiedade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TiposDePropiedadeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TiposDePropiedadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tiposDePropiedades = TiposDePropiedade::paginate();

        return view('tipos-de-propiedade.index', compact('tiposDePropiedades'))
            ->with('i', ($request->input('page', 1) - 1) * $tiposDePropiedades->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tiposDePropiedade = new TiposDePropiedade();

        return view('tipos-de-propiedade.create', compact('tiposDePropiedade'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TiposDePropiedadeRequest $request): RedirectResponse
    {
        TiposDePropiedade::create($request->validated());

        return Redirect::route('tipos-de-propiedades.index')
            ->with('success', 'TiposDePropiedade created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tiposDePropiedade = TiposDePropiedade::find($id);

        return view('tipos-de-propiedade.show', compact('tiposDePropiedade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tiposDePropiedade = TiposDePropiedade::find($id);

        return view('tipos-de-propiedade.edit', compact('tiposDePropiedade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TiposDePropiedadeRequest $request, TiposDePropiedade $tiposDePropiedade): RedirectResponse
    {
        $tiposDePropiedade->update($request->validated());

        return Redirect::route('tipos-de-propiedades.index')
            ->with('success', 'TiposDePropiedade updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TiposDePropiedade::find($id)->delete();

        return Redirect::route('tipos-de-propiedades.index')
            ->with('success', 'TiposDePropiedade deleted successfully');
    }
}
