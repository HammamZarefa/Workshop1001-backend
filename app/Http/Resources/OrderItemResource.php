<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'product'   => [
                'id'    => $this->product->id,
                'name'  => $this->product->name,
                'image' => $this->product->getFirstMediaUrl('images') ?? null,

            ],
            'price'     => $this->price,
            'quantity'  => $this->quantity,
            'note'      => $this->note,
            'subtotal'  => $this->price * $this->quantity,
        ];
    }
}
