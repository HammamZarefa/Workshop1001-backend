<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'shipping_address' => $this->shipping_address,
            'status' => $this->status,
            'currency' => $this->currency,
            'coupon_value' => $this->coupon_value,
            'tax_amount' => $this->tax_amount,
            'discount_percentage' => $this->discount_percentage,
            'total' => $this->total,
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
