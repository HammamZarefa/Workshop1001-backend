<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_carts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cart $cart): bool
    {
        return $user->hasPermission('view_carts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_carts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cart $cart): bool
    {
        return $user->hasPermission('update_carts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cart $cart): bool
    {
        return $user->hasPermission('delete_carts');
    }

}
