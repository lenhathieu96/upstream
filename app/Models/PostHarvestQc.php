<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostHarvestQc extends Model
{
    protected $table = 'post_harvest_qc';
    protected $fillable = [
        'key',
        'description',
        'unit',
        'min_standard',
        'max_standard',
        'is_published',
        'type',
    ];

    public function vendorProcurements(): HasMany
    {
        return $this->hasMany(VendorProcurementQC::class, 'post_harvest_qc_id');
    }
}