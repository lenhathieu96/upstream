<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetVehiclesRequest;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index(GetVehiclesRequest $request)
    {
        $query = Vehicle::orderBy('id', 'desc');

        if ($request->filled('type_id')) {
            $query->where('type_id', $request->input('type_id'));
        }

        return $this->success($query->get());
    }
}