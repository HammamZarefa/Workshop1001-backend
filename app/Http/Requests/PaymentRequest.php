<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id'  => 'required|exists:orders,id',
            'provider'  => 'nullable|string|max:255',
            'method'    => 'nullable|string|max:255',
            'status'    => 'required|in:pending,paid,failed,canceled',
            'reference' => 'nullable|string|max:255',
            'amount'    => 'required|numeric|min:0',
            'currency'  => 'required|string|size:3',
            'paid_at'   => 'nullable|date',
            'meta'      => 'nullable|array',
        ];
    }
}
