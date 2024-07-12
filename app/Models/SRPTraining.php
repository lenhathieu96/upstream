<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPTraining extends Model
{
    use HasFactory;
    protected $table = 'srp_trainings';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'title',
        'type',
        'question',
        'title',
        'type',
        'answer',
        'score',
        'created_at',
    ];
}
