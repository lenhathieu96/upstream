<?php

namespace App\Services;

use App\Models\SeasonMaster;
use Illuminate\Database\Eloquent\Builder;

class SeasonMasterService
{
    public function split_with_whitespace($keyword)
    {
        return preg_split('/\s+/u', $keyword, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function index($seasonName = null, $fromPefiod = null, $toPefiod = null, $status = null): Builder
    {
        $seasonMasterQuery = SeasonMaster::orderByDesc('id');
        if ($seasonName) {
            $words = $this->split_with_whitespace($seasonName);

            $seasonMasterQuery->where(function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->orWhere('season_name', 'like', '%' . $word . '%');
                }
            });
        }

        if ($fromPefiod) {
            $seasonMasterQuery->where('from_period', '>=', $fromPefiod);
        }

        if ($toPefiod) {
            $seasonMasterQuery->where('to_period', '<=', $toPefiod);
        }

        if ($status) {
            $seasonMasterQuery->where('status', $status);
        }

        $seasonMasterQuery->orderByDesc('created_at');

        return $seasonMasterQuery;
    }
}