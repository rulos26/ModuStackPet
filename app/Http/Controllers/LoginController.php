<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotificacionSimple;
class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Retorna la vista del formulario de login
    }

    /**
     * Maneja el inicio de sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email', // El campo email es obligatorio y debe ser un correo válido
            'password' => 'required',    // El campo password es obligatorio
        ]);

        // Intentar autenticar al usuario con las credenciales proporcionadas
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Autenticación exitosa
            $user = Auth::user(); // Obtener el usuario autenticado
            $roles = $user->roles->pluck('name'); // Obtener los roles del usuario
//dd  ($roles);
            // Verificar los roles del usuario y redirigir al dashboard correspondiente
            if ($roles->contains('Superadmin')) {
                return redirect()->route('superadmin.dashboard'); // Redirigir al dashboard de Superadmin
                $user->notify(new NotificacionSimple());
                
            }

            if ($roles->contains('Admin')) {
                $user->notify(new NotificacionSimple());
                return redirect()->route('admin.dashboard'); // Redirigir al dashboard de Admin
            }

            if ($roles->contains('Cliente')) {
                $user->notify(new NotificacionSimple());
                return redirect()->route('cliente.dashboard'); // Redirigir al dashboard de Cliente
            }

            // Si el usuario no tiene un rol válido
            Auth::logout(); // Cerrar sesión
            return back()->withErrors([
                'email' => 'No tienes permisos para acceder.',
            ]);
        }

        // Si las credenciales no son válidas
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Cerrar la sesión del usuario
        return redirect('/'); // Redirigir a la página principal o de login
    }
}
