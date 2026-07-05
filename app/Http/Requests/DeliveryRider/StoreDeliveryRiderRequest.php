<?php

namespace App\Http\Requests\DeliveryRider;

use App\Enums\TransportationWay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class StoreDeliveryRiderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'max:20', 'unique:delivery_riders,phone'],
            'email'               => ['required', 'email', 'max:255', 'unique:delivery_riders,email'],
            'password'            => ['required', 'string', Password::defaults()],
            'transportation_way'  => ['required', new Enum(TransportationWay::class)],
            'avatar'              => ['nullable', 'image', 'max:2048'],
            'is_available'        => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'transportation_way.enum' => 'وسيلة المواصلات غير صحيحة، القيم المتاحة: ' . implode(', ', TransportationWay::values()),
        ];
    }
}
