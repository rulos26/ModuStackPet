<?php

namespace App\Http\Controllers;

use App\Models\MuertesOrigen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MuertesOrigenRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MuertesOrigenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $muertesOrigens = MuertesOrigen::paginate();

        return view('muertes-origen.index', compact('muertesOrigens'))
            ->with('i', ($request->input('page', 1) - 1) * $muertesOrigens->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $muertesOrigen = new MuertesOrigen();

        return view('muertes-origen.create', compact('muertesOrigen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MuertesOrigenRequest $request): RedirectResponse
    {
        MuertesOrigen::create($request->validated());

        return Redirect::route('muertes-origens.index')
            ->with('success', 'MuertesOrigen created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $muertesOrigen = MuertesOrigen::find($id);

        return view('muertes-origen.show', compact('muertesOrigen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $muertesOrigen = MuertesOrigen::find($id);

        return view('muertes-origen.edit', compact('muertesOrigen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MuertesOrigenRequest $request, MuertesOrigen $muertesOrigen): RedirectResponse
    {
        $muertesOrigen->update($request->validated());

        return Redirect::route('muertes-origens.index')
            ->with('success', 'MuertesOrigen updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        MuertesOrigen::find($id)->delete();

        return Redirect::route('muertes-origens.index')
            ->with('success', 'MuertesOrigen deleted successfully');
    }
}
