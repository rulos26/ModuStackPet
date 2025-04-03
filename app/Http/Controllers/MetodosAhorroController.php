<?php

namespace App\Http\Controllers;

use App\Models\MetodosAhorro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MetodosAhorroRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MetodosAhorroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $metodosAhorros = MetodosAhorro::paginate();

        return view('metodos-ahorro.index', compact('metodosAhorros'))
            ->with('i', ($request->input('page', 1) - 1) * $metodosAhorros->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $metodosAhorro = new MetodosAhorro();

        return view('metodos-ahorro.create', compact('metodosAhorro'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MetodosAhorroRequest $request): RedirectResponse
    {
        MetodosAhorro::create($request->validated());

        return Redirect::route('metodos-ahorros.index')
            ->with('success', 'MetodosAhorro created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $metodosAhorro = MetodosAhorro::find($id);

        return view('metodos-ahorro.show', compact('metodosAhorro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $metodosAhorro = MetodosAhorro::find($id);

        return view('metodos-ahorro.edit', compact('metodosAhorro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MetodosAhorroRequest $request, MetodosAhorro $metodosAhorro): RedirectResponse
    {
        $metodosAhorro->update($request->validated());

        return Redirect::route('metodos-ahorros.index')
            ->with('success', 'MetodosAhorro updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        MetodosAhorro::find($id)->delete();

        return Redirect::route('metodos-ahorros.index')
            ->with('success', 'MetodosAhorro deleted successfully');
    }
}
