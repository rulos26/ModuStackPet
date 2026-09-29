<?php

namespace App\Policies;

use App\Models\Mascota;
use App\Models\User;

class MascotaPolicy
{
    /**
     * SEG-016: corrige el IDOR de mascotas.* — Cliente solo ve/gestiona las
     * suyas, Admin y Superadmin gestionan todas, Paseador no tiene acceso.
     */
    public function view(User $user, Mascota $mascota): bool
    {
        return $this->puedeGestionar($user, $mascota);
    }

    public function update(User $user, Mascota $mascota): bool
    {
        return $this->puedeGestionar($user, $mascota);
    }

    public function delete(User $user, Mascota $mascota): bool
    {
        return $this->puedeGestionar($user, $mascota);
    }

    private function puedeGestionar(User $user, Mascota $mascota): bool
    {
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            return true;
        }

        if ($user->hasRole('Cliente')) {
            return $mascota->user_id === $user->id;
        }

        return false;
    }
}
