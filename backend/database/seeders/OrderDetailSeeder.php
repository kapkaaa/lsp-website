<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_details')->insert([
            'id' => 1,
            'order_id' => 1,
            'product_detail_id' => 38,
            'quantity' => 1,
            'unit_price' => 190000,
            'total' => 190000,
            'product_id' => 14,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}