<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstadosCicloRequest;
use App\Models\EstadosCiclo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstadosCicloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $estadosCiclos = EstadosCiclo::paginate();
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-ciclo.index', compact('estadosCiclos', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * $estadosCiclos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $estadosCiclo = new EstadosCiclo;
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');
        $estados = EstadosCiclo::all();

        return view('estados-ciclo.create', compact('estadosCiclo', 'roles', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstadosCicloRequest $request): RedirectResponse
    {
        EstadosCiclo::create($request->validated());

        return Redirect::route('estados-ciclos.index')
            ->with('success', 'EstadosCiclo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $estadosCiclo = EstadosCiclo::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-ciclo.show', compact('estadosCiclo', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $estadosCiclo = EstadosCiclo::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-ciclo.edit', compact('estadosCiclo', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstadosCicloRequest $request, EstadosCiclo $estadosCiclo): RedirectResponse
    {
        $estadosCiclo->update($request->validated());

        return Redirect::route('estados-ciclos.index')
            ->with('success', 'EstadosCiclo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EstadosCiclo::find($id)->delete();

        return Redirect::route('estados-ciclos.index')
            ->with('success', 'EstadosCiclo deleted successfully');
    }
}
