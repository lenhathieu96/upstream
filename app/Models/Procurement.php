<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procurement extends Model
{
    protected $fillable = [
        'transaction_date',
        'procurement_code',
        'booking_id',
        'warehouse_id',
        'total_amount',
        'staff_id',
        'lat',
        'lng'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(ProcurementDetail::class, 'procurement_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function procurementDetails(): HasMany
    {
        return $this->hasMany(ProcurementDetail::class, 'procurement_id');
    }

    public function otherCosts(): HasMany
    {
        return $this->hasMany(ProcurementOtherCost::class, 'procurement_id');
    }
}