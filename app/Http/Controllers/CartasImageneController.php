<?php

namespace App\Http\Controllers;

use App\Models\CartasImagene;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CartasImageneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CartasImageneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cartasImagenes = CartasImagene::paginate();

        return view('cartas-imagene.index', compact('cartasImagenes'))
            ->with('i', ($request->input('page', 1) - 1) * $cartasImagenes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cartasImagene = new CartasImagene();

        return view('cartas-imagene.create', compact('cartasImagene'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartasImageneRequest $request): RedirectResponse
    {
        CartasImagene::create($request->validated());

        return Redirect::route('cartas-imagenes.index')
            ->with('success', 'CartasImagene created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cartasImagene = CartasImagene::find($id);

        return view('cartas-imagene.show', compact('cartasImagene'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cartasImagene = CartasImagene::find($id);

        return view('cartas-imagene.edit', compact('cartasImagene'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartasImageneRequest $request, CartasImagene $cartasImagene): RedirectResponse
    {
        $cartasImagene->update($request->validated());

        return Redirect::route('cartas-imagenes.index')
            ->with('success', 'CartasImagene updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CartasImagene::find($id)->delete();

        return Redirect::route('cartas-imagenes.index')
            ->with('success', 'CartasImagene deleted successfully');
    }
}
