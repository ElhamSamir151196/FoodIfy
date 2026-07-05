<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryRiderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'phone'               => $this->phone,
            'email'               => $this->email,
            'avatar'              => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'transportation_way'  => $this->transportation_way?->value,
            'transportation_label'=> $this->transportation_way?->label(),
            'rating'              => (float) $this->rating,
            'is_available'        => (bool) $this->is_available,
            'created_at'          => $this->created_at?->toDateTimeString(),
            'updated_at'          => $this->updated_at?->toDateTimeString(),
        ];
    }
}
