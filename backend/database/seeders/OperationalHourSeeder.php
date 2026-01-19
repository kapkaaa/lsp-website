<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalHourSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $data = [];
        
        // Store operational hours
        foreach ($days as $day) {
            $openTime = $day === 'Rabu' ? '08:00:00' : '10:00:00';
            $data[] = [
                'service_type' => 'Store',
                'day' => $day,
                'open_time' => $openTime,
                'close_time' => '20:00:00',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Website operational hours
        foreach ($days as $day) {
            $data[] = [
                'service_type' => 'Website',
                'day' => $day,
                'open_time' => '10:00:00',
                'close_time' => '17:00:00',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('operational_hours')->insert($data);
    }
}
