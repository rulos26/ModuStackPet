<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstadosPedidoRequest;
use App\Models\EstadosPedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstadosPedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $estadosPedidos = EstadosPedido::paginate();
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-pedido.index', compact('estadosPedidos', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * $estadosPedidos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $estadosPedido = new EstadosPedido;
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-pedido.create', compact('estadosPedido', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstadosPedidoRequest $request): RedirectResponse
    {
        EstadosPedido::create($request->validated());
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return Redirect::route('estados-pedidos.index')
            ->with('success', 'EstadosPedido created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $estadosPedido = EstadosPedido::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-pedido.show', compact('estadosPedido', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $estadosPedido = EstadosPedido::find($id);
        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        return view('estados-pedido.edit', compact('estadosPedido', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstadosPedidoRequest $request, EstadosPedido $estadosPedido): RedirectResponse
    {
        $estadosPedido->update($request->validated());

        return Redirect::route('estados-pedidos.index')
            ->with('success', 'EstadosPedido updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EstadosPedido::find($id)->delete();

        return Redirect::route('estados-pedidos.index')
            ->with('success', 'EstadosPedido deleted successfully');
    }
}
