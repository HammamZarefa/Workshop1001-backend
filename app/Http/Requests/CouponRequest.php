<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        if (!$this->has('start_date') || empty($this->start_date)) {
            $this->merge([
                'start_date' => now()->toDateTimeString(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date' => 'sometimes|date',
            'expiration_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }
}
