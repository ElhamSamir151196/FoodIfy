<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'meal'       => new MealResource($this->whenLoaded('meal')),
            'quantity'   => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'subtotal'   => round($this->quantity * $this->unit_price, 2),
        ];
    }
}