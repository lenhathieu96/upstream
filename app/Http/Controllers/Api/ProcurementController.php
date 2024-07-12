<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProcurementRequest;
use App\Http\Requests\GetProcurementsRequest;
use App\Http\Resources\ProcurementResource;
use App\Models\Procurement;
use App\Services\ProcurementService;
use Illuminate\Http\JsonResponse;

class ProcurementController extends Controller
{
    public function store(CreateProcurementRequest $request): JsonResponse
    {
        try {
            $procurement = (new ProcurementService())->store($request->toArray());
            if ($procurement) {
                return $this->success(new ProcurementResource($procurement));
            }
            return $this->fail('Can not create procurement');
        }
        catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function index(GetProcurementsRequest $request)
    {
        $items = Procurement::orderBy('id', 'desc')
            ->where('staff_id', $request->user('sanctum')->staff->id)
            ->paginate($request->input('per_page', 15));

        return $this->pagination($items, ProcurementResource::collection($items));
    }
}