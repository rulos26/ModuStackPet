<?php

namespace App\Http\Controllers;

use App\Models\CoberturasSalud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CoberturasSaludRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CoberturasSaludController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $coberturasSaluds = CoberturasSalud::paginate();

        return view('coberturas-salud.index', compact('coberturasSaluds'))
            ->with('i', ($request->input('page', 1) - 1) * $coberturasSaluds->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $coberturasSalud = new CoberturasSalud();

        return view('coberturas-salud.create', compact('coberturasSalud'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CoberturasSaludRequest $request): RedirectResponse
    {
        CoberturasSalud::create($request->validated());

        return Redirect::route('coberturas-saluds.index')
            ->with('success', 'CoberturasSalud created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $coberturasSalud = CoberturasSalud::find($id);

        return view('coberturas-salud.show', compact('coberturasSalud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $coberturasSalud = CoberturasSalud::find($id);

        return view('coberturas-salud.edit', compact('coberturasSalud'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CoberturasSaludRequest $request, CoberturasSalud $coberturasSalud): RedirectResponse
    {
        $coberturasSalud->update($request->validated());

        return Redirect::route('coberturas-saluds.index')
            ->with('success', 'CoberturasSalud updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CoberturasSalud::find($id)->delete();

        return Redirect::route('coberturas-saluds.index')
            ->with('success', 'CoberturasSalud deleted successfully');
    }
}
