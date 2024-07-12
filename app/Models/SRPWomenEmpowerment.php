<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPWomenEmpowerment extends Model
{
    use HasFactory;
    protected $table = 'srp_women_empowerments';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'question',
        'title',
        'type',
        'answer',
        'score',
        'created_at',
    ];
}
