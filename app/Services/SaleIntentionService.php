<?php

namespace App\Services;

use App\Models\SaleIntention;

class SaleIntentionService
{
    public function getStaffSaleIntentionColumn(string $column): array
    {
        if (empty(request()->user('sanctum')->staff)) {
            return [];
        }
        return SaleIntention::whereHas('farmer', function ($query) {
            $query->where('staff_id', request()->user('sanctum')->staff->id);
        })->pluck($column)->toArray();
    }
}