<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPhoto;
use App\Models\Brand;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'brand_id' => 1,
                'type_id' => 1,
                'name' => 'T-Shirt DZ Black M',
                'selling_price' => 150000,
                'cost_price' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'brand_id' => 2,
                'type_id' => 2,
                'name' => 'Hoodie SW White L',
                'selling_price' => 300000,
                'cost_price' => 220000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'brand_id' => 3,
                'type_id' => 3,
                'name' => 'nama',
                'selling_price' => 100000,
                'cost_price' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'brand_id' => 4,
                'type_id' => 1,
                'name' => 'Punk Is Attitude',
                'selling_price' => 200000,
                'cost_price' => 170000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'brand_id' => 4,
                'type_id' => 1,
                'name' => 'nama',
                'selling_price' => 50000,
                'cost_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'brand_id' => 1,
                'type_id' => 2,
                'name' => 'nama',
                'selling_price' => 15,
                'cost_price' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'brand_id' => 4,
                'type_id' => 1,
                'name' => 'nama product',
                'selling_price' => 30000,
                'cost_price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'brand_id' => 4,
                'type_id' => 1,
                'name' => 'test',
                'selling_price' => 15000,
                'cost_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'brand_id' => 7,
                'type_id' => 1,
                'name' => 'SISSY',
                'selling_price' => 190000,
                'cost_price' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}