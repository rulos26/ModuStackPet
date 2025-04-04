<?php

namespace App\Http\Controllers;

use App\Models\Reclamante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ReclamanteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ReclamanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $reclamantes = Reclamante::paginate();

        return view('reclamante.index', compact('reclamantes'))
            ->with('i', ($request->input('page', 1) - 1) * $reclamantes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reclamante = new Reclamante();

        return view('reclamante.create', compact('reclamante'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReclamanteRequest $request): RedirectResponse
    {
        Reclamante::create($request->validated());

        return Redirect::route('reclamantes.index')
            ->with('success', 'Reclamante created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $reclamante = Reclamante::find($id);

        return view('reclamante.show', compact('reclamante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reclamante = Reclamante::find($id);

        return view('reclamante.edit', compact('reclamante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReclamanteRequest $request, Reclamante $reclamante): RedirectResponse
    {
        $reclamante->update($request->validated());

        return Redirect::route('reclamantes.index')
            ->with('success', 'Reclamante updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Reclamante::find($id)->delete();

        return Redirect::route('reclamantes.index')
            ->with('success', 'Reclamante deleted successfully');
    }
}
