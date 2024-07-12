<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonEmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'farmland_id',
        'season_id',
        'cultivation_id',
        'staff_id',
    ];


    public function emission()
    {
        return $this->hasOne(Emission::class,'carbon_emissions_id','id');
    }

    public function product_loss()
    {
        return $this->hasOne(ProductLoss::class,'carbon_emissions_id','id');
    }

    public function carbon_stage()
    {
        return $this->hasOne(CarbonStage::class,'carbon_emissions_id','id');
    }
    
}
