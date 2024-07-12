<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributionResource extends JsonResource
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
            'receipt_no' => $this->receipt_no,
            'farmer_id' => $this->farmer_id,
            'province_name' => $this->farmer->provinceRelation->province_name ?? null,
            'commune_name' => $this->farmer->communeRelation->commune_name ?? null,
            'cooperative_name' => $this->farmer->cooperative->name ?? null,
            'farmer_name' => $this->farmer->full_name,
            'agent_id' => $this->agent_id,
            'agent_name' => $this->staff->name,
            'total_amount' => $this->total_amount,
            'created_at' => $this->created_at,
            'heromarket_combined_order_id' => $this->heromarket_combined_order_id,
            'distribution_details' => DistributionDetailResource::collection($this->distributionDetails),
            'transactions' => TransactionResource::collection($this->transactions),
        ];
    }
}
