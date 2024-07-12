<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalHusbandry extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'farm_animal',
        'animal_count',
        'fodder',
        'animal_housing',
        'revenue',
        'breed_name',
        'animal_for_growth',
    ];

    public function farmer_details()
    {
        return $this->belongsTo(FarmerDetails::class);
    }
}
