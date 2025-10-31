<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Muestra el formulario de registro
    public function showRegisterForm()
    {
        return view('auth.register');  // Vista de registro
    }

    // Maneja el proceso de registro
    public function register(Request $request)
    {
        // Validación de los datos recibidos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',  // Confirmación de contraseña
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        // Creación del usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Asegurarse de hashear la contraseña
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

        // Autenticar al usuario después de registrarse
        Auth::login($user);

        // Redirigir al dashboard de cliente
        return redirect()->route('cliente.dashboard')
            ->with('success', '¡Registro exitoso! Verifica tu correo electrónico.');
    }
}
