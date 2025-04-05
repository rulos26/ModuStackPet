<?php

namespace App\Http\Controllers;

use App\Http\Requests\PruebaRequest;
use App\Models\Prueba;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $pruebas = Prueba::paginate();
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('prueba.index', compact('pruebas', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * $pruebas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $prueba = new Prueba;
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('prueba.create', compact('prueba', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PruebaRequest $request): RedirectResponse
    {
        Prueba::create($request->validated());

        return Redirect::route('pruebas.index')
            ->with('success', 'Prueba created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $prueba = Prueba::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('prueba.show', compact('prueba', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $prueba = Prueba::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('prueba.edit', compact('prueba', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PruebaRequest $request, Prueba $prueba): RedirectResponse
    {
        $prueba->update($request->validated());

        return Redirect::route('pruebas.index')
            ->with('success', 'Prueba updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Prueba::find($id)->delete();

        return Redirect::route('pruebas.index')
            ->with('success', 'Prueba deleted successfully');
    }
}
