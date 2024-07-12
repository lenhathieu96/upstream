<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionBalance extends Model
{
    use HasFactory;

    protected $table = 'distribution_balance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_name',
        'farmer_id',
        'quantity',
    ];

    public $timestamps = false;

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerDetails::class);
    }
}
