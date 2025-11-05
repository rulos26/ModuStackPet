<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Raza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MascotaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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
        $mascotas = Mascota::with(['raza', 'user'])->paginate();

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

        return view('mascota.create', compact('mascota', 'razas'));
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

            // Usar automáticamente el usuario autenticado como propietario
            $user = auth()->user();
            $validatedData['user_id'] = $user->id;

            // Manejar la subida de la imagen del avatar
            if ($request->hasFile('avatar')) {
                $avatarPath = 'avatars/' . $user->cedula . '/mascotas';
                $avatarName = $validatedData['nombre'] . '.' . $request->file('avatar')->getClientOriginalExtension();

                // Crear el directorio si no existe
                if (!file_exists(public_path($avatarPath))) {
                    mkdir(public_path($avatarPath), 0777, true);
                }

                $request->file('avatar')->move(public_path($avatarPath), $avatarName);
                $validatedData['avatar'] = $avatarPath . '/' . $avatarName;
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
        $mascota = Mascota::with(['raza', 'user.cliente.ciudad', 'user.cliente.barrio'])->find($id);

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

        return view('mascota.edit', compact('mascota', 'razas'));
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

            // Usar automáticamente el usuario autenticado como propietario
            $user = auth()->user();
            $validatedData['user_id'] = $user->id;

            // Manejar la subida de la imagen del avatar
            if ($request->hasFile('avatar')) {
                $avatarPath = 'avatars/' . $user->cedula . '/mascotas';
                $avatarName = $validatedData['nombre'] . '.' . $request->file('avatar')->getClientOriginalExtension();

                // Eliminar la imagen anterior si existe
                if ($mascota->avatar && file_exists(public_path($mascota->avatar))) {
                    unlink(public_path($mascota->avatar));
                }

                // Crear el directorio si no existe
                if (!file_exists(public_path($avatarPath))) {
                    mkdir(public_path($avatarPath), 0777, true);
                }

                $request->file('avatar')->move(public_path($avatarPath), $avatarName);
                $validatedData['avatar'] = $avatarPath . '/' . $avatarName;
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
            if ($mascota->avatar && file_exists(public_path($mascota->avatar))) {
                unlink(public_path($mascota->avatar));
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
