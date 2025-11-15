<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_orders' => $this->resource['total_orders'] ?? 0,
            'admin_pending' => $this->resource['admin_pending'] ?? 0,
            'admin_accepted' => $this->resource['admin_accepted'] ?? 0,
            'admin_rejected' => $this->resource['admin_rejected'] ?? 0,
            'processing_orders' => $this->resource['processing_orders'] ?? 0,
            'completed_orders' => $this->resource['completed_orders'] ?? 0,
            'delivered_orders' => $this->resource['delivered_orders'] ?? 0,
            'total_revenue' => $this->resource['total_revenue'] ?? 0,
        ];
    }
}
