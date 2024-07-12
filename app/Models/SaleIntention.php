<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleIntention extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'farm_land_id',
        'cultivation_id',
        'season_id',
        'variety',
        'sowing_date',
        'quantity',
        'min_price',
        'max_price',
        'product_id',
        'date_for_harvest',
        'aviable_date',
        'grade',
        'age_of_crop',
        'quality_check',
        'photo',
        'lat',
        'lng',
        'pre_harvest_qc',
    ];

    protected $casts = [
        'pre_harvest_qc' => 'array',
    ];

    public function farmer()
    {
        return $this->belongsTo(FarmerDetails::class, 'farmer_id', 'id');
    }

    public function farm_land()
    {
        return $this->belongsTo(FarmLand::class, 'farm_land_id', 'id');
    }

    public function cultivation()
    {
        return $this->belongsTo(Cultivations::class, 'cultivation_id', 'id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }
   
}
