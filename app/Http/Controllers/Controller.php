<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success(mixed $data = [], string $message = 'Success!', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function fail(string $message = '', $statusCode = 200, array $errors = []): JsonResponse
    {
        return response()->json([
            'result' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    public function pagination(LengthAwarePaginator $paginator, AnonymousResourceCollection $collection = null, array $addition = []): JsonResponse
    {
        return $this->success(array_merge($addition, [
            'data' => empty($collection) ? $paginator->items() : $collection,
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem()
        ]));
    }
}
