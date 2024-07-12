<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = [
        'province_id',
        'district_name',
        'district_code',
        'status'
    ];
    public function commune()
    {
        return $this->hasMany(Commune::class);
    }
}
