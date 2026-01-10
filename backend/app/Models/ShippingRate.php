<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'region',
        'price_per_kg'
    ];

    protected $casts = [
        'price_per_kg' => 'integer'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Helper method untuk calculate shipping cost
    public function calculateShippingCost($totalItems)
    {
        // 1 kg = 3 kaos
        // Kurang dari 3 tetap dihitung 1 kg
        $weight = ceil($totalItems / 3);
        return $weight * $this->price_per_kg;
    }

    // Get available regions
    public static function getAvailableRegions()
    {
        return self::pluck('region', 'id');
    }
}