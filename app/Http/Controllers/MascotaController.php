<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Raza;
use App\Models\Barrio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MascotaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class MascotaController extends Controller
{
    /**
     * Muestra una lista de todas las mascotas.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $mascotas = Mascota::with(['raza', 'barrio'])->paginate();

        return view('mascota.index', compact('mascotas'))
            ->with('i', ($request->input('page', 1) - 1) * $mascotas->perPage());
    }

    /**
     * Muestra el formulario para crear una nueva mascota.
     *
     * @return View
     */
    public function create(): View
    {
        $mascota = new Mascota();
        $razas = Raza::all();
        $barrios = Barrio::all();
        $users = User::role('Cliente')->get();

        return view('mascota.create', compact('mascota', 'razas', 'barrios', 'users'));
    }

    /**
     * Almacena una nueva mascota en la base de datos.
     *
     * @param MascotaRequest $request
     * @return RedirectResponse
     */
    public function store(MascotaRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            // Manejar la subida de la imagen del avatar
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('mascotas/avatars', 'public');
                $validatedData['avatar'] = $avatarPath;
            }

            Mascota::create($validatedData);

            return Redirect::route('mascotas.index')
                ->with('success', 'Mascota creada exitosamente.');
        } catch (\Exception $e) {
            return Redirect::route('mascotas.create')
                ->with('error', 'Error al crear la mascota: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Muestra una mascota específica.
     *
     * @param int $id
     * @return View
     */
    public function show($id): View
    {
        $mascota = Mascota::with(['raza', 'barrio'])->find($id);

        return view('mascota.show', compact('mascota'));
    }

    /**
     * Muestra el formulario para editar una mascota específica.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $mascota = Mascota::find($id);
        $razas = Raza::all();
        $barrios = Barrio::all();
        $users = User::role('Cliente')->get();

        return view('mascota.edit', compact('mascota', 'razas', 'barrios', 'users'));
    }

    /**
     * Actualiza una mascota específica en la base de datos.
     *
     * @param MascotaRequest $request
     * @param Mascota $mascota
     * @return RedirectResponse
     */
    public function update(MascotaRequest $request, Mascota $mascota): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            // Manejar la subida de la imagen del avatar
            if ($request->hasFile('avatar')) {
                // Eliminar el avatar anterior si existe
                if ($mascota->avatar) {
                    Storage::disk('public')->delete($mascota->avatar);
                }

                $avatarPath = $request->file('avatar')->store('mascotas/avatars', 'public');
                $validatedData['avatar'] = $avatarPath;
            }

            $mascota->update($validatedData);

            return Redirect::route('mascotas.index')
                ->with('success', 'Mascota actualizada exitosamente');
        } catch (\Exception $e) {
            return Redirect::route('mascotas.edit', $mascota)
                ->with('error', 'Error al actualizar la mascota: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Elimina una mascota específica de la base de datos.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $mascota = Mascota::find($id);

            // Eliminar el avatar si existe
            if ($mascota->avatar) {
                Storage::disk('public')->delete($mascota->avatar);
            }

            $mascota->delete();

            return Redirect::route('mascotas.index')
                ->with('success', 'Mascota eliminada exitosamente');
        } catch (\Exception $e) {
            return Redirect::route('mascotas.index')
                ->with('error', 'Error al eliminar la mascota: ' . $e->getMessage());
        }
    }
}
