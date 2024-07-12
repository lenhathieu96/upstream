<?php

namespace App\Services;

use App\Http\Controllers\Api\UploadsController;
use App\Models\SaleIntention;
use App\Models\VendorProcurement;
use App\Models\VendorProcurementDetail;
use App\Services\Common\UniqueCodeService;

class VendorProcurementService
{
    public function create(array $attribute): ?VendorProcurement
    {
        if (VendorProcurementDetail::where('product_id', $attribute['product_id'])->exists()) {
            return null;
        }
        $vendor = VendorProcurement::create([
            'vendor_procurement_code' => UniqueCodeService::generate('VEPR'),
            'transaction_date' => today(),
            'season_id' => $attribute['season_id'],
            'lat' => $attribute['lat'],
            'lng' => $attribute['lng'],
            'order_id' => $attribute['order_id'],
            'order_code' => $attribute['order_code'],
        ]);
        $this->uploadPhoto($vendor, $attribute['order_photo']);
        $this->createVendorDetail($vendor, $attribute);

        return $vendor;
    }

    public function createVendorDetail(VendorProcurement $vendorProcurement, array $attribute)
    {
        $detail = VendorProcurementDetail::create([
            'product_id' => $attribute['product_id'],
            'vendor_procurement_id' => $vendorProcurement->id,
            'product_name' => $attribute['product_name'],
            'sale_intention_id' => SaleIntention::where('product_id', $attribute['product_id'])->first()->id,
            'order_quantity' => $attribute['quantity'],
        ]);
        if (!empty($attribute['qc_photo'])) {
            $this->uploadPhoto($detail, $attribute['qc_photo']);
        }

        if (!empty($attribute['post_harvest_qc'])) {
            (new PostHarvestQcService())->checkVendorDetailQuality($detail, $attribute['post_harvest_qc']);
        }
    }

    public function uploadPhoto(VendorProcurement|VendorProcurementDetail $vendor, array $photos)
    {
        $photoIds = [];

        foreach ($photos as $photo) {

            $id = (new UploadsController())->upload_photo($photo, $vendor->id, $vendor->getMorphClass());
            if (!empty($id)) {
                array_push($photoIds, $id);
            }
        }
        $vendor->photos = implode(',', $photoIds);
        $vendor->save();
    }
}