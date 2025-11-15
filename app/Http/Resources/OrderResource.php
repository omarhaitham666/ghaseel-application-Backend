<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'location' => new UserLocationResource($this->whenLoaded('location')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'delivery_type' => $this->delivery_type,
            'pickup_date' => $this->pickup_date,
            'pickup_time' => $this->pickup_time,
            'delivery_date' => $this->delivery_date,
            'delivery_time' => $this->delivery_time,
            'notes' => $this->notes,
            'admin_status' => $this->admin_status,
            'order_status' => $this->order_status,
            'final_price' => $this->final_price,
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
