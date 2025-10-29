<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        Log::info('LoginController: Mostrando formulario de login');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('LoginController: Inicio de proceso de login', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Validar credenciales
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        Log::info('LoginController: Credenciales validadas', [
            'email' => $credentials['email']
        ]);

        // Intentar autenticación
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            Log::info('LoginController: Autenticación exitosa', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray()
            ]);

            // Verificar si el usuario está activo
            if (isset($user->activo) && !$user->activo) {
                Auth::logout();
                Log::warning('LoginController: Usuario inactivo intentó iniciar sesión', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ])->withInput($request->only('email'));
            }

            // Redireccionar según el rol del usuario
            $redirectUrl = null;
            if ($user->hasRole('Superadmin')) {
                $redirectUrl = route('superadmin.dashboard');
                Log::info('LoginController: Redirigiendo a Superadmin dashboard');
            } elseif ($user->hasRole('Admin')) {
                $redirectUrl = route('admin.dashboard');
                Log::info('LoginController: Redirigiendo a Admin dashboard');
            } elseif ($user->hasRole('Cliente')) {
                $redirectUrl = route('cliente.dashboard');
                Log::info('LoginController: Redirigiendo a Cliente dashboard');
            } elseif ($user->hasRole('Paseador')) {
                $redirectUrl = route('paseador.dashboard');
                Log::info('LoginController: Redirigiendo a Paseador dashboard');
            } else {
                $redirectUrl = route('temp.index');
                Log::warning('LoginController: Usuario sin rol asignado, redirigiendo a dashboard temporal', [
                    'user_id' => $user->id
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        // Si llegamos aquí, la autenticación falló
        Log::warning('LoginController: Autenticación fallida', [
            'email' => $credentials['email'],
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Log::info('LoginController: Usuario cerrando sesión', [
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('LoginController: Sesión cerrada correctamente');

        return redirect('/');
    }
}
