<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redireccionar según el rol del usuario
            $user = Auth::user();
            if ($user->hasRole('Superadmin')) {
                return redirect()->intended('superadmin/dashboard');
            } elseif ($user->hasRole('Admin')) {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->hasRole('Cliente')) {
                return redirect()->intended('clientes/dashboard');
            } elseif ($user->hasRole('Paseador')) {
                return redirect()->intended('paseador/dashboard');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
