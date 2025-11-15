<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'category_id'  => $this->category_id,
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'title'        => $this->title,
            'description'  => $this->description,
            'price'        => $this->price,
            'currency'     => $this->currency,
            'stock'        => $this->stock,
            'is_active'    => $this->is_active,
            'is_featured'  => $this->is_featured,
            'colors'       => $this->colors,
            'featured_image' => $this->getFirstMedia('featured')
                ? $this->getFirstMedia('featured')->getUrl()
                : null,
            'gallery' => $this->getMedia('gallery')->map(function ($media) {
                return [
                    'id'  => $media->id,
                    'url' => $media->getUrl(),
                ];
            }),

        ];
    }
}
