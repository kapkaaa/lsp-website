<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'information' => 'Full access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Cashier', 'information' => 'Handle transactions', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Customer', 'information' => 'Buyer role', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

