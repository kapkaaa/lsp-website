<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        $productDetails = [
            ['id' => 1, 'size_id' => 2, 'color_id' => 1, 'stock' => 37, 'product_id' => 1, 'status' => 'available', 'barcode' => ''],
            ['id' => 2, 'size_id' => 3, 'color_id' => 2, 'stock' => 15, 'product_id' => 2, 'status' => 'available', 'barcode' => ''],
            ['id' => 3, 'size_id' => 4, 'color_id' => 3, 'stock' => 2, 'product_id' => 3, 'status' => 'available', 'barcode' => ''],
            ['id' => 4, 'size_id' => 3, 'color_id' => 1, 'stock' => 12, 'product_id' => 4, 'status' => 'available', 'barcode' => ''],
            ['id' => 6, 'size_id' => 3, 'color_id' => 3, 'stock' => 10, 'product_id' => 5, 'status' => 'available', 'barcode' => ''],
            ['id' => 7, 'size_id' => 3, 'color_id' => 3, 'stock' => 5, 'product_id' => 6, 'status' => 'available', 'barcode' => ''],
            ['id' => 8, 'size_id' => 3, 'color_id' => 3, 'stock' => 5, 'product_id' => 7, 'status' => 'available', 'barcode' => ''],
            ['id' => 9, 'size_id' => 3, 'color_id' => 1, 'stock' => 20, 'product_id' => 7, 'status' => 'available', 'barcode' => ''],
            ['id' => 10, 'size_id' => 3, 'color_id' => 3, 'stock' => 10, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 11, 'size_id' => 1, 'color_id' => 4, 'stock' => 2, 'product_id' => 9, 'status' => 'out_of_stock', 'barcode' => ''],
            ['id' => 12, 'size_id' => 2, 'color_id' => 4, 'stock' => 5, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 13, 'size_id' => 3, 'color_id' => 4, 'stock' => 5, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 14, 'size_id' => 4, 'color_id' => 4, 'stock' => 2, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 19, 'size_id' => 1, 'color_id' => 6, 'stock' => 3, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 20, 'size_id' => 2, 'color_id' => 6, 'stock' => 5, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 21, 'size_id' => 3, 'color_id' => 6, 'stock' => 6, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 22, 'size_id' => 4, 'color_id' => 6, 'stock' => 2, 'product_id' => 9, 'status' => 'available', 'barcode' => ''],
            ['id' => 23, 'size_id' => 2, 'color_id' => 2, 'stock' => 2, 'product_id' => 9, 'status' => 'available', 'barcode' => '8138062817747'],
            ['id' => 35, 'size_id' => 1, 'color_id' => 7, 'stock' => 1, 'product_id' => 9, 'status' => 'available', 'barcode' => '4345621677603'],
            ['id' => 37, 'size_id' => 4, 'color_id' => 1, 'stock' => 5, 'product_id' => 3, 'status' => 'available', 'barcode' => '5035537635543'],
            ['id' => 38, 'size_id' => 3, 'color_id' => 2, 'stock' => 8, 'product_id' => 14, 'status' => 'available', 'barcode' => '4452176580276'],
            ['id' => 39, 'size_id' => 2, 'color_id' => 1, 'stock' => 5, 'product_id' => 14, 'status' => 'available', 'barcode' => '6255390234070'],
            ['id' => 40, 'size_id' => 1, 'color_id' => 1, 'stock' => 4, 'product_id' => 14, 'status' => 'available', 'barcode' => '1046327032755'],
            ['id' => 41, 'size_id' => 3, 'color_id' => 1, 'stock' => 5, 'product_id' => 14, 'status' => 'available', 'barcode' => '4182779932271'],
        ];

        foreach ($productDetails as $detail) {
            DB::table('product_details')->insert(array_merge($detail, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}