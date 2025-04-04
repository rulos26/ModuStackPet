<?php

namespace App\Http\Controllers;

use App\Models\Paise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PaiseRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PaiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $paises = Paise::paginate();

        return view('paise.index', compact('paises'))
            ->with('i', ($request->input('page', 1) - 1) * $paises->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paise = new Paise();

        return view('paise.create', compact('paise'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaiseRequest $request): RedirectResponse
    {
        Paise::create($request->validated());

        return Redirect::route('paises.index')
            ->with('success', 'Paise created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $paise = Paise::find($id);

        return view('paise.show', compact('paise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paise = Paise::find($id);

        return view('paise.edit', compact('paise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaiseRequest $request, Paise $paise): RedirectResponse
    {
        $paise->update($request->validated());

        return Redirect::route('paises.index')
            ->with('success', 'Paise updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Paise::find($id)->delete();

        return Redirect::route('paises.index')
            ->with('success', 'Paise deleted successfully');
    }
}
