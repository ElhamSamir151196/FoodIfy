<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'meal'     => new MealResource($this->whenLoaded('meal')),
            'quantity' => $this->quantity,
            'subtotal' => round($this->quantity * $this->meal->price, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}