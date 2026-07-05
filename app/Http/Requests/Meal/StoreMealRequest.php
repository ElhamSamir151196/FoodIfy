<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Ingredient;
use Illuminate\Validation\Rule;

class StoreMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'  => ['required', 'integer', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'price'        => ['required', 'numeric', 'min:0'],
            'image'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'calories'     => ['required', 'integer', 'min:0'],
            'protein'      => ['required', 'numeric', 'min:0'],
            'carbs'        => ['required', 'numeric', 'min:0'],
            'fat'          => ['required', 'numeric', 'min:0'],
            'fiber'        => ['required', 'numeric', 'min:0'],
            'is_available' => ['sometimes', 'boolean'],

            'ingredients'   => ['sometimes', 'array'],
            'ingredients.*' => ['string', Rule::enum(Ingredient::class)],
        ];
    }
}