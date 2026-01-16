<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            [
                'id' => 1,
                'user_id' => 2,
                'transaction_code' => 'TRX-KSR-001',
                'total' => 300000,
                'payment_method' => 'cash',
                'transaction_status' => 'completed',
                'cash_received' => null,
                'change_given' => null,
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'transaction_code' => 'TRX20251217212743',
                'total' => 200000,
                'payment_method' => 'qris',
                'transaction_status' => 'completed',
                'cash_received' => null,
                'change_given' => null,
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'transaction_code' => 'TRX20251219143953',
                'total' => 400000,
                'payment_method' => 'cash',
                'transaction_status' => 'completed',
                'cash_received' => null,
                'change_given' => null,
            ],
            [
                'id' => 4,
                'user_id' => 2,
                'transaction_code' => 'TRX20251219145422',
                'total' => 750000,
                'payment_method' => 'qris',
                'transaction_status' => 'completed',
                'cash_received' => null,
                'change_given' => null,
            ],
            [
                'id' => 5,
                'user_id' => 2,
                'transaction_code' => 'TRX20251219150049',
                'total' => 750000,
                'payment_method' => 'transfer',
                'transaction_status' => 'completed',
                'cash_received' => null,
                'change_given' => null,
            ],
            [
                'id' => 6,
                'user_id' => 2,
                'transaction_code' => 'TRX20251219153153',
                'total' => 600000,
                'payment_method' => 'cash',
                'transaction_status' => 'completed',
                'cash_received' => 700000,
                'change_given' => 100000,
            ],
            [
                'id' => 7,
                'user_id' => 2,
                'transaction_code' => 'TRX20251219153246',
                'total' => 600000,
                'payment_method' => 'cash',
                'transaction_status' => 'completed',
                'cash_received' => 600000,
                'change_given' => 0,
            ],
            [
                'id' => 8,
                'user_id' => 2,
                'transaction_code' => 'TRX20251219154029',
                'total' => 150000,
                'payment_method' => 'qris',
                'transaction_status' => 'completed',
                'cash_received' => 150000,
                'change_given' => 0,
            ],
            [
                'id' => 9,
                'user_id' => 5,
                'transaction_code' => 'TRX20251222194455',
                'total' => 200000,
                'payment_method' => 'qris',
                'transaction_status' => 'completed',
                'cash_received' => 200000,
                'change_given' => 0,
            ],
            [
                'id' => 10,
                'user_id' => 2,
                'transaction_code' => 'TRX20251222194623',
                'total' => 150000,
                'payment_method' => 'cash',
                'transaction_status' => 'completed',
                'cash_received' => 200000,
                'change_given' => 50000,
            ],
            [
                'id' => 11,
                'user_id' => 2,
                'transaction_code' => 'TRX20251222194816',
                'total' => 400000,
                'payment_method' => 'transfer',
                'transaction_status' => 'completed',
                'cash_received' => 400000,
                'change_given' => 0,
            ],
            [
                'id' => 14,
                'user_id' => 5,
                'transaction_code' => 'TRX20251225163732',
                'total' => 550000,
                'payment_method' => 'qris',
                'transaction_status' => 'completed',
                'cash_received' => 550000,
                'change_given' => 0,
            ],
        ];

        foreach ($transactions as $transaction) {
            DB::table('transactions')->insert(array_merge($transaction, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}