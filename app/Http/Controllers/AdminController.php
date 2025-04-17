<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MensajeDeBienvenida;

class AdminController extends Controller
{
    public function login_Admin()
    {
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

        // Buscar el mensaje de bienvenida que coincida con el rol del usuario
        $mensajeDeBienvenida = MensajeDeBienvenida::where('rol', $roles->first())->first();

        // Verificar si se encontró un mensaje de bienvenida
        if (!$mensajeDeBienvenida) {
            return redirect()->route('dashboard')->withErrors(['message' => 'No se encontró un mensaje de bienvenida para tu rol.']);
        }

        // Retornar la vista del dashboard con los roles y el mensaje de bienvenida
        return view('admin.dashboard', [
            'roles' => $roles,
            'titulo' => $mensajeDeBienvenida->titulo,
            'descripcion' => $mensajeDeBienvenida->descripcion,
            'logo' => $mensajeDeBienvenida->logo,
        ]);
    }
}
