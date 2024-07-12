<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SeasonMaster extends Model
{
    use HasFactory;
    protected $table = 'season_masters';
    // protected $appends = ['season_name'];

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_code', 'code');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrentSeason(Builder $query)
    {
        return $query->where('is_current_season', 1);
    }
}
