<?php

namespace App\Http\Traits;

use App\Models\Role;

trait HasRoles
{
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

}
