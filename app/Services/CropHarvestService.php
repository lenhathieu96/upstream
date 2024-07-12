<?php

namespace App\Services;

use App\Models\CropHarvest;
use App\Services\Common\UniqueCodeService;
use Illuminate\Support\Facades\DB;

class CropHarvestService
{
    public function create(array $data, $date, $staffIdInput = null): ?CropHarvest
    {
        $authUser = request()->user('sanctum');
        $staffId = empty($authUser->staff) ? null : $authUser->staff->id;
        $cropHarvest = CropHarvest::create([
            'staff_id' => !empty($staffId) ? $staffId : $staffIdInput,
            'harvest_date' => $date,
            'crop_harvest_code' => UniqueCodeService::generate('CH'),
        ]);
        $totalAmount = 0;

        DB::table('crop_harvest_details')->insert(array_map(function ($attribute) use ($cropHarvest, &$totalAmount) {
            $totalAmount += $attribute['approx_harvest_qty'] * $attribute['price_per_unit'];
            return [
                'crop_harvest_id' => $cropHarvest->id,
                'cultivation_id' => $attribute['cultivation_id'],
                'approx_harvest_qty' => $attribute['approx_harvest_qty'],
                'price_per_unit' => $attribute['price_per_unit'],
                'sub_total' => $attribute['approx_harvest_qty'] * $attribute['price_per_unit'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $data));

        $cropHarvest->total_amount = $totalAmount;
        $cropHarvest->save();

        return $cropHarvest;
    }
}