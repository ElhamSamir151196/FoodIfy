<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MealResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => (float) $this->price,
            'image'        => Storage::disk('public')->url($this->image),
            'is_available' => $this->is_available,
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'nutrition'    => [
                'calories' => $this->calories,
                'protein'  => (float) $this->protein,
                'carbs'    => (float) $this->carbs,
                'fat'      => (float) $this->fat,
                'fiber'    => (float) $this->fiber,
            ],
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'ingredients'  => $this->whenLoaded('ingredients', fn() =>
                $this->ingredients->map(fn($i) => ['id' => $i->id, 'name' => $i->name])
            ),
        ];
    }
}
