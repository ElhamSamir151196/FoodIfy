<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'user'                 => new UserResource($this->whenLoaded('user')),
            'delivery_address'     => $this->delivery_address,
            'delivery_lat'         => $this->delivery_lat,
            'delivery_lng'         => $this->delivery_lng,
            'notes'                => $this->notes,
            'subtotal'             => (float) $this->subtotal,
            'delivery_fee'         => (float) $this->delivery_fee,
            'total'                => (float) $this->total,
            'status'               => $this->status,
            'tracking_status'      => $this->tracking_status,
            'estimated_minutes'    => $this->estimated_minutes,
            'rider'                => new DeliveryRiderResource($this->whenLoaded('rider')),
            'items'                => OrderItemResource::collection($this->whenLoaded('items')),
            'paid_at'              => $this->paid_at,
            'cancelled_at'         => $this->cancelled_at,
            'cancellation_reason'  => $this->cancellation_reason,
            'created_at'           => $this->created_at,
            'last_updated_at'      => $this->updated_at,
        ];
    }
}