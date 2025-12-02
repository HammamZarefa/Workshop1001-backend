<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:2048',
        ];
    }
}
