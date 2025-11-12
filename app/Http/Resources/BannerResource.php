<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'link'        => $this->link,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
            'image'       => $this->getFirstMediaUrl('banners'),
        ];
    }
}
