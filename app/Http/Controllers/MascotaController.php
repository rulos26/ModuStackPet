<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Raza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MascotaRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class MascotaController extends Controller
{
    /**
     * Muestra una lista de todas las mascotas.
     * Solo los administradores (Superadmin y Admin) ven todas las mascotas.
     * Los demás usuarios solo ven sus propias mascotas.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Mascota::class);

        $user = auth()->user();

        // Si es administrador (Superadmin o Admin), mostrar todas las mascotas
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            $mascotas = Mascota::with(['raza', 'user.cliente'])->paginate();
        } else {
            // Si no es administrador, solo mostrar las mascotas del usuario autenticado
            $mascotas = Mascota::where('user_id', $user->id)
                ->with(['raza', 'user.cliente'])
                ->paginate();
        }

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
        $this->authorize('create', Mascota::class);

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
        $this->authorize('create', Mascota::class);

        try {
            $validatedData = $request->validated();

            // Convertir valores de radio buttons a boolean
            $validatedData['vacunas_completas'] = (bool) ($request->vacunas_completas ?? 0);
            $validatedData['esterilizado'] = (bool) ($request->esterilizado ?? 0);

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
            Log::error('Error al crear mascota', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return Redirect::route('mascotas.create')
                ->with('error', 'Ocurrió un error al crear la mascota. Intenta nuevamente.')
                ->withInput();
        }
    }

    /**
     * Muestra una mascota específica.
     *
     * @param Mascota $mascota
     * @return View
     */
    public function show(Mascota $mascota): View
    {
        $this->authorize('view', $mascota);

        $mascota->load(['raza', 'user.cliente.ciudad', 'user.cliente.barrio']);

        return view('mascota.show', compact('mascota'));
    }

    /**
     * Muestra el formulario para editar una mascota específica.
     *
     * @param Mascota $mascota
     * @return View
     */
    public function edit(Mascota $mascota): View
    {
        $this->authorize('update', $mascota);

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
        $this->authorize('update', $mascota);

        try {
            $validatedData = $request->validated();

            // Convertir valores de radio buttons a boolean
            $validatedData['vacunas_completas'] = (bool) ($request->vacunas_completas ?? 0);
            $validatedData['esterilizado'] = (bool) ($request->esterilizado ?? 0);

            // El dueño (user_id) nunca cambia al editar, ni siquiera para
            // Admin o Superadmin: se ignora cualquier user_id recibido y se
            // conserva el propietario original de la mascota.
            unset($validatedData['user_id']);
            $user = auth()->user();

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
            Log::error('Error al actualizar mascota', [
                'user_id' => auth()->id(),
                'mascota_id' => $mascota->id,
                'error' => $e->getMessage(),
            ]);

            return Redirect::route('mascotas.edit', $mascota)
                ->with('error', 'Ocurrió un error al actualizar la mascota. Intenta nuevamente.')
                ->withInput();
        }
    }

    /**
     * Elimina una mascota específica de la base de datos.
     *
     * @param Mascota $mascota
     * @return RedirectResponse
     */
    public function destroy(Mascota $mascota): RedirectResponse
    {
        $this->authorize('delete', $mascota);

        try {
            // Eliminar el avatar si existe
            if ($mascota->avatar && file_exists(public_path($mascota->avatar))) {
                unlink(public_path($mascota->avatar));
            }

            $mascota->delete();

            return Redirect::route('mascotas.index')
                ->with('success', 'Mascota eliminada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar mascota', [
                'user_id' => auth()->id(),
                'mascota_id' => $mascota->id,
                'error' => $e->getMessage(),
            ]);

            return Redirect::route('mascotas.index')
                ->with('error', 'Ocurrió un error al eliminar la mascota. Intenta nuevamente.');
        }
    }
}
