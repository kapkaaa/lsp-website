<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            'id' => 1,
            'order_id' => 1,
            'user_id' => 3,
            'payment_method' => 'transfer',
            'midtrans_order_id' => 'MID-001',
            'midtrans_transaction_id' => 'TRX-001',
            'midtrans_transaction_status' => 'SUCCESS',
            'midtrans_payment_type' => 'BANK_TRANSFER',
            'gross_amount' => 210000,
            'va_number' => '1234567890',
            'pdf_url' => null,
            'income' => 210000,
            'profit' => 40000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}