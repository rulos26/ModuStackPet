<?php

namespace App\Http\Controllers;

use App\Http\Requests\CicloRequest;
use App\Models\Ciclo;
use App\Models\EstadosCiclo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CicloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $ciclos = Ciclo::with('estadoCiclo')->paginate();
        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        return view('ciclo.index', compact('ciclos', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * $ciclos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ciclo = new Ciclo;
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');
        $estados = EstadosCiclo::all();

        return view('ciclo.create', compact('ciclo', 'roles', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CicloRequest $request): RedirectResponse
    {
        Ciclo::create($request->validated());

        return Redirect::route('ciclos.index')
            ->with('success', 'Ciclo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $ciclo = Ciclo::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('ciclo.show', compact('ciclo', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ciclo = Ciclo::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');
        $estados = EstadosCiclo::all();

        return view('ciclo.edit', compact('ciclo', 'roles', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CicloRequest $request, Ciclo $ciclo): RedirectResponse
    {
        $ciclo->update($request->validated());

        return Redirect::route('ciclos.index')
            ->with('success', 'Ciclo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Ciclo::find($id)->delete();

        return Redirect::route('ciclos.index')
            ->with('success', 'Ciclo deleted successfully');
    }
}
