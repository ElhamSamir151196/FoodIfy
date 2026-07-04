<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'  => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'name'         => ['sometimes', 'required', 'string', 'max:255'],
            'description'  => ['sometimes', 'required', 'string'],
            'price'        => ['sometimes', 'required', 'numeric', 'min:0'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'calories'     => ['sometimes', 'required', 'integer', 'min:0'],
            'protein'      => ['sometimes', 'required', 'numeric', 'min:0'],
            'carbs'        => ['sometimes', 'required', 'numeric', 'min:0'],
            'fat'          => ['sometimes', 'required', 'numeric', 'min:0'],
            'fiber'        => ['sometimes', 'required', 'numeric', 'min:0'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }
}