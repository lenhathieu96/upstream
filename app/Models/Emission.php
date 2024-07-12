<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emission extends Model
{
    use HasFactory;
    protected $fillable = [
        'carbon_emissions_id',
        'cultivation',
        'hgh',
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
        'co2_emission',
        'ch4_emission',
        'n20_emission',
        'ghg_emission',
        'carbon_foot_print',
    ];
}
