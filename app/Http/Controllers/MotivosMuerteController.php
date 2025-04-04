<?php

namespace App\Http\Controllers;

use App\Models\MotivosMuerte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MotivosMuerteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MotivosMuerteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $motivosMuertes = MotivosMuerte::paginate();

        return view('motivos-muerte.index', compact('motivosMuertes'))
            ->with('i', ($request->input('page', 1) - 1) * $motivosMuertes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $motivosMuerte = new MotivosMuerte();

        return view('motivos-muerte.create', compact('motivosMuerte'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MotivosMuerteRequest $request): RedirectResponse
    {
        MotivosMuerte::create($request->validated());

        return Redirect::route('motivos-muertes.index')
            ->with('success', 'MotivosMuerte created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $motivosMuerte = MotivosMuerte::find($id);

        return view('motivos-muerte.show', compact('motivosMuerte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $motivosMuerte = MotivosMuerte::find($id);

        return view('motivos-muerte.edit', compact('motivosMuerte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MotivosMuerteRequest $request, MotivosMuerte $motivosMuerte): RedirectResponse
    {
        $motivosMuerte->update($request->validated());

        return Redirect::route('motivos-muertes.index')
            ->with('success', 'MotivosMuerte updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        MotivosMuerte::find($id)->delete();

        return Redirect::route('motivos-muertes.index')
            ->with('success', 'MotivosMuerte deleted successfully');
    }
}
