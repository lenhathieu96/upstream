<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPWaterManagement extends Model
{
    use HasFactory;
    protected $table = 'srp_water_managements';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'collection_code',
        'question',
        'title',
        'type',
        'answer',
        'score',
        'created_at',
    ];
}
