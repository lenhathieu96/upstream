<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CropHarvestResource extends JsonResource
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
            'crop_harvest_code' => $this->cropHarvest->crop_harvest_code,
            'harvest_date' => $this->cropHarvest->harvest_date,
            'crop' => $this->cultivation->crops_master->name,
            'variety' => $this->cultivation->crop_variety,
            'farmer_name' => $this->cultivation->farm_land->farmer_details->full_name,
            'loan_amount' => $this->cultivation->farm_land->farmer_details->faAccount->loan_amount,
            'approx_harvest_qty' => $this->approx_harvest_qty,
            'price_per_unit' => $this->price_per_unit,
            'sub_total' => $this->sub_total
        ];
    }
}
