<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Module;
use App\Models\ModuleLog;

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
        // Validar que los roles enviados existan
        $request->validate([
            'roles' => 'array|exists:roles,name',
        ]);

        // Verificar si el usuario tiene roles protegidos
        $rolesProtegidos = ['Superadmin', 'Admin'];
        if ($user->roles->pluck('name')->intersect($rolesProtegidos)->isNotEmpty()) {
            return redirect()->route('usuarios.roles.index')->with('error', 'No se pueden modificar los roles de un usuario con rol Superadmin o Admin.');
        }

        try {
            $user->syncRoles($request->rol); // Asignar los roles al usuario

            // Registrar auditoría permission_changed
            $usuariosModule = Module::where('slug', 'usuarios')->first();
            if ($usuariosModule) {
                ModuleLog::createLog(
                    auth()->id() ?? 0,
                    $usuariosModule->id,
                    ModuleLog::ACTION_PERMISSION_CHANGED,
                    $request->ip(),
                    $request->userAgent()
                );
            }

            return redirect()->route('usuarios.roles.index')->with('success', 'Roles asignados correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.roles.index')->with('error', 'Ocurrió un error al asignar los roles.');
        }
    }
}
