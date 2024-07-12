<?php

namespace App\Services;

use App\Models\CropHarvestDetail;
use App\Models\Procurement;
use App\Models\ProcurementDetail;

class ProcurementDetailService
{
    public function createProcurementDetail(Procurement $procurement, array $attribute)
    {
        foreach ($attribute['procurement_details'] as $detail) {
            $cropHarvestDetail = CropHarvestDetail::find($detail['crop_harvest_detail_id']);
            $farmer = $cropHarvestDetail->cultivation->farm_land->farmer_details;
            $actualSubTotal = $cropHarvestDetail->price_per_unit * $detail['actual_qty'];
            ProcurementDetail::create([
                'procurement_id' => $procurement->id,
                'farmer_id' => $farmer->id,
                'crop_harvest_detail_id' => $detail['crop_harvest_detail_id'],
                'actual_qty' => $detail['actual_qty'],
                'actual_sub_total' => $actualSubTotal
            ]);

            $faAccount = $farmer->faAccount;
            $faAccount->loan_amount = $faAccount->loan_amount > $actualSubTotal ? ($faAccount->loan_amount - $actualSubTotal) : 0;
            $faAccount->outstanding_amount = $faAccount->outstanding_amount + $actualSubTotal;
            $faAccount->save();

            $cropHarvestDetail->update([
                'status' => 'procured'
            ]);
            $procurement->total_amount += $actualSubTotal;
            $procurement->save();
        }
    }
}