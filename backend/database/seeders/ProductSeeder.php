<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPhoto;
use App\Models\Brand;
use App\Models\Type;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample brands if they don't exist
        $brand1 = Brand::firstOrCreate([
            'name' => 'Nike'
        ]);

        $brand2 = Brand::firstOrCreate([
            'name' => 'Adidas'
        ]);

        // Create sample types if they don't exist
        $type1 = Type::firstOrCreate([
            'name' => 'Shoes'
        ]);

        $type2 = Type::firstOrCreate([
            'name' => 'T-Shirt'
        ]);

        // Create sample products
        $product1 = Product::create([
            'name' => 'Nike Air Max',
            'brand_id' => $brand1->id,
            'type_id' => $type1->id,
            'cost_price' => 800000,
            'selling_price' => 1200000
        ]);

        $product2 = Product::create([
            'name' => 'Adidas Ultraboost',
            'brand_id' => $brand2->id,
            'type_id' => $type1->id,
            'cost_price' => 900000,
            'selling_price' => 1300000
        ]);

        // Create sample product details
        $detail1 = ProductDetail::create([
            'size_id' => 40,
            'color_id' => 1,
            'stock' => 10,
            'product_id' => $product1->id,
            'status' => true,
            'barcode' => 'NIKE40-1' // Adding required field
        ]);

        $detail2 = ProductDetail::create([
            'size_id' => 42,
            'color_id' => 2,
            'stock' => 5,
            'product_id' => $product1->id,
            'status' => true,
            'barcode' => 'NIKE42-2' // Adding required field
        ]);

        $detail3 = ProductDetail::create([
            'size_id' => 41,
            'color_id' => 1,
            'stock' => 8,
            'product_id' => $product2->id,
            'status' => true,
            'barcode' => 'ADIDAS41-1' // Adding required field
        ]);

        // Create sample product photos
        ProductPhoto::create([
            'product_detail_id' => $detail1->id,
            'photo_url' => 'https://example.com/nike-air-max-black.jpg'
        ]);

        ProductPhoto::create([
            'product_detail_id' => $detail1->id,
            'photo_url' => 'https://example.com/nike-air-max-black-side.jpg'
        ]);

        ProductPhoto::create([
            'product_detail_id' => $detail2->id,
            'photo_url' => 'https://example.com/nike-air-max-white.jpg'
        ]);

        ProductPhoto::create([
            'product_detail_id' => $detail3->id,
            'photo_url' => 'https://example.com/adidas-boost-blue.jpg'
        ]);
    }
}