<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetSeasonMastersRequest;
use App\Services\SeasonMasterService;
use Illuminate\Http\JsonResponse;

class SeasonMasterController extends Controller
{
    public function index(GetSeasonMastersRequest $request): JsonResponse
    {
        return $this->success((new SeasonMasterService())->index(
            $request->input('season_name'),
            $request->input('from_period'),
            $request->input('to_period'),
            $request->input('status')
        )->get());
    }
}