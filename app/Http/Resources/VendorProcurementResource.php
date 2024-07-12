<?php

namespace App\Http\Resources;

use App\Models\Uploads;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorProcurementResource extends JsonResource
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
            'vendor_procurement_code' => $this->vendor_procurement_code,
            'transaction_date' => $this->transaction_date,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'order_id' => $this->order_id,
            'order_code' => $this->order_code,
            'photos' => (new UploadService())->generateFileUrl($this->photos),
            'vendor_procurement_detail' => new VendorProcurementDetailResource($this->resource->detail)
        ];
    }
}
