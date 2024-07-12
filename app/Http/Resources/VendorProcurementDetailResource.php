<?php

namespace App\Http\Resources;

use App\Models\PostHarvestQc;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorProcurementDetailResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'sale_intention_id' => $this->sale_intention_id,
            'photos' => (new UploadService())->generateFileUrl($this->photos),
            'post_harvest_qc' => PostHarvestQc::query()->leftJoin(
                'vendor_procurement_qc',
                'vendor_procurement_qc.post_harvest_qc_id',
                '=',
                'post_harvest_qc.id'
            )->select('post_harvest_qc.id', 'description', 'unit', 'value')
                ->where('vendor_detail_id', '=', $this->id)->get()->toArray()
        ];
    }
}
