<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address' => 'nullable|string',
            'status' => 'nullable|in:pending,accepted,rejected',
            'coupon_value' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'currency' => 'nullable|string|size:3',
        ];
    }
}
