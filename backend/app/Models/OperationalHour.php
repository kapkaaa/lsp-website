<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OperationalHour extends Model
{
    protected $fillable = [
        'service_type', 'day', 'open_time', 
        'close_time', 'status'
    ];
    
    // Check if currently operational
    public static function isOperational($serviceType = 'store')
    {
        $today = strtolower(Carbon::now()->format('l')); // monday, tuesday, etc
        
        $hour = self::where('service_type', $serviceType)
                    ->where('day', $today)
                    ->first();
        
        if (!$hour || $hour->status !== 'open') {
            return false;
        }
        
        $now = Carbon::now()->format('H:i:s');
        return $now >= $hour->open_time && $now <= $hour->close_time;
    }
}