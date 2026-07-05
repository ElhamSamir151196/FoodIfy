<?php

namespace App\Http\Requests\DeliveryRider;

use App\Enums\TransportationWay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UpdateDeliveryRiderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $riderId = $this->route('delivery_rider')?->id;

        return [
            'name'                => ['sometimes', 'string', 'max:255'],
            'phone'               => ['sometimes', 'string', 'max:20', Rule::unique('delivery_riders', 'phone')->ignore($riderId)],
            'email'               => ['sometimes', 'email', 'max:255', Rule::unique('delivery_riders', 'email')->ignore($riderId)],
            'password'            => ['sometimes', 'nullable', 'string', Password::defaults()],
            'transportation_way'  => ['sometimes', new Enum(TransportationWay::class)],
            'avatar'              => ['sometimes', 'nullable', 'image', 'max:2048'],
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
