<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'name',
        'code',
        'capacity',
        'type',
        'lat',
        'lng',
        'address',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function generateCode()
    {
        if (empty($this->id)) {
            throw new \Exception('Warehouse must have ID in order to generate CODE');
        }

        $this->code = 'W' . str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    public function scopeActive($query)
    {   
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->whereNull('staff_id');
    }
}
