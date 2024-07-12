<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_id',
        'province_name',
        'province_code',
        'status'
    ];

    public function district()
    {
        return $this->hasMany(District::class);
    }
}
