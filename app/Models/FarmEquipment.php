<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'farm_equipment_items',
        'farm_equipment_items_count',
        'year_of_manufacture',
        'year_of_purchase',
    ];
    public function farmer_details()
    {
        return $this->belongsTo(FarmerDetails::class);
    }
}
