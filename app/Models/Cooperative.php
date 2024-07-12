<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    use HasFactory;
    protected $table = 'cooperatives';
    protected $fillable = ['user_id', 'staff_id', 'name', 'formation_date', 'cooperative_code', 'status', 'services', 'email', 'phone_number'];


    public function generateCooperativeCode()
    {
        if (empty($this->id)) {
            throw new \Exception('Cooperative must have ID in order to generate CODE');
        }

        $this->cooperative_code = 'C' . str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
