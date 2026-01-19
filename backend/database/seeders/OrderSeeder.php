<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            'id' => 1,
            'buyer_id' => 3,
            'approved_by' => 5,
            'shipping_rate_id' => 1,
            'order_code' => 'ORD-001',
            'subtotal' => 190000,
            'weight' => 1,
            'shipping_cost' => 20000,
            'total_payment' => 210000,
            'destination_city' => 'Jakarta',
            'payment_proof' => 'test',
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'payment_method' => 'transfer',
            'rejection_reason' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}