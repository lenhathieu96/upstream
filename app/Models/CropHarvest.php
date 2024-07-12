<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropHarvest extends Model
{
    protected $appends = ['variety', 'farmer_name', 'farmer_loan_amount'];
    protected $fillable = [
        'crop_harvest_code',
        'total_amount',
        'harvest_date',
        'staff_id',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(CropHarvestDetail::class);
    }

    /**
     * Crop Harvest Details has the same variety
     **/
    public function getVarietyAttribute()
    {
        $detail = $this->details()->first();
        return $detail->cultivation->crop_variety;
    }

    public function getFarmerNameAttribute()
    {
        $detail = $this->details()->first();
        return $detail->cultivation->farm_land->farmer_details->full_name;
    }

    public function getFarmerLoanAmountAttribute()
    {
        $detail = $this->details()->first();
        return $detail->cultivation->farm_land->farmer_details->faAccount->loan_amount;
    }
}