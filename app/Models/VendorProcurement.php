<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VendorProcurement extends Model
{
    protected $fillable = [
        'vendor_procurement_code',
        'transaction_date',
        'season_id',
        'lat',
        'lng',
        'order_id',
        'order_code',
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];
    public function detail(): HasOne
    {
        return $this->hasOne(VendorProcurementDetail::class, 'vendor_procurement_id');
    }
}