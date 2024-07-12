<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'accout_type',
        'accout_no',
        'bank_name',
        'branch_details',
        'sort_code',
    ];
    
    public function farmer_details()
    {
        return $this->belongsTo(FarmerDetails::class);
    }

}
