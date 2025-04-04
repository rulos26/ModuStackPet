<?php

namespace App\Http\Controllers;

use App\Models\Amparo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AmparoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AmparoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $amparos = Amparo::paginate();

        return view('amparo.index', compact('amparos'))
            ->with('i', ($request->input('page', 1) - 1) * $amparos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $amparo = new Amparo();

        return view('amparo.create', compact('amparo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AmparoRequest $request): RedirectResponse
    {
        Amparo::create($request->validated());

        return Redirect::route('amparos.index')
            ->with('success', 'Amparo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $amparo = Amparo::find($id);

        return view('amparo.show', compact('amparo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $amparo = Amparo::find($id);

        return view('amparo.edit', compact('amparo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AmparoRequest $request, Amparo $amparo): RedirectResponse
    {
        $amparo->update($request->validated());

        return Redirect::route('amparos.index')
            ->with('success', 'Amparo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Amparo::find($id)->delete();

        return Redirect::route('amparos.index')
            ->with('success', 'Amparo deleted successfully');
    }
}
