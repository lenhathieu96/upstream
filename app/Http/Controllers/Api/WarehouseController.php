<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetVehiclesRequest;
use App\Http\Requests\GetWarehousesRequest;
use App\Models\Vehicle;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index(GetWarehousesRequest $request)
    {
        $query = Warehouse::orderBy('id', 'desc');

        return $this->success($query->get());
    }
}