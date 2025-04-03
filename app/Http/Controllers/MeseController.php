<?php

namespace App\Http\Controllers;

use App\Models\Mese;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MeseRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MeseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $meses = Mese::paginate();

        return view('mese.index', compact('meses'))
            ->with('i', ($request->input('page', 1) - 1) * $meses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $mese = new Mese();

        return view('mese.create', compact('mese'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeseRequest $request): RedirectResponse
    {
        Mese::create($request->validated());

        return Redirect::route('meses.index')
            ->with('success', 'Mese created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $mese = Mese::find($id);

        return view('mese.show', compact('mese'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $mese = Mese::find($id);

        return view('mese.edit', compact('mese'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeseRequest $request, Mese $mese): RedirectResponse
    {
        $mese->update($request->validated());

        return Redirect::route('meses.index')
            ->with('success', 'Mese updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Mese::find($id)->delete();

        return Redirect::route('meses.index')
            ->with('success', 'Mese deleted successfully');
    }
}
