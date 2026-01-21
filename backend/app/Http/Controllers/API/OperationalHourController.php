<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OperationalHour;
use Illuminate\Http\Request;

class OperationalHourController extends Controller
{
    public function checkStatus(Request $request)
    {
        $serviceType = $request->get('service_type', 'online');
        
        $isOperational = OperationalHour::isOperational($serviceType);
        $message = OperationalHour::getOperationalMessage($serviceType);
        $todayHours = OperationalHour::getTodayOperationalHours($serviceType);

        return response()->json([
            'success' => true,
            'is_operational' => $isOperational,
            'message' => $message,
            'hours' => $todayHours ? [
                'open_time' => $todayHours->open_time,
                'close_time' => $todayHours->close_time,
                'status' => $todayHours->status
            ] : null,
            'service_type' => $serviceType
        ]);
    }
}