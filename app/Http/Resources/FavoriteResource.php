<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'meal'         => new MealResource($this->resource),
            'favorited_at' => $this->pivot->created_at,
        ];
    }
}