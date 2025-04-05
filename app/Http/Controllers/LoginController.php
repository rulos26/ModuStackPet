<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Asegúrate de tener la vista para el formulario de login
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Autenticación exitosa
            $user = Auth::user();
            $roles = $user->roles->pluck('name');

            if ($roles->contains('Superadmin')) {
                return redirect()->route('superadmin.dashboard');
            } else {
                dd('error');
            }

            if ($roles->contains('Admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                dd('error');
            }

            if ($roles->contains('Cliente')) {
                return redirect()->route('cliente.dashboard');
            } else {
                dd('error');
            }

            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
            // return redirect()->intended('dashboard'); // Redirige al usuario después del inicio de sesión
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/'); // Redirigir a la página principal o de login
    }
}
