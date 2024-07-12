<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPreHarvestQcRequest;
use App\Models\PreHarvestQc;

class PreHarvestQcController extends Controller
{
    public function index(GetPreHarvestQcRequest $request)
    {
        $query = PreHarvestQc::select(
            'id',
            $request->filled('lang') && $request->input('lang') == 'vi' ? 'description_vn as description' : 'description',
            'unit',
            'description as description_en',
            'description_vn',
            'min_standard',
            'max_standard',
            'type',
        )
            ->where('is_published', true)->get();

        return $this->success($query);
    }
}