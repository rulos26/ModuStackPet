<?php

namespace App\Http\Responses;

use App\Models\User;

class RoleRedirect
{
    /**
     * Ruta de destino según el rol del usuario (misma lógica que el antiguo
     * Auth\LoginController).
     */
    public static function for(User $user): string
    {
        return match (true) {
            $user->hasRole('Superadmin') => route('superadmin.dashboard'),
            $user->hasRole('Admin') => route('admin.dashboard'),
            $user->hasRole('Cliente') => route('cliente.dashboard'),
            $user->hasRole('Paseador') => route('paseador.dashboard'),
            default => route('temp.index'),
        };
    }
}
