<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropCalendarDetail extends Model
{
    use HasFactory;

    const DURATION_UOM = [
        'days' => 'Day(s)',
        'weeks' => 'Week(s)',
        'months' => 'Month(s)',
        'years' => 'Year(s)',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
