<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;


class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'amount'    => $this->amount,
            'currency'  => $this->currency,
            'status'    => $this->status,
            'provider'  => $this->provider,
            'method'    => $this->method,
            'reference' => $this->reference,
            'paid_at'   => $this->paid_at ? $this->paid_at->toDateTimeString() : null,
            'meta'      => $this->meta,

            'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}
