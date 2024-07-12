<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPostHarvestQcRequest;
use App\Models\PostHarvestQc;
use Illuminate\Http\JsonResponse;

class PostHarvestQcController extends Controller
{
    public function index(GetPostHarvestQcRequest $request): JsonResponse
    {
        return $this->success(PostHarvestQc::select(
            'id',
            'key',
            'description',
            'unit',
            'min_standard',
            'max_standard',
            'type'
        )->where('is_published', true)->get());
    }
}