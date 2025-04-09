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
        $request->validate([
            'roles' => 'array|exists:roles,name',
        ]);

        $user->assignRole($request->roles); 
        $usuarios = User::with('roles')->get();
        $roles = Role::all();
        return view('user.roles', compact('usuarios', 'roles'));
    }
}
