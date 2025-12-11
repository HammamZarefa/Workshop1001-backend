<?php

namespace App\Policies;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CouponPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_coupons');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Coupon $coupon): bool
    {
        return $user->hasPermission('view_coupons');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_coupons');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Coupon $coupon): bool
    {
        return $user->hasPermission('update_coupons');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Coupon $coupon): bool
    {
        return $user->hasPermission('delete_coupons');
    }

}
