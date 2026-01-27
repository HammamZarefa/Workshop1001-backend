<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'coupon_id',
        'coupon_value',
        'shipping_address',
        'status',
        'tax_amount',
        'discount_percentage',
        'currency',
        'total',
        'stock_deducted_at',

    ];

    /*
     |-------------------------
     | Relationships
     |-------------------------
     */

    // Order belongs to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // One order has many items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }



    public function logs()
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('created_at', 'desc');
    }

    public static function allowedStatuses(): array
    {
        return ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    }

   public static function statusTransitions(): array
{
    return [
        'pending'    => ['paid', 'cancelled'],
        'paid'       => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped'    => ['delivered'],
        'delivered'  => [],
        'cancelled'  => [],
    ];
}


    public function canTransitionTo(string $new): bool
    {
        $current = $this->status;
        $map = static::statusTransitions();
        if (!array_key_exists($current, $map)) return false;
        return in_array($new, $map[$current], true);
    }


}
