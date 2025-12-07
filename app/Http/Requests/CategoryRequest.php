<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
           'title' => 'required|string',
        'parent_id' => 'nullable|exists:categories,id',
        'is_active' => 'nullable|boolean',
        'image_file' => 'nullable|image|max:2048',
        'image_url'  => 'nullable|url',
        ];
    }
}
