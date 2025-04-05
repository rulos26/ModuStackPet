<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $pedidos = Pedido::paginate();
        $user = Auth::user(); // Equivalente a auth()->user()
        if (! $user) {
            return redirect('/'); // Redirige a la raíz
        }
        $roles = $user->roles->pluck('name');

        return view('pedido.index', compact('pedidos', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * $pedidos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pedido = new Pedido;
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('pedido.create', compact('pedido', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PedidoRequest $request): RedirectResponse
    {
        Pedido::create($request->validated());
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return Redirect::route('pedidos.index')
            ->with('success', 'Pedido created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $pedido = Pedido::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('pedido.show', compact('pedido', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pedido = Pedido::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('pedido.edit', compact('pedido', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PedidoRequest $request, Pedido $pedido): RedirectResponse
    {
        $pedido->update($request->validated());

        return Redirect::route('pedidos.index')
            ->with('success', 'Pedido updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Pedido::find($id)->delete();

        return Redirect::route('pedidos.index')
            ->with('success', 'Pedido deleted successfully');
    }
}
