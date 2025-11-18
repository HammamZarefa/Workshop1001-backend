<?php

namespace App\Models;

use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'usage_limit_per_user',
        'start_date',
        'expiration_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(float $orderTotal, $user): bool
    {
        // 1. Check start date
        if ($this->start_date && now()->lt($this->start_date)) {
            return false;
        }

        // 2. Check expiration
        if ($this->expiration_date && now()->gt($this->expiration_date)) {
            return false;
        }

        // 3. Check minimum order amount
        if ($orderTotal < $this->min_order_amount) {
            return false;
        }

        // 4. Check total usage limit
        if (!is_null($this->usage_limit)) {
            $totalUsed = $this->orders()->count();
            if ($totalUsed >= $this->usage_limit) {
                return false;
            }
        }

        // 5. Check per-user usage
        if (!is_null($this->usage_limit_per_user)) {
            $userUsed = $this->orders()->where('user_id', $user->id)->count();
            if ($userUsed >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }
    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'percentage') {
            return ($orderTotal * $this->value) / 100;
        }

        return $this->value;
    }

}
