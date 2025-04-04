<?php

namespace App\Http\Controllers;

use App\Models\CartaAutorizacione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CartaAutorizacioneRequest;
use App\Models\Aspirante;
use App\Models\Busqueda;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CartaAutorizacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cedula = session('cedula_numero');
        //dd($cedula);
        $cartaAutorizaciones = CartaAutorizacione::where('cedula', $cedula)->get();
       /*  if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda'));
        }
        //dd($cedula);
        
        if ($cartaAutorizaciones->isEmpty()) {
            $cartaAutorizacione = new CartaAutorizacione();
            $cartaAutorizacione->cedula = $cedula;
            $cartaAutorizacione->firmado = '0';
            $cartaAutorizacione->save();
        } */

        return view('carta-autorizacione.index', compact('cartaAutorizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cartaAutorizacione = new CartaAutorizacione();

        return view('carta-autorizacione.create', compact('cartaAutorizacione'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartaAutorizacioneRequest $request): RedirectResponse
    {
        CartaAutorizacione::create($request->validated());

        return Redirect::route('carta-autorizaciones.index')
            ->with('success', 'CartaAutorizacione created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cedula = session('cedula');
        /* if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda'));
        }
        $cartaAutorizacione = CartaAutorizacione::find($id);
        // Obtén los aspirantes por cédula
        $aspirantes = Aspirante::where('cedula', $cedula)->with(['ciudad', 'lugar_expedicion', 'municipio'])->First();
//dd($aspirantes); */
        return view('carta-autorizacione.cartaNew');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cartaAutorizacione = CartaAutorizacione::find($id);

        return view('carta-autorizacione.edit', compact('cartaAutorizacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartaAutorizacioneRequest $request, CartaAutorizacione $cartaAutorizacione): RedirectResponse
    {
        $cartaAutorizacione->update($request->validated());

        return Redirect::route('carta-autorizaciones.index')
            ->with('success', 'CartaAutorizacione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CartaAutorizacione::find($id)->delete();

        return Redirect::route('carta-autorizaciones.index')
            ->with('success', 'CartaAutorizacione deleted successfully');
    }

    public function cartaNew(){
        return view('carta-autorizacione.cartaNew');
    }
}
