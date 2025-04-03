<?php

namespace App\Http\Controllers;

use App\Models\PorcentajesAhorro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PorcentajesAhorroRequest;
use App\Models\MetodosAhorro;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PorcentajesAhorroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $porcentajesAhorros = PorcentajesAhorro::paginate();

        return view('porcentajes-ahorro.index', compact('porcentajesAhorros'))
            ->with('i', ($request->input('page', 1) - 1) * $porcentajesAhorros->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $porcentajesAhorro = new PorcentajesAhorro();
        $metodos=MetodosAhorro::all();
        //dd($metodos);
        return view('porcentajes-ahorro.create', compact('porcentajesAhorro','metodos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PorcentajesAhorroRequest $request): RedirectResponse
    {
        PorcentajesAhorro::create($request->validated());

        return Redirect::route('porcentajes-ahorros.index')
            ->with('success', 'PorcentajesAhorro created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $porcentajesAhorro = PorcentajesAhorro::find($id);

        return view('porcentajes-ahorro.show', compact('porcentajesAhorro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $porcentajesAhorro = PorcentajesAhorro::find($id);
        $metodos=MetodosAhorro::all();
        return view('porcentajes-ahorro.edit', compact('porcentajesAhorro','metodos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PorcentajesAhorroRequest $request, PorcentajesAhorro $porcentajesAhorro): RedirectResponse
    {
        $porcentajesAhorro->update($request->validated());

        return Redirect::route('porcentajes-ahorros.index')
            ->with('success', 'PorcentajesAhorro updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        PorcentajesAhorro::find($id)->delete();

        return Redirect::route('porcentajes-ahorros.index')
            ->with('success', 'PorcentajesAhorro deleted successfully');
    }
}
