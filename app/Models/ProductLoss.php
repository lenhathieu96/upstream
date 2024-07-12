<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLoss extends Model
{
    use HasFactory;
    protected $fillable = [
       'carbon_emissions_id',
        'yield_before_harvest',
        'harvesting_losses',
        'drying_losses',
        'storing_losses',
        'milling_losses',
        'food_losses',
        'husk',
        'bran',
        'rice_straw',
        'rice_husk',
        'rice_bran',
        'total_product_loss',
    ];
}
