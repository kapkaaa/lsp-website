<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// ============ OperationalHour Model ============
class OperationalHour extends Model
{
    protected $fillable = [
        'service_type',
        'day',
        'open_time',
        'close_time',
        'status'
    ];

    protected $casts = [
        'open_time' => 'datetime:H:i:s',
        'close_time' => 'datetime:H:i:s'
    ];

    // Helper Methods
    public static function isOperational($serviceType = 'Website')
    {
        $currentDay = Carbon::now()->format('l'); // Monday, Tuesday, etc
        $currentTime = Carbon::now()->format('H:i:s');

        $operationalHour = self::where('service_type', $serviceType)
            ->where('day', $currentDay)
            ->where('status', 'open')
            ->first();

        if (!$operationalHour) {
            return false;
        }

        $openTime = Carbon::parse($operationalHour->open_time)->format('H:i:s');
        $closeTime = Carbon::parse($operationalHour->close_time)->format('H:i:s');

        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }

    public static function getOperationalMessage($serviceType = 'online')
    {
        $currentDay = Carbon::now()->format('l');
        
        $operationalHour = self::where('service_type', $serviceType)
            ->where('day', $currentDay)
            ->first();

        if (!$operationalHour || $operationalHour->status === 'closed') {
            return 'Layanan tutup hari ini';
        }

        if (self::isOperational($serviceType)) {
            return 'Layanan buka: ' . 
                   Carbon::parse($operationalHour->open_time)->format('H:i') . ' - ' . 
                   Carbon::parse($operationalHour->close_time)->format('H:i');
        }

        return 'Layanan tutup. Buka jam: ' . 
               Carbon::parse($operationalHour->open_time)->format('H:i') . ' - ' . 
               Carbon::parse($operationalHour->close_time)->format('H:i');
    }

    public static function getTodayOperationalHours($serviceType = 'online')
    {
        $currentDay = Carbon::now()->format('l');
        
        return self::where('service_type', $serviceType)
            ->where('day', $currentDay)
            ->first();
    }

    public function isCurrentlyOpen()
    {
        $currentTime = Carbon::now()->format('H:i:s');
        $openTime = Carbon::parse($this->open_time)->format('H:i:s');
        $closeTime = Carbon::parse($this->close_time)->format('H:i:s');

        return $this->status === 'open' && 
               $currentTime >= $openTime && 
               $currentTime <= $closeTime;
    }
}