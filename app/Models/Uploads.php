<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uploads extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_original_name', 'file_name', 'user_id', 'user_type', 'extension', 'type', 'file_size',
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /**
     * get farmer for user_type = farmer
     */
    public function farmer()
    {
    	return $this->belongsTo(FarmerDetails::class, 'user_id', 'id')->where('user_type', 'farmer');
    }
}
