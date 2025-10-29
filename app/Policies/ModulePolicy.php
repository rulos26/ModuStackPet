<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;

class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Superadmin');
    }

    public function update(User $user, Module $module): bool
    {
        return $user->hasRole('Superadmin');
    }
}


