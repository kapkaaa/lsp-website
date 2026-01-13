<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OperationalHour;

class CheckOperationalHours
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if current time is within operational hours
        if (!OperationalHour::isOperational('online')) {
            $message = OperationalHour::getOperationalMessage('online');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 403);
            }

            return redirect()->route('customer.home')
                ->with('warning', $message);
        }

        return $next($request);
    }
}