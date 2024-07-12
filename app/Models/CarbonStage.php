<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonStage extends Model
{
    use HasFactory;
    protected $fillable = [
        'carbon_emissions_id',
        'crop_establish',
        'water_soil',
        'fetilizer',
        'equipment',
        'harvesting',
        'straw_management',
        'drying',
        'storing',
        'milling',
        'packaging',
        'transports',
    ];
}
