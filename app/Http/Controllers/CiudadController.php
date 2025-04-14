<?php

namespace App\Http\Controllers;

use App\Models\Ciudade;
use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CiudadeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Controlador para gestionar las ciudades
 *
 * Maneja todas las operaciones CRUD relacionadas con las ciudades
 */
class CiudadController extends Controller
{
    /**
     * Muestra el listado de ciudades
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $ciudades = Ciudade::with('departamento')
            ->orderBy('municipio')
            ->paginate();

        return view('ciudade.index', compact('ciudades'))
            ->with('i', ($request->input('page', 1) - 1) * $ciudades->perPage());
    }

    /**
     * Muestra el formulario para crear una nueva ciudad
     *
     * @return View
     */
    public function create(): View
    {
        $ciudad = new Ciudade();
        $departamentos = Departamento::orderBy('nombre')->pluck('nombre', 'id');

        return view('ciudade.create', compact('ciudad', 'departamentos'));
    }

    /**
     * Almacena una nueva ciudad en la base de datos
     *
     * @param CiudadeRequest $request
     * @return RedirectResponse
     */
    public function store(CiudadeRequest $request): RedirectResponse
    {
        Ciudade::create($request->validated());

        return Redirect::route('ciudades.index')
            ->with('success', 'Municipio creado exitosamente.');
    }

    /**
     * Muestra los detalles de una ciudad específica
     *
     * @param int $id
     * @return View
     */
    public function show($id): View
    {
        $ciudad = Ciudade::with('departamento')->findOrFail($id);

        return view('ciudade.show', compact('ciudad'));
    }

    /**
     * Muestra el formulario para editar una ciudad
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $ciudad = Ciudade::findOrFail($id);
        $departamentos = Departamento::orderBy('nombre')->pluck('nombre', 'id');

        return view('ciudade.edit', compact('ciudad', 'departamentos'));
    }

    /**
     * Actualiza una ciudad en la base de datos
     *
     * @param CiudadeRequest $request
     * @param Ciudade $ciudad
     * @return RedirectResponse
     */
    public function update(CiudadeRequest $request, Ciudade $ciudad): RedirectResponse
    {
        $ciudad->update($request->validated());

        return Redirect::route('ciudades.index')
            ->with('success', 'Municipio actualizado exitosamente');
    }

    /**
     * Elimina una ciudad de la base de datos
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $ciudad = Ciudade::findOrFail($id);

            // Verificar si la ciudad está activa
            if ($ciudad->estado == 1) {
                return Redirect::route('ciudades.index')
                    ->with('error', 'No se puede eliminar un municipio activo. Primero debe desactivarlo.');
            }

            $ciudad->delete();

            return Redirect::route('ciudades.index')
                ->with('success', 'Municipio eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            return Redirect::route('ciudades.index')
                ->with('error', 'El municipio que intenta eliminar no existe.');
        } catch (Exception $e) {
            return Redirect::route('ciudades.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el municipio.');
        }
    }

    /**
     * Cambia el estado de una ciudad (activo/inactivo)
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function toggleStatus($id): RedirectResponse
    {
        try {
            $ciudad = Ciudade::findOrFail($id);
            $ciudad->estado = $ciudad->estado == 1 ? 0 : 1;
            $ciudad->save();

            $status = $ciudad->estado == 1 ? 'activado' : 'desactivado';
            return Redirect::route('ciudades.index')
                ->with('success', "Municipio {$status} exitosamente");
        } catch (ModelNotFoundException $e) {
            return Redirect::route('ciudades.index')
                ->with('error', 'El municipio no existe.');
        } catch (Exception $e) {
            return Redirect::route('ciudades.index')
                ->with('error', 'Ocurrió un error al cambiar el estado del municipio.');
        }
    }
}
