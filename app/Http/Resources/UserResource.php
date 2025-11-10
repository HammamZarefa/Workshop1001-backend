<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
<<<<<<< HEAD
=======


>>>>>>> 94462bd3665a2eebf59768ce840d2040098fac63
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
<<<<<<< HEAD
        return parent::toArray($request);
=======
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
>>>>>>> 94462bd3665a2eebf59768ce840d2040098fac63
    }
}
