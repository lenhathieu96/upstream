<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmLandLatLng extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'farm_land_id',
        'order',
        'lat',
        'lng',
    ];

    public function farm_land()
    {
        return $this->belongsTo(FarmLand::class);
    }
   
}
