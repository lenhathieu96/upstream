<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetCooperativeProductsRequest;
use App\Http\Requests\GetCooperativeRequest;
use App\Models\Cooperative;
use App\Services\HeroMarket\ProductService;
use Illuminate\Http\Request;

class CooperativeController extends Controller
{
    public function index(GetCooperativeRequest $request)
    {
        $user = $request->user('sanctum');

        $cooperatives = Cooperative::where('staff_id', $user->staff->id)
            ->select('id', 'staff_id', 'name', 'cooperative_code')
            ->where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $cooperatives->where('name', 'like', '%' . $search . '%')
                ->orWhere('cooperative_code', 'like', '%' . $search . '%');
        }

        return $this->success($cooperatives->get());
    }

    public function getCooperativeProducts(GetCooperativeProductsRequest $request)
    {
        $service = new ProductService();
        try {
            return $service->getProductByEnterprise($request->toArray());
        } catch (\Exception $exception) {
            info($exception->getMessage());
            return $this->fail('Get Products Fail!');
        }
    }

    public function getCooperativeCategories(int $id)
    {
        $service = new ProductService();
        try {
            return $service->getCategoriesByEnterprise($id);
        } catch (\Exception $exception) {
            info($exception->getMessage());
            return $this->fail('Get Categories Fail!');
        }
    }

    public function getCooperativeId($email)
    {
        $cooperative = Cooperative::where('email', $email)->first();
        if ($cooperative) {
            return response()->json([
                'result' => true,
                'message' => 'get Cooperative Id is successfully!',
                'id' => $cooperative->id,
            ]);
        }

        return response()->json([
            'result' => false,
            'message' => 'get Cooperative Id is fail',
            'id' => null,
        ]);
    }
}