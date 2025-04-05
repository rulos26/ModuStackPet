<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstadosDeudaRequest;
use App\Models\EstadosDeuda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstadosDeudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $estadosDeudas = EstadosDeuda::paginate();
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-deuda.index', compact('estadosDeudas', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * $estadosDeudas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $estadosDeuda = new EstadosDeuda;
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-deuda.create', compact('estadosDeuda', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstadosDeudaRequest $request): RedirectResponse
    {
        EstadosDeuda::create($request->validated());

        return Redirect::route('estados-deudas.index')
            ->with('success', 'EstadosDeuda created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $estadosDeuda = EstadosDeuda::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-deuda.show', compact('estadosDeuda', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $estadosDeuda = EstadosDeuda::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-deuda.edit', compact('estadosDeuda', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstadosDeudaRequest $request, EstadosDeuda $estadosDeuda): RedirectResponse
    {
        $estadosDeuda->update($request->validated());

        return Redirect::route('estados-deudas.index')
            ->with('success', 'EstadosDeuda updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EstadosDeuda::find($id)->delete();

        return Redirect::route('estados-deudas.index')
            ->with('success', 'EstadosDeuda deleted successfully');
    }
}
