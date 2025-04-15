<?php

namespace App\Http\Controllers;

use App\Models\Sectore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SectoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SectoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sectores = Sectore::paginate();

        return view('sectore.index', compact('sectores'))
            ->with('i', ($request->input('page', 1) - 1) * $sectores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sectore = new Sectore();

        return view('sectore.create', compact('sectore'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectoreRequest $request): RedirectResponse
    {
        Sectore::create($request->validated());

        return Redirect::route('sectores.index')
            ->with('success', 'Sectore created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $sectore = Sectore::find($id);

        return view('sectore.show', compact('sectore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $sectore = Sectore::find($id);

        return view('sectore.edit', compact('sectore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectoreRequest $request, Sectore $sectore): RedirectResponse
    {
        $sectore->update($request->validated());

        return Redirect::route('sectores.index')
            ->with('success', 'Sectore updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Sectore::find($id)->delete();

        return Redirect::route('sectores.index')
            ->with('success', 'Sectore deleted successfully');
    }
}
