<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementOtherCost extends Model
{
    protected $table = 'procurement_other_cost';
    protected $fillable = [
        'procurement_id',
        'item',
        'quantity',
        'rate',
        'sub_total',
    ];

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class, 'procurement_id');
    }
}