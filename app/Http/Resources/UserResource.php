<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'full_name'  => $this->full_name,
            'phone'      => $this->phone,
            'email'      => $this->email,
            'role'       => $this->role,
            'avatar'     => $this->avatar,
            'address'    => $this->address,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'phone_verified_at' => $this->phone_verified_at?->format('Y-m-d H:i:s'),
        ];
    }
}