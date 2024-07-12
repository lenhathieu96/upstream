<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmLand extends Model
{
    use HasFactory;

    protected $table = 'farm_lands';
    //protected $appends = ['total_land_holding_ha', 'actual_area_ha'];
    
    // protected $with = ['some_field_farmer_details'];
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'farmer_id',
        'farm_name',
        'total_land_holding',
        'lat',
        'lng',
        'farm_land_ploting',
        'actual_area',
        'farm_photo',
        'land_ownership',
        'srp_score',
        'carbon_index',
        'approach_road',
        'land_topology',
        'land_gradient',
        'land_document',
        'season_id',
        'status',
    ];

    public function farmer_details()
    {
        return $this->belongsTo(FarmerDetails::class,'farmer_id','id');
    }

    public function some_field_farmer_details()
    {
        return $this->belongsTo(FarmerDetails::class,'farmer_id','id')->select(['full_name', 'farmer_code','farmer_photo']);
    }

    public function farm_land_lat_lng()
    {
        return $this->hasMany(FarmLandLatLng::class,'farm_land_id','id');
    }

    public function cultivation()
    {
        return $this->hasMany(Cultivations::class,'farm_land_id','id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    
    public function getFarmPhotoUrlAttribute()
    {
        $photoIds = explode(',', $this->farm_photo);
        $url = [];
        foreach ($photoIds as $photoId) {
            $upload = Uploads::find($photoId);
            if ($upload) {
                $url[] = asset($upload->file_name);
            }
        }

        return $url;
    }

    public function getAreaAttribute()
    {
        $data =round($this->actual_area,2);
        return $data;
    }

    public function getTotalLandHoldingHaAttribute()
    {
        return $this->total_land_holding;
    }
    
    public function getActualAreaHaHaAttribute()
    {
        return $this->actual_area;
    }
}
