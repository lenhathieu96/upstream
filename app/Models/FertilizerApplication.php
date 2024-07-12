<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FertilizerApplication extends Model
{
    use HasFactory;
    protected $table = 'srp_fertilizer_applications';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'srp_id',
        'question',
        'answer',
        'collection_code',
    ];
}
