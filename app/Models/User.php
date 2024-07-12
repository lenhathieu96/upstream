<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $with = ['farmer_detail'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_type',
        'username',
        'email',
        'password',
        'phone_number',
        'email_verified_at',
        'banned'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    public function farm_land()
    {
        return $this->hasMany(FarmLand::class);
    }

    public function farmer_detail()
    {
        return $this->hasOne(FarmerDetails::class);
    }

    public function staff_detail()
    {
        return $this->hasOne(FarmerDetails::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id', 'id');
    }
}
