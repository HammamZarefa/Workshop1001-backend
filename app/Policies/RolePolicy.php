<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if (property_exists($user, 'is_super_admin') && $user->is_super_admin) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.index');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('roles.create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.update');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.delete');
    }

}
