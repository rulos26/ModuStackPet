<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activo' => true, // Clientes activos por defecto
        ]);

        // Asignar rol "Cliente" automáticamente
        $user->assignRole('Cliente');

        // Crear perfil en tabla clientes
        Cliente::create([
            'user_id' => $user->id,
            'nombre' => $request->name,
        ]);

        // Enviar email de verificación
        $user->sendEmailVerificationNotification();

        // Auto-login
        Auth::login($user);

        // Redirigir a dashboard de cliente según rol
        return redirect()->route('cliente.dashboard')
            ->with('success', '¡Registro exitoso! Verifica tu correo electrónico.');
    }
}
