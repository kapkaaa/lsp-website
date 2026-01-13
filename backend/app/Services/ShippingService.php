<?php

namespace App\Services;

use App\Models\ShippingRate;

class ShippingService
{
    /**
     * Calculate shipping cost based on destination and quantity
     * 
     * @param int $shippingRateId
     * @param int $totalItems
     * @return array
     */
    public function calculateShipping($shippingRateId, $totalItems)
    {
        $shippingRate = ShippingRate::findOrFail($shippingRateId);
        
        // Calculate weight (3 items per kg, round up)
        $weight = ceil($totalItems / 3);
        
        // Calculate cost
        $cost = $weight * $shippingRate->price_per_kg;
        
        return [
            'region' => $shippingRate->region,
            'weight' => $weight,
            'price_per_kg' => $shippingRate->price_per_kg,
            'total_cost' => $cost
        ];
    }

    /**
     * Get all available shipping rates
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableRates()
    {
        return ShippingRate::orderBy('region')->get();
    }

    /**
     * Validate if region is available for shipping
     * 
     * @param string $city
     * @return bool
     */
    public function isRegionAvailable($city)
    {
        $availableRegions = [
            'Jakarta', 'Depok', 'Bekasi', 'Tangerang', 'Bogor',
            'Jawa Barat', 'Jawa Tengah', 'Jawa Timur'
        ];

        foreach ($availableRegions as $region) {
            if (stripos($city, $region) !== false || stripos($region, $city) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get shipping rate by city name
     * 
     * @param string $city
     * @return ShippingRate|null
     */
    public function getRateByCity($city)
    {
        // Exact match first
        $rate = ShippingRate::where('region', 'like', "%{$city}%")->first();
        
        if ($rate) {
            return $rate;
        }

        // Check if city is in a province
        $provinceMapping = [
            'Bandung' => 'Jawa Barat',
            'Semarang' => 'Jawa Tengah',
            'Surabaya' => 'Jawa Timur',
            // Add more city to province mapping as needed
        ];

        if (isset($provinceMapping[$city])) {
            return ShippingRate::where('region', $provinceMapping[$city])->first();
        }

        return null;
    }
}