<?php

namespace App\Http\Controllers;

use App\Models\RegimenesSalud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegimenesSaludRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RegimenesSaludController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $regimenesSaluds = RegimenesSalud::paginate();

        return view('regimenes-salud.index', compact('regimenesSaluds'))
            ->with('i', ($request->input('page', 1) - 1) * $regimenesSaluds->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $regimenesSalud = new RegimenesSalud();

        return view('regimenes-salud.create', compact('regimenesSalud'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegimenesSaludRequest $request): RedirectResponse
    {
        RegimenesSalud::create($request->validated());

        return Redirect::route('regimenes-saluds.index')
            ->with('success', 'RegimenesSalud created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $regimenesSalud = RegimenesSalud::find($id);

        return view('regimenes-salud.show', compact('regimenesSalud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $regimenesSalud = RegimenesSalud::find($id);

        return view('regimenes-salud.edit', compact('regimenesSalud'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegimenesSaludRequest $request, RegimenesSalud $regimenesSalud): RedirectResponse
    {
        $regimenesSalud->update($request->validated());

        return Redirect::route('regimenes-saluds.index')
            ->with('success', 'RegimenesSalud updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        RegimenesSalud::find($id)->delete();

        return Redirect::route('regimenes-saluds.index')
            ->with('success', 'RegimenesSalud deleted successfully');
    }
}
