<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->insert([
            ['id' => 1, 'name' => 'DistroZone', 'information' => 'Local brand', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'StreetWear', 'information' => 'Premium distro', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'nama', 'information' => 'keterangann', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Priders Heartquarter', 'information' => 'Officiall merchandise MCPR', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Abigail', 'information' => 'Abigail clothing brand', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}