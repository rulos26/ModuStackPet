<?php

namespace App\Http\Controllers;

use App\Models\Raza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RazaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RazaController extends Controller
{
    /**
     * Muestra una lista de todas las razas.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $razas = Raza::paginate();

        return view('raza.index', compact('razas'))
            ->with('i', ($request->input('page', 1) - 1) * $razas->perPage());
    }

    /**
     * Muestra el formulario para crear una nueva raza.
     *
     * @return View
     */
    public function create(): View
    {
        $raza = new Raza();

        return view('raza.create', compact('raza'));
    }

    /**
     * Almacena una nueva raza en la base de datos.
     *
     * @param RazaRequest $request
     * @return RedirectResponse
     */
    public function store(RazaRequest $request): RedirectResponse
    {
        Raza::create($request->validated());

        return Redirect::route('razas.index')
            ->with('success', 'Raza creada exitosamente.');
    }

    /**
     * Muestra una raza específica.
     *
     * @param int $id
     * @return View
     */
    public function show($id): View
    {
        $raza = Raza::find($id);

        return view('raza.show', compact('raza'));
    }

    /**
     * Muestra el formulario para editar una raza específica.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $raza = Raza::find($id);

        return view('raza.edit', compact('raza'));
    }

    /**
     * Actualiza una raza específica en la base de datos.
     *
     * @param RazaRequest $request
     * @param Raza $raza
     * @return RedirectResponse
     */
    public function update(RazaRequest $request, Raza $raza): RedirectResponse
    {
        $raza->update($request->validated());

        return Redirect::route('razas.index')
            ->with('success', 'Raza actualizada exitosamente');
    }

    /**
     * Elimina una raza específica de la base de datos.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        Raza::find($id)->delete();

        return Redirect::route('razas.index')
            ->with('success', 'Raza eliminada exitosamente');
    }
}
