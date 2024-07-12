<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPWaterIrrigation extends Model
{
    use HasFactory;
    protected $table = 'srp_water_irrigations';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'section',
        'collection_code',
        'question',
        'title',
        'type',
        'answer',
        'score',
        'created_at',
    ];
}
