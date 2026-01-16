<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('colors')->insert([
            ['id' => 1, 'name' => 'Hitam', 'information' => 'Warna Hitam', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Putih', 'information' => 'Warna Putih', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'abu abu', 'information' => 'abu abu color', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'hytamm', 'information' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'biru', 'information' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'hijau', 'information' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Ungu', 'information' => 'Warna ungu', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}