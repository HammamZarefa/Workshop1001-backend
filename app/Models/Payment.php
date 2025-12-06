<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'method',
        'status',
        'reference',
        'amount',
        'currency',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

     public function markAsRefunded(?string $reason = null)
    {
        $this->update([
            'status' => 'refunded',
            'meta' => array_merge($this->meta ?? [], [
                'refund_reason' => $reason,
                'refunded_at'   => now(),
            ]),
        ]);
    }
}
