<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropVariety extends Model
{
    use HasFactory;
    protected $fillable = [
        'crop_id',
        'name',
    ];
}
