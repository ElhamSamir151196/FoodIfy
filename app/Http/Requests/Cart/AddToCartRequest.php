<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meal_id'  => ['required', 'integer', 'exists:meals,id'],
            'quantity' => ['required', 'integer', 'min:1' , 'max:100'],
        ];
    }
}