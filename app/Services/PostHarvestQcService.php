<?php

namespace App\Services;

use App\Models\ProcurementDetail;
use App\Models\VendorProcurementDetail;
use Illuminate\Support\Facades\DB;

class PostHarvestQcService
{
    public function checkVendorDetailQuality(VendorProcurementDetail $vendor, array $attribute)
    {
        $now = now();
        DB::table('vendor_procurement_qc')->insert(array_map(function ($data) use ($vendor, $now) {
            return [
                'vendor_detail_id' => $vendor->id,
                'post_harvest_qc_id' => $data['id'],
                'value' => $data['value'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $attribute));
    }
}