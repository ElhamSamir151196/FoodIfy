<?php
namespace App\Http\Resources;

use App\Enums\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MealReviewResource;

class MealResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'price'          => (float) $this->price,
            'image'          => Storage::disk('public')->url($this->image),
            'is_available'   => $this->is_available,
            'category'       => new CategoryResource($this->whenLoaded('category')),
            'nutrition'      => [
                'calories' => $this->calories,
                'protein'  => (float) $this->protein,
                'carbs'    => (float) $this->carbs,
                'fat'      => (float) $this->fat,
                'fiber'    => (float) $this->fiber,
            ],
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,

            // Include average rating and reviews only if they are loaded
            'average_rating' => $this->when(
                $this->relationLoaded('reviews'),
                fn() => $this->reviews->count() ? round($this->reviews->avg('rating'), 1) : null
            ),
            'reviews'        => MealReviewResource::collection($this->whenLoaded('reviews')),

            // Map ingredients to their corresponding enum values and labels
            'ingredients'    => collect($this->ingredients ?? [])->map(function ($key) {
                $ingredient = Ingredient::tryFrom($key);
                return $ingredient ? [
                    'id'   => $ingredient->value,
                    'name' => $ingredient->label(),
                    'icon' => $ingredient->icon(),
                ] : null;
            })->filter()->values(),
        ];
    }
}
