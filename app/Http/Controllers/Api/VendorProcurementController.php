<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVendorProcurementRequest;
use App\Http\Requests\GetVendorProcurementsRequest;
use App\Http\Resources\VendorProcurementResource;
use App\Models\VendorProcurement;
use App\Services\SaleIntentionService;
use App\Services\VendorProcurementService;
use Illuminate\Http\JsonResponse;

class VendorProcurementController extends Controller
{
    public function __construct(private VendorProcurementService $service)
    {
    }

    public function create(CreateVendorProcurementRequest $request)
    {
        $vendor = $this->service->create($request->toArray());
        if (empty($vendor)) {
            return $this->fail('Vendor Procurement With Order Code ' . $request->input('order_code') . ' Is Existed!');
        }

        return $this->success($vendor);
    }

    public function index(GetVendorProcurementsRequest $request): JsonResponse
    {
        $saleIntentionIds = (new SaleIntentionService())->getStaffSaleIntentionColumn('id');

        $vendors = VendorProcurement::orderBy('id', 'desc')->whereHas('detail', function ($builder) use ($saleIntentionIds) {
            $builder->whereIn('sale_intention_id', $saleIntentionIds);
        });

        return $this->success(VendorProcurementResource::collection($vendors->get()));
    }
}