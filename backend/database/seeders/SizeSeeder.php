<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sizes')->insert([
            ['id' => 1, 'name' => 'S', 'information' => 'Small', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'M', 'information' => 'Medium', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'L', 'information' => 'Large', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'XL', 'information' => 'extra largee', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'XXL', 'information' => 'Extra Extra Large', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'XS', 'information' => 'Extra Small', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => '3XL', 'information' => 'Triple Extra Large', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => '4XL', 'information' => 'Four Extra Large', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => '5XL', 'information' => 'Five extra large', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
