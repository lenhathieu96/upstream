<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distribution_id',
        'product_id',
        'product_name',
        'category_id',
        'category_name',
        'quantity',
        'price_per_unit',
        'sub_total',
        'unit',
        'available_stocks',
    ];

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class, 'distribution_id');
    }
}
