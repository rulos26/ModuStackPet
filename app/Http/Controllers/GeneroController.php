<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\GeneroRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class GeneroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $generos = Genero::paginate();

        return view('genero.index', compact('generos'))
            ->with('i', ($request->input('page', 1) - 1) * $generos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $genero = new Genero();

        return view('genero.create', compact('genero'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GeneroRequest $request): RedirectResponse
    {
        Genero::create($request->validated());

        return Redirect::route('generos.index')
            ->with('success', 'Genero created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $genero = Genero::find($id);

        return view('genero.show', compact('genero'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $genero = Genero::find($id);

        return view('genero.edit', compact('genero'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GeneroRequest $request, Genero $genero): RedirectResponse
    {
        $genero->update($request->validated());

        return Redirect::route('generos.index')
            ->with('success', 'Genero updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Genero::find($id)->delete();

        return Redirect::route('generos.index')
            ->with('success', 'Genero deleted successfully');
    }
}
