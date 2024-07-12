<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivities extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'type',
        'code',
        'request_log',
        'action',
        'status_code',
        'status_msg',
        'lat',
        'lng',
    ];
}
