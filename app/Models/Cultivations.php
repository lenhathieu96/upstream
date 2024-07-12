<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Cultivations extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    use HasFactory;
    protected $table = 'cultivations';
    protected $appends = ['crop_name', 'photo_url'];

    protected $fillable = [
        'farm_land_id',
        'season_id',
        'crop_id',
        'crop_variety',
        'sowing_date',
        'expect_date',
        'est_yield',
        'photo',
        'parcel_id'
    ];

    public function farm_land()
    {
        return $this->belongsTo(FarmLand::class,'farm_land_id','id');
    }

    public function season()
    {
        return $this->belongsTo(SeasonMaster::class,'season_id','id');
    }

    public function crops_master(): BelongsTo
    {
        return $this->belongsTo(CropInformation::class,'crop_id','id');
    }

    public function carbon_emission()
    {
        return $this->hasOne(CarbonEmission::class,'cultivation_id','id')->where('season_id',$this->season_id);
    }

    public function srp()
    {
        return $this->hasOne(SRP::class,'cultivation_id','id')->where('season_id',$this->season_id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function setCropVarietyAttribute($value)
    {
        if ($value == 'OM18') {
            $this->attributes['crop_variety'] = 'OM 18';
        } else {
            $this->attributes['crop_variety'] = $value;
        }
    }

    public function getPhotoUrlAttribute()
    {
        $upload = Uploads::find($this->attributes['photo']);
        if ($upload) {
            return asset($upload->file_name);
        }
        return null;
    }

    public function getCropNameAttribute()
    {
        $crop = CropInformation::find($this->crop_id);
        return $crop?->name;
    }

    public function cropHarvestDetail(): HasOne
    {
        return $this->hasOne(CropHarvestDetail::class, 'cultivation_id');
    }
}
