<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetVehicleTypeRequest;
use App\Models\VehicleType;

class VehicleTypeController extends Controller
{
    public function index(GetVehicleTypeRequest $request)
    {
        $query = VehicleType::orderBy('id', 'desc');

        return $this->success($query->get());
    }
}