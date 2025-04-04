<?php

namespace App\Http\Controllers;

use App\Models\PersonasAfiliada;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PersonasAfiliadaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PersonasAfiliadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $personasAfiliadas = PersonasAfiliada::paginate();

        return view('personas-afiliada.index', compact('personasAfiliadas'))
            ->with('i', ($request->input('page', 1) - 1) * $personasAfiliadas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $personasAfiliada = new PersonasAfiliada();

        return view('personas-afiliada.create', compact('personasAfiliada'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonasAfiliadaRequest $request): RedirectResponse
    {
        PersonasAfiliada::create($request->validated());

        return Redirect::route('personas-afiliadas.index')
            ->with('success', 'PersonasAfiliada created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $personasAfiliada = PersonasAfiliada::find($id);

        return view('personas-afiliada.show', compact('personasAfiliada'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $personasAfiliada = PersonasAfiliada::find($id);

        return view('personas-afiliada.edit', compact('personasAfiliada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonasAfiliadaRequest $request, PersonasAfiliada $personasAfiliada): RedirectResponse
    {
        $personasAfiliada->update($request->validated());

        return Redirect::route('personas-afiliadas.index')
            ->with('success', 'PersonasAfiliada updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        PersonasAfiliada::find($id)->delete();

        return Redirect::route('personas-afiliadas.index')
            ->with('success', 'PersonasAfiliada deleted successfully');
    }
}
