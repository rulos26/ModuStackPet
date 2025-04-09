<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleAssignmentController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->get();
        $roles = Role::all();
        return view('user.roles', compact('usuarios', 'roles'));
    }

    public function asignarRoles(Request $request, User $user)
    {
       //dd($user, $request);
        // Validar que los roles enviados existan
        $request->validate([
            'roles' => 'array|exists:roles,name',
        ]);

        // Verificar si el usuario tiene roles protegidos
        $rolesProtegidos = ['Superadmin', 'Admin'];
        if ($user->roles->pluck('name')->intersect($rolesProtegidos)->isNotEmpty()) {
            return redirect()->route('usuarios.roles.index')->with('error', 'No se pueden modificar los roles de un usuario con rol Superadmin o Admin.');
        }

        User::updateOrCreate(
            ['email' => $user->email], // criterio de búsqueda
            ['name' => $user->name] // datos a actualizar o crear
        )->syncRoles('Paseador');

        // Asignar los roles al usuario
       /*  try {
            $user->syncRoles($request->roles); // Reemplaza los roles existentes con los nuevos
            return redirect()->route('usuarios.roles.index')->with('success', 'Roles asignados correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.roles.index')->with('error', 'Ocurrió un error al asignar los roles.');
        } */
    }
}
