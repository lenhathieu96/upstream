<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Type: 0 is text/string, 1 is number (Ask Mobile Dev before
 **/
class PreHarvestQc extends Model
{
    protected $table = 'pre_harvest_qc';

    protected $fillable = [
        'key',
        'description',
        'description_vn',
        'unit',
        'min_standard',
        'max_standard',
        'is_published',
        'type',
    ];
}