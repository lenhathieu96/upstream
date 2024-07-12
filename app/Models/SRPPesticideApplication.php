<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPPesticideApplication extends Model
{
    use HasFactory;
    protected $table = 'srp_pesticide_applications';
    protected $fillable = [
        'farmer_id',
        'cultivation_id',
        'staff_id',
        'srp_id',
        'section',
        'collection_code',
        'title',
        'type',
        'question',
        'answer',
        'score',
        'created_at',
    ];
}
