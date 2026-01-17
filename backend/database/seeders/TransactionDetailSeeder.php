<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionDetailSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            ['id' => 1, 'transaction_id' => 1, 'quantity' => 2, 'unit_price' => 150000, 'subtotal' => 300000, 'product_detail_id' => 1],
            ['id' => 2, 'transaction_id' => 2, 'quantity' => 2, 'unit_price' => 100000, 'subtotal' => 200000, 'product_detail_id' => 3],
            ['id' => 3, 'transaction_id' => 3, 'quantity' => 1, 'unit_price' => 100000, 'subtotal' => 100000, 'product_detail_id' => 3],
            ['id' => 4, 'transaction_id' => 3, 'quantity' => 2, 'unit_price' => 150000, 'subtotal' => 300000, 'product_detail_id' => 1],
            ['id' => 5, 'transaction_id' => 4, 'quantity' => 1, 'unit_price' => 150000, 'subtotal' => 150000, 'product_detail_id' => 1],
            ['id' => 6, 'transaction_id' => 4, 'quantity' => 2, 'unit_price' => 300000, 'subtotal' => 600000, 'product_detail_id' => 2],
            ['id' => 7, 'transaction_id' => 5, 'quantity' => 1, 'unit_price' => 150000, 'subtotal' => 150000, 'product_detail_id' => 1],
            ['id' => 8, 'transaction_id' => 5, 'quantity' => 2, 'unit_price' => 300000, 'subtotal' => 600000, 'product_detail_id' => 2],
            ['id' => 9, 'transaction_id' => 6, 'quantity' => 1, 'unit_price' => 300000, 'subtotal' => 300000, 'product_detail_id' => 2],
            ['id' => 10, 'transaction_id' => 6, 'quantity' => 2, 'unit_price' => 150000, 'subtotal' => 300000, 'product_detail_id' => 1],
            ['id' => 11, 'transaction_id' => 7, 'quantity' => 4, 'unit_price' => 150000, 'subtotal' => 600000, 'product_detail_id' => 1],
            ['id' => 12, 'transaction_id' => 8, 'quantity' => 1, 'unit_price' => 150000, 'subtotal' => 150000, 'product_detail_id' => 1],
            ['id' => 13, 'transaction_id' => 9, 'quantity' => 1, 'unit_price' => 200000, 'subtotal' => 200000, 'product_detail_id' => 4],
            ['id' => 14, 'transaction_id' => 10, 'quantity' => 1, 'unit_price' => 150000, 'subtotal' => 150000, 'product_detail_id' => 1],
            ['id' => 15, 'transaction_id' => 11, 'quantity' => 2, 'unit_price' => 200000, 'subtotal' => 400000, 'product_detail_id' => 4],
            ['id' => 16, 'transaction_id' => 14, 'quantity' => 3, 'unit_price' => 150000, 'subtotal' => 450000, 'product_detail_id' => 1],
            ['id' => 17, 'transaction_id' => 14, 'quantity' => 1, 'unit_price' => 100000, 'subtotal' => 100000, 'product_detail_id' => 3],
        ];

        foreach ($details as $detail) {
            DB::table('transaction_details')->insert(array_merge($detail, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}