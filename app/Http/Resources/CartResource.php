<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'items' => CartItemResource::collection($this->items),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total' => $this->items->sum(fn($item) => $item->price * $item->quantity),

        ];
    }
}
