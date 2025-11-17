<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'shipping_address' => 'required|string',
            'coupon_value' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'currency' => 'required|string|size:3',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string',
        ];
    }
}
