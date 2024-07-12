<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DataTables;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_name',
        'country_code',
        'status'
    ];

    public function province()
    {
        return $this->hasMany(Province::class);
    }

    public function farmerDetails()
    {
        return $this->hasMany(FarmerDetails::class, 'country', 'id');
    }
}
