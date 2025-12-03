<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request)
    {
        $product = $this->product;
        return [
            'id'        => $this->id,
            'product'   => [
                'id'    => $product->id ?? null,
                'title'  => $product->title ?? null,
                'image' => $product?->getFirstMediaUrl('featured')
            ],
            'price'     => $this->price,
            'quantity'  => $this->quantity,
            'subtotal'  => $this->price * $this->quantity,
        ];
    }
}
