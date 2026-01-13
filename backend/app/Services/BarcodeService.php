<?php

namespace App\Services;

use App\Models\ProductDetail;

class BarcodeService
{
    /**
     * Generate unique 13-digit barcode
     * 
     * @return string
     */
    public function generate()
    {
        do {
            $barcode = $this->generateBarcode();
        } while ($this->exists($barcode));

        return $barcode;
    }

    /**
     * Generate random 13-digit number
     * 
     * @return string
     */
    private function generateBarcode()
    {
        return (string) mt_rand(1000000000000, 9999999999999);
    }

    /**
     * Check if barcode already exists
     * 
     * @param string $barcode
     * @return bool
     */
    private function exists($barcode)
    {
        return ProductDetail::where('barcode', $barcode)->exists();
    }

    /**
     * Validate barcode format
     * 
     * @param string $barcode
     * @return bool
     */
    public function validate($barcode)
    {
        // Check if barcode is 13 digits
        return preg_match('/^\d{13}$/', $barcode) === 1;
    }

    /**
     * Find product by barcode
     * 
     * @param string $barcode
     * @return ProductDetail|null
     */
    public function findProduct($barcode)
    {
        return ProductDetail::with(['product', 'size', 'color'])
            ->where('barcode', $barcode)
            ->first();
    }

    /**
     * Generate barcode for product variant
     * Uses format: [ProductID][SizeID][ColorID][Random]
     * 
     * @param int $productId
     * @param int $sizeId
     * @param int $colorId
     * @return string
     */
    public function generateForVariant($productId, $sizeId, $colorId)
    {
        // Create a unique identifier from the variant
        $prefix = str_pad($productId, 4, '0', STR_PAD_LEFT);
        $prefix .= str_pad($sizeId, 2, '0', STR_PAD_LEFT);
        $prefix .= str_pad($colorId, 2, '0', STR_PAD_LEFT);
        
        // Add random digits to make it 13 digits
        $random = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $barcode = $prefix . $random;

        // Ensure uniqueness
        while ($this->exists($barcode)) {
            $random = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $barcode = $prefix . $random;
        }

        return $barcode;
    }
}