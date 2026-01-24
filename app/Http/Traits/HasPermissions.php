<?php

namespace App\Http\Traits;

trait HasPermissions
{
    public function hasPermission(string $permissionName): bool
    {
        if ($this->is_admin) {
            return true;
        }
        return $this->role
            ? $this->role->hasPermission($permissionName)
            : false;
    }
}
