<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'education',
        'marial_status',
        'parent_name',
        'spouse_name',
        'no_of_family',
        'total_child_under_18',
        'total_child_under_18_going_school',
    ];
}
