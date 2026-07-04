<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_address'  => ['required', 'string', 'max:500'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ];
    }
}