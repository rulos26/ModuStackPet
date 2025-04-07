<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Mostrar una lista de los recursos.
     */
    public function index(Request $request): View
    {
        $users = User::paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create(): View
    {
        $user = new User();

        return view('user.create', compact('user'));
    }

    /**
     * Almacenar un recurso recién creado en el almacenamiento.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit($id): View
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Actualizar el recurso especificado en el almacenamiento.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
