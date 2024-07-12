<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class IpBanned extends Model
{
    use HasFactory;
    protected $table = 'ip_banned';
    protected $fillable = ['ip'];

    public static function getListIpBanned()
    {
        return Cache::remember('ip_banned', 86400, function () { // remember cache for 1 day
            return IpBanned::pluck('ip')->toArray();
        });
    }

    public static function clearIpCache()
    {
        Cache::forget('ip_banned');
    }
}
