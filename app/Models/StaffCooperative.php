<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffCooperative extends Model
{
    use HasFactory;
    protected $table = 'staff_cooperatives';
    protected $fillable = ['staff_id', 'cooperative_id'];

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }
}
