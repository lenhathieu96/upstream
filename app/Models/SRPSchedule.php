<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRPSchedule extends Model
{

    use HasFactory;
    protected $table = 'srp_schedules';
    protected $fillable = [
        'srp_id',
        'name_action',
        'date_action',
        'is_finished',
        'score',
    ];

    public function srp()
    {
        return $this->belongsTo(SRP::class, 'srp_id', 'id');
    }
}
