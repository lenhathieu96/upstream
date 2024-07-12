<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * status list: procured, pending
 **/
class CropHarvestDetail extends Model
{
    protected $fillable = [
        'crop_harvest_id',
        'cultivation_id',
        'approx_harvest_qty',
        'price_per_unit',
        'sub_total',
        'status',
    ];

    public function cropHarvest(): BelongsTo
    {
        return $this->belongsTo(CropHarvest::class);
    }

    public function cultivation(): BelongsTo
    {
        return $this->belongsTo(Cultivations::class, 'cultivation_id');
    }
}