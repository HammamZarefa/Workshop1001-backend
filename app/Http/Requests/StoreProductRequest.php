<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',

            'category_id' => 'nullable|exists:categories,id',
            'is_featured' => 'nullable|boolean',

            'featured' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'gallery'   => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
