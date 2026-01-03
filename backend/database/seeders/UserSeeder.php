<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        User::firstOrCreate([
            'username' => 'admin'
        ], [
            'role_id' => 1,
            'name' => 'Admin User',
            'password' => Hash::make('password123'),
            'nik' => '1234567890123456',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'city' => 'Jakarta',
            'phone' => '081234567890',
            'profile_photo' => null,
            'status' => true,
        ]);

        User::firstOrCreate([
            'username' => 'staff'
        ], [
            'role_id' => 2,
            'name' => 'Staff User',
            'password' => Hash::make('password123'),
            'nik' => '9876543210987654',
            'address' => 'Jl. Staff No. 2, Bandung',
            'city' => 'Bandung',
            'phone' => '082345678901',
            'profile_photo' => null,
            'status' => true,
        ]);

        User::firstOrCreate([
            'username' => 'customer'
        ], [
            'role_id' => 3,
            'name' => 'Customer User',
            'password' => Hash::make('password123'),
            'nik' => '5678901234567890',
            'address' => 'Jl. Customer No. 3, Surabaya',
            'city' => 'Surabaya',
            'phone' => '083456789012',
            'profile_photo' => null,
            'status' => true,
        ]);
    }
}