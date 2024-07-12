<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'housing_ownership',
        'house_type',
        'consumer_electronic',
        'vehicle',
    ];
}
