<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropCalendar extends Model
{
    use HasFactory;
    protected $table = 'crop_calendars';

    public function cropCalendarDetails()
    {
        return $this->hasMany(CropCalendarDetail::class, 'crop_calendar_id', 'id');
    }

    public function cropInformation()
    {
        return $this->belongsTo(CropInformation::class,'crop_info_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class,'district_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
