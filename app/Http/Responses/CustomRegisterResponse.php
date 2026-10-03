<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse;

class CustomRegisterResponse implements RegisterResponse
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json('', 201);
        }

        return redirect()->route('cliente.dashboard')
            ->with('success', '¡Registro exitoso! Verifica tu correo electrónico.');
    }
}
