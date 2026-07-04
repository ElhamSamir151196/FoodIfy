<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'last_four'  => $this->last_four,
            'card_brand' => $this->card_brand,
            'bank_name'  => $this->bank_name,
            'is_default' => $this->is_default,
            'created_at' => $this->created_at,
        ];
    }
}