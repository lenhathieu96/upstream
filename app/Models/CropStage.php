<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropStage extends Model
{
    use HasFactory;
    protected $table = 'crop_stages';
    protected $fillable = ['name', 'status'];
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function crop_information()
    {
        return $this->belongsTo(CropInformation::class);
    }

    public function crop_variety()
    {
        return $this->belongsTo(CropVariety::class);
    }
}
