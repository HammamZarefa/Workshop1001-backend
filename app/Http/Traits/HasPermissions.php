<?php

namespace App\Http\Traits;

trait HasPermissions
{
    public function hasPermission(string $permissionName): bool
    {
        return $this->role
            ? $this->role->hasPermission($permissionName)
            : false;
    }
}
