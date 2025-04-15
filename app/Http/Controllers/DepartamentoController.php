<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DepartamentoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Controlador para la gestión de Departamentos
 */
class DepartamentoController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Muestra un listado de los departamentos.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $departamentos = Departamento::ordenarPorNombre()->get();
        return view('departamento.index', compact('departamentos'));
    }

    /**
     * Muestra el formulario para crear un nuevo departamento.
     *
     * @return View
     */
    public function create(): View
    {
        $departamento = new Departamento();
        return view('departamento.create', compact('departamento'));
    }

    /**
     * Almacena un nuevo departamento en la base de datos.
     *
     * @param DepartamentoRequest $request
     * @return RedirectResponse
     */
    public function store(DepartamentoRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['estado'] = $request->has('estado') ? 1 : 0;

            Departamento::create($data);
            return Redirect::route('departamentos.index')
                ->with('success', 'Departamento creado exitosamente.');
        } catch (\Exception $e) {
            return Redirect::back()
                ->withInput()
                ->with('error', 'Error al crear el departamento: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un departamento específico.
     *
     * @param int $id_departamento
     * @return View
     */
    public function show($id_departamento): View
    {
        $departamento = Departamento::findOrFail($id_departamento);
        return view('departamento.show', compact('departamento'));
    }

    /**
     * Muestra el formulario para editar un departamento.
     *
     * @param int $id_departamento
     * @return View
     */
    public function edit($id_departamento): View
    {
        $departamento = Departamento::findOrFail($id_departamento);
        return view('departamento.edit', compact('departamento'));
    }

    /**
     * Actualiza un departamento específico en la base de datos.
     *
     * @param DepartamentoRequest $request
     * @param int $id_departamento
     * @return RedirectResponse
     */
    public function update(DepartamentoRequest $request, $id_departamento): RedirectResponse
    {
        try {
            $departamento = Departamento::findOrFail($id_departamento);
            $data = $request->validated();
            $data['estado'] = $request->has('estado') ? 1 : 0;

            $departamento->update($data);
            return Redirect::route('departamentos.index')
                ->with('success', 'Departamento actualizado exitosamente');
        } catch (\Exception $e) {
            return Redirect::back()
                ->withInput()
                ->with('error', 'Error al actualizar el departamento: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un departamento específico.
     *
     * @param int $id_departamento
     * @return RedirectResponse
     */
    public function destroy($id_departamento): RedirectResponse
    {
        try {
            $departamento = Departamento::findOrFail($id_departamento);
            $departamento->delete();
            return Redirect::route('departamentos.index')
                ->with('success', 'Departamento eliminado exitosamente');
        } catch (\Exception $e) {
            return Redirect::back()
                ->with('error', 'Error al eliminar el departamento: ' . $e->getMessage());
        }
    }

    /**
     * Cambia el estado de un departamento.
     *
     * @param int $id_departamento
     * @return RedirectResponse
     */
    public function toggleStatus($id_departamento): RedirectResponse
    {
        try {
            $departamento = Departamento::findOrFail($id_departamento);
            $departamento->estado = $departamento->estado == 1 ? 0 : 1;
            $departamento->save();

            return Redirect::route('departamentos.index')
                ->with('success', 'Estado del departamento actualizado exitosamente');
        } catch (\Exception $e) {
            return Redirect::back()
                ->with('error', 'Error al cambiar el estado del departamento: ' . $e->getMessage());
        }
    }
}
