<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCropHarvestRequest;
use App\Http\Requests\GetCropHarvestsRequest;
use App\Http\Resources\CropHarvestResource;
use App\Models\CropHarvest;
use App\Models\CropHarvestDetail;
use App\Models\Cultivations;
use App\Services\CropHarvestService;
use Illuminate\Http\JsonResponse;

class CropHarvestController extends Controller
{
    public function __construct(private CropHarvestService $cropHarvestService)
    {
    }

    public function index(GetCropHarvestsRequest $request): JsonResponse
    {
        $query = CropHarvestDetail::query()->orderBy('id', 'desc');
        $authUser = $request->user('sanctum');
        if ($authUser->staff) {
            $query->whereHas('cropHarvest', function ($builder) use ($authUser) {
                $builder->where('staff_id', $authUser->staff->id);
            });
        }

        return $this->success(CropHarvestResource::collection($query->get()));
    }

    public function create(CreateCropHarvestRequest $request): JsonResponse
    {
        $details = $request->input('crop_harvests');
        $checkQuery = CropHarvestDetail::whereIn('cultivation_id', array_column($details, 'cultivation_id'));

        if (Cultivations::whereIn('id', array_column($details, 'cultivation_id'))
                ->pluck('crop_variety')
                ->unique()
                ->count() > 1) {
            return $this->fail('Please select the same Variation!');
        }
        if ($checkQuery->exists()) {
            return $this->fail('Some Cultivation has been assigned to a Crop Harvest!', 200, [
                'cultivation_id' => $checkQuery->pluck('cultivation_id')->toArray()
            ]);
        }

        $cropHarvest = $this->cropHarvestService->create($details, $request->input('harvest_date'), $request->input('staff_id'));
        return $this->success($cropHarvest->toArray());
    }
}