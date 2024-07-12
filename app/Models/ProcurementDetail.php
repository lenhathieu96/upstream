<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementDetail extends Model
{
    protected $fillable = [
        'procurement_id',
        'farmer_id',
        'crop_harvest_detail_id',
        'actual_qty',
        'actual_sub_total'
    ];

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class, 'procurement_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerDetails::class, 'farmer_id');
    }

    public function cropHarvestDetail(): BelongsTo
    {
        return $this->belongsTo(CropHarvestDetail::class, 'crop_harvest_detail_id');
    }
}