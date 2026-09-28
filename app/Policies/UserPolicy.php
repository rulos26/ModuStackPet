<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Defensa en profundidad para SEG-001/010: solo Superadmin puede ver o
     * modificar la asignación de roles, aunque el middleware de la ruta
     * fallara o cambiara.
     */
    public function manageRoles(User $authUser): bool
    {
        return $authUser->hasRole('Superadmin');
    }

    public function assignRole(User $authUser, User $target): bool
    {
        return $authUser->hasRole('Superadmin');
    }
}
