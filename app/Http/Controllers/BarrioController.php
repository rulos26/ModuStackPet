<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BarrioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BarrioController extends Controller
{
    /**
     * Muestra una lista de todos los barrios.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $barrios = Barrio::paginate();

        return view('barrio.index', compact('barrios'))
            ->with('i', ($request->input('page', 1) - 1) * $barrios->perPage());
    }

    /**
     * Muestra el formulario para crear un nuevo barrio.
     *
     * @return View
     */
    public function create(): View
    {
        $barrio = new Barrio();

        return view('barrio.create', compact('barrio'));
    }

    /**
     * Almacena un nuevo barrio en la base de datos.
     *
     * @param BarrioRequest $request
     * @return RedirectResponse
     */
    public function store(BarrioRequest $request): RedirectResponse
    {
        Barrio::create($request->validated());

        return Redirect::route('barrios.index')
            ->with('success', 'Barrio creado exitosamente.');
    }

    /**
     * Muestra un barrio específico.
     *
     * @param int $id
     * @return View
     */
    public function show($id): View
    {
        $barrio = Barrio::find($id);

        return view('barrio.show', compact('barrio'));
    }

    /**
     * Muestra el formulario para editar un barrio específico.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $barrio = Barrio::find($id);

        return view('barrio.edit', compact('barrio'));
    }

    /**
     * Actualiza un barrio específico en la base de datos.
     *
     * @param BarrioRequest $request
     * @param Barrio $barrio
     * @return RedirectResponse
     */
    public function update(BarrioRequest $request, Barrio $barrio): RedirectResponse
    {
        $barrio->update($request->validated());

        return Redirect::route('barrios.index')
            ->with('success', 'Barrio actualizado exitosamente');
    }

    /**
     * Elimina un barrio específico de la base de datos.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        Barrio::find($id)->delete();

        return Redirect::route('barrios.index')
            ->with('success', 'Barrio eliminado exitosamente');
    }
}
