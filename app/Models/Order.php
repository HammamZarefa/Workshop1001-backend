<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'coupon_value',
        'shipping_address',
        'status',
        'tax_amount',
        'discount_percentage',
        'currency',
        'total',
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



    // إجمالي السعر قبل الضرائب والخصومات
    public function subtotal()
    {
        return $this->items->sum(fn ($item) => $item->price * $item->quantity);
    }

    // إجمالي الضريبة
    public function taxTotal()
    {
        return $this->subtotal() * ($this->tax_amount / 100);
    }

    // قيمة الخصم
    public function discountTotal()
    {
        return $this->subtotal() * ($this->discount_percentage / 100);
    }

    // إجمالي نهائي محسوب
    public function calculatedTotal()
    {
        return $this->subtotal()
            + $this->taxTotal()
            - $this->discountTotal()
            - $this->coupon_value;
    }
}
