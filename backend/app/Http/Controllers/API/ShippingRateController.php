<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    /**
     * Display a listing of shipping rates.
     */
    public function index()
    {
        $rates = ShippingRate::all();
        
        return response()->json([
            'data' => $rates,
            'message' => 'Shipping rates retrieved successfully'
        ]);
    }
}
