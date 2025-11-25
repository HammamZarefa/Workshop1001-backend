<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shipping_address' => $this->shipping_address,
            'status' => $this->status,
            'currency' => $this->currency,
            'coupon_value' => $this->coupon_value,
            'tax_amount' => $this->tax_amount,
            'discount_percentage' => $this->discount_percentage,
            'total' => $this->total,
            'customer' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name ?? null,
                    'last_name' => $this->user->last_name ?? null,
                    'email' => $this->user->email,
                ];
            }),
            'payment' => $this->whenLoaded('payment', function () {
                return [
                    'id' => $this->payment->id,
                    'provider' => $this->payment->provider,
                    'method' => $this->payment->method,
                    'status' => $this->payment->status,
                    'reference' => $this->payment->reference,
                    'amount' => $this->payment->amount,
                    'currency' => $this->payment->currency,
                    'paid_at' => $this->payment->paid_at,
                ];
            }),
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
