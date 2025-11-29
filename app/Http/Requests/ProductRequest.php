<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'is_featured' => 'nullable|boolean',
            'min_price'   => 'nullable|numeric|min:0',
            'max_price'   => 'nullable|numeric|min:0',
            'featured' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery' => 'nullable',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'colors' => 'nullable|array',
            'colors.*' => 'regex:/^#[0-9A-Fa-f]{6}$/',
            'is_special' => 'boolean',
        ];
    }
}
