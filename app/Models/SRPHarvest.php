<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPHarvest extends Model
{
    use HasFactory;
    protected $table = 'srp_harvests';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'section',
        'title',
        'type',
        'collection_code',
        'question',
        'answer',
        'score',
        'created_at',
    ];
}
