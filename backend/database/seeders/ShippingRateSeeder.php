<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shipping_rates')->insert([
            ['id' => 1, 'region' => 'Jakarta', 'price_per_kg' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'region' => 'Depok', 'price_per_kg' => 24000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'region' => 'Bekasi', 'price_per_kg' => 25000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'region' => 'Tangerang', 'price_per_kg' => 25000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'region' => 'Bogor', 'price_per_kg' => 27000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'region' => 'Jawa Barat', 'price_per_kg' => 31000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'region' => 'Jawa Tengah', 'price_per_kg' => 39000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'region' => 'Jawa Timur', 'price_per_kg' => 47000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}