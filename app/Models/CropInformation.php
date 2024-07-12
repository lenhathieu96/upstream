<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CropInformation extends Model
{
    use HasFactory;

    protected $table = 'crop_informations';

    protected $fillable = ['name','photo','crop_category_code','duration','duration_type','expected_expense','expected_income','expected_yield',];

    const DURATION_TYPE = [
        'days' => 'Day(s)',
        'months' => 'Month(s)',
        'years' => 'Year(s)',
    ];

    const EXPECTED_YIELD_TYPE = [
        'hectare'=> 'Hectare',
        'acre'   => 'Acre'
    ];

    public function crop_category()
    {
        return $this->belongsTo(CropCategory::class, 'crop_category_code', 'code');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Uploads::class,'photo','id');
    }

    public function crop_variety()
    {
        return $this->hasMany(CropVariety::class,'crop_id','id');
    }

    public function crop_stages()
    {
        return $this->hasMany(CropStage::class, 'crop_information_id','id');
    }

    public function crop_calendars()
    {
        return $this->hasMany(CropCalendar::class, 'crop_info_id', 'id');
    }

    public function getPhotoUrlAttribute()
    {
        if (!empty($this->thumbnail)) {
            return asset($this->thumbnail->file_name);
        }
        
        return '';
    }

    public function cultivations(): HasMany
    {
        return $this->hasMany(Cultivations::class, 'crop_id');
    }
}
