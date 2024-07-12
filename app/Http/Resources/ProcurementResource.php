<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcurementResource extends JsonResource
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
            'transaction_date' => $this->transaction_date,
            'procurement_code' => $this->procurement_code,
            'booking_id' => $this->booking_id,
            'warehouse_id' => $this->warehouse_id,
            'total_amount' => $this->total_amount,
            'staff_id' => $this->staff_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'warehouse' => new WarehouseResource($this->resource->warehouse),
            'booking' => new BookingResource($this->resource->booking),
            'details' => ProcurementDetailResource::collection($this->resource->details),
            'other_costs' => ProcurementOtherCostResource::collection($this->resource->otherCosts),
        ];
    }
}
