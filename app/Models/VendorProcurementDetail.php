<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProcurementDetail extends Model
{
    protected $fillable = [
        'vendor_procurement_id',
        'product_name',
        'sale_intention_id',
        'order_quantity',
        'product_id',
    ];

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(VendorProcurement::class, 'vendor_procurement_id');
    }
}