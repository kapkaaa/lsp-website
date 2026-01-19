<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('types')->insert([
            ['id' => 1, 'name' => 'Kaos Lengan Pendek', 'information' => 'Kaos dengan Lengan Pendek', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Hoodie', 'information' => 'Long sleeve hoodie', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'nama', 'information' => 'anama', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Kaos Lengan Panjang', 'information' => 'Kaos dengan lengan panjang yo', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}