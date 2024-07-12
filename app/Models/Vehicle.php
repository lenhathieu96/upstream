<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'type_id',
        'license_number',
        'driver_name',
        'driver_phone_number',
        'capacity',
        'driver_photo',
        'driver_id_photo',
        'document',
        'status',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'vehicle_id');
    }
}