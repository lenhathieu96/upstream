<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receipt_no',
        'agent_id',
        'farmer_id',
        'distribution_date',
        'total_amount',
    ];

    protected $casts = [
        'distribution_date' => 'date',
        'receipt_no' => 'string'
    ];

    public function distributionDetails(): HasMany
    {
        return $this->hasMany(DistributionDetail::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerDetails::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'agent_id');
    }
}
