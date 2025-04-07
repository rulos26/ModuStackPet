<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    public function index()
    {
        dd('SuperadminController@index');
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'Debes iniciar sesión para acceder.']);
        }

        $user = Auth::user(); // Obtener el usuario autenticado
        $roles = $user->roles->pluck('name'); // Obtener los roles del usuario

        // Verificar si el usuario tiene al menos un rol asignado
        if ($roles->isEmpty()) {
            Auth::logout(); // Cerrar sesión
            session()->invalidate(); // Invalidar la sesión
            return redirect()->route('logout')->withErrors(['message' => 'No tienes permisos para acceder.']);
        }

        // Retornar la vista del dashboard con los roles
        return view('superadmin.dashboard', compact('roles'));
    }
}
