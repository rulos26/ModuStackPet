<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    // // Mostrar el formulario para restablecer contraseña
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Enviar el enlace para restablecer la contraseña
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
                    ? back()->with('status', __($response))
                    : back()->withErrors(['email' => __($response)]);
    }

    // Mostrar el formulario para restablecer la contraseña (cuando se pasa el token)
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Procesar el restablecimiento de la contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        // Intentar restablecer la contraseña
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                // Disparar el evento de restablecimiento de la contraseña
                event(new PasswordReset($user));
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.')
                    : back()->withErrors(['email' => [__($response)]]);
    }
}
