<?php

namespace App\Http\Controllers\Api;

use App\Exports\DistributionDetailExport;
use App\Exports\DistributionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDistributionRequest;
use App\Http\Requests\GetAvailableStockRequest;
use App\Http\Requests\GetDistributionsRequest;
use App\Http\Resources\DistributionResource;
use App\Models\Distribution;
use App\Models\DistributionBalance;
use App\Models\User;
use App\Services\DistributionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class DistributionController extends Controller
{
    public function __construct(private DistributionService $service)
    {
    }

    public function store(CreateDistributionRequest $request): JsonResponse
    {
        $checkOutOfStockEndpoint = config('upstream.HEROMARKET_URL') . '/api/v2/users/products/out-of-stock';
        $response = Http::withOptions(['verify' => false])->post($checkOutOfStockEndpoint, $request->toArray());
        $result = json_decode($response->getBody(), true);
        if (count($result['data']) > 0) {
            return $this->fail('Some Items are out of stock!', 200, [
                'stock_id' => $result['data']
            ]);
        }

        try {
            DB::beginTransaction();
            $result = $this->service->create($request->toArray());
            if ($result) {
                DB::commit();
                return $this->success(
                    (new DistributionResource($result))->resolve(),
                    'Distribution Created Successfully!'
                );
            }

            return $this->fail('Can not Create Distribution');
        } catch (\Exception $exception) {
            DB::rollback();
            info($exception->getMessage());
            info($exception->getFile());
            info($exception->getLine());
            info($exception->getCode());
            info($exception->getTraceAsString());

            return $this->fail($exception->getMessage());
        }
    }

    public function index(GetDistributionsRequest $request)
    {
        $authUser = $request->user('sanctum');

        $query = Distribution::query()->where('agent_id', $authUser->staff->id)
            ->orderBy('id', $request->input('order_by', 'desc'));

        if ($request->input('farmer_id')) {
            $query->where('farmer_id', $request->input('farmer_id'));
        }

        if ($request->filled('export_type')) {
            $type = $request->input('export_type');
            return Excel::download((new DistributionExport($query, $type)), $type . '_export_' . date('Y-m-d') . '.xlsx');
        }

        $query = $query->paginate($request->input('per_page', 15));

        return $this->pagination(
            $query,
            DistributionResource::collection($query)
        );
    }

    public function show(int $id): JsonResponse
    {
        $distribution = Distribution::find($id);

        $authUser = request()->user('sanctum');

        if (empty($authUser->staff)
            || empty($distribution)
            || $distribution->agent_id !== $authUser->staff->id
        ) {
            return $this->fail('Distribution does not Exist or not belongs to you!');
        }

        return $this->success(new DistributionResource($distribution));
    }

    public function getPreviousStock(GetAvailableStockRequest $request): JsonResponse
    {
        $productId = $request->route('product_id');
        $farmerId = $request->route('farmer_id');

        $query = DistributionBalance::where('product_id', $productId)
            ->where('farmer_id', $farmerId);

        return $this->success([
            'previous_stocks' => $query->exists() ? (float)$query->sum('quantity') : 0,
        ]);
    }
}