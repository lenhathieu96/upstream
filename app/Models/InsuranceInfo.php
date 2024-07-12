<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'life_insurance',
        'provider_life_insurance',
        'life_insurance_amount',
        'life_insurance_enrolled_date',
        'life_insurance_end_date',
        'health_insurance',
        'provider_health_insurance',
        'health_insurance_amount',
        'health_insurance_enrolled_date',
        'health_insurance_end_date',
        'crop_insurance',
        'provider_crop_insurance',
        'crop_insured',
        'no_of_area_insured',
        'crop_insurance_enrolled_date',
        'crop_insurance_end_date',
        'social_insurance',
        'provider_social_insurance',
        'social_insurance_enrolled_date',
        'social_insurance_end_date',
        'other_insurance',
    ];

    public function farmer_details()
    {
        return $this->belongsTo(FarmerDetails::class);
    }
}
