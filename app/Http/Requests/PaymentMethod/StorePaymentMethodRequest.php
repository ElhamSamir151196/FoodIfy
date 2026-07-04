<?php

namespace App\Http\Requests\PaymentMethod;

use App\Enums\PaymentMethodType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'         => ['required', new Enum(PaymentMethodType::class)],
            'paymob_token' => ['nullable', 'string'],
            'last_four'    => ['nullable', 'string', 'size:4'],
            'card_brand'   => ['nullable', 'string', 'max:50'],
            'bank_name'    => ['nullable', 'string', 'max:100'],
            'is_default'   => ['sometimes', 'boolean'],
        ];
    }
}