<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'is_certified_farmer',
        'certification_type',
        'year_of_ics',
    ];
}
