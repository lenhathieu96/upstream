<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class VendorProcurementQC extends Model
{
    protected $fillable = [
        'vendor_detail_id',
        'post_harvest_qc_id',
        'value',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(VendorProcurementDetail::class, 'vendor_detail_id');
    }

    public function postHarvestQC(): BelongsTo
    {
        return $this->belongsTo(PostHarvestQC::class, 'post_harvest_qc_id');
    }
}