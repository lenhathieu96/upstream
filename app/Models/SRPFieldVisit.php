<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPFieldVisit extends Model
{
    use HasFactory;
    protected $table = 'srp_field_visits';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'title',
        'type',
        'question',
        'answer',
        'score',
    ];
}
