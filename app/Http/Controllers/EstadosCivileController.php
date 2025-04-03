<?php

namespace App\Http\Controllers;

use App\Models\EstadosCivile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EstadosCivileRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstadosCivileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $estadosCiviles = EstadosCivile::paginate();

        return view('estados-civile.index', compact('estadosCiviles'))
            ->with('i', ($request->input('page', 1) - 1) * $estadosCiviles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $estadosCivile = new EstadosCivile();

        return view('estados-civile.create', compact('estadosCivile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstadosCivileRequest $request): RedirectResponse
    {
        EstadosCivile::create($request->validated());

        return Redirect::route('estados-civiles.index')
            ->with('success', 'EstadosCivile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $estadosCivile = EstadosCivile::find($id);

        return view('estados-civile.show', compact('estadosCivile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $estadosCivile = EstadosCivile::find($id);

        return view('estados-civile.edit', compact('estadosCivile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstadosCivileRequest $request, EstadosCivile $estadosCivile): RedirectResponse
    {
        $estadosCivile->update($request->validated());

        return Redirect::route('estados-civiles.index')
            ->with('success', 'EstadosCivile updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EstadosCivile::find($id)->delete();

        return Redirect::route('estados-civiles.index')
            ->with('success', 'EstadosCivile deleted successfully');
    }
}
