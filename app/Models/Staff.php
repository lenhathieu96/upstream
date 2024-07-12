<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    use HasFactory;

    protected $appends = ['name'];
    
    protected $fillable = [
        'user_id',
        'user_type',
        'first_name',
        'last_name',
        'gender',
        'email',
        'lat',
        'lng',
        'phone_number',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function farmer_details()
    {
        return $this->hasMany(FarmerDetails::class,'staff_id','id')->orderBy('created_at', 'DESC');
    }

    public function farmer_details_id()
    {
        return $this->hasMany(FarmerDetails::class,'staff_id','id')->select('id');
    }

    public function srp_schedule()
    {

        return $this->hasManyThrough(
            SRPSchedule::class, 
            SRP::class,
            'staff_id', // Foreign key on the environments table...
            'srp_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id');
    }

    public function getNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function farm_land(): HasManyThrough
    {
        return $this->hasManyThrough(
        FarmLand::class, 
        FarmerDetails::class,
        'staff_id', // Foreign key on the environments table...
        'farmer_id', // Foreign key on the deployments table...
        'id', // Local key on the projects table...
        'id')->join('cultivations','farm_lands.id','=','cultivations.farm_land_id')->select('cultivations.*');
    }

   

    public function farm_land_count(): HasManyThrough
    {
        return $this->hasManyThrough(
        FarmLand::class, 
        FarmerDetails::class,
        'staff_id', // Foreign key on the environments table...
        'farmer_id', // Foreign key on the deployments table...
        'id', // Local key on the projects table...
        'id');
    }

    public function faAccount(): HasOne
    {
        return $this->hasOne(FaAccount::class, 'staff_id', 'id');
    }

    public function cooperatives()
    {
        return $this->hasMany(Cooperative::class, 'staff_id', 'id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class);
    }
}
