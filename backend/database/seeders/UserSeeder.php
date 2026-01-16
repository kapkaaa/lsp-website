<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'role_id' => 1,
                'name' => 'Admin Distro',
                'username' => 'admin',
                'password' => '$2y$12$r8A8X6l/I7J/APGIUcMh4.wjCNYC4YhS.gYM9OQIZlgbL5.g3A7xi',
                'nik' => '1234567890',
                'address' => 'Jl. Admin',
                'city' => 'Bandung',
                'phone' => '0811111111',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'role_id' => 2,
                'name' => 'Kasir Distro',
                'username' => 'kasir',
                'password' => '$2y$12$5HFu7.VPiEu.G1FlH2Qq0eN3HE0dSWOEZyo19xucDfZUX2ZGAQc.6',
                'nik' => '0987654321',
                'address' => 'Jl. Kasir',
                'city' => 'Bandung',
                'phone' => '0822222222',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'role_id' => 3,
                'name' => 'Budi',
                'username' => 'budi',
                'password' => '$2a$12$r8A8X6l/I7J/APGIUcMh4.wjCNYC4YhS.gYM9OQIZlgbL5.g3A7xi',
                'nik' => '1122334455',
                'address' => 'Jl. Customer',
                'city' => 'Jakarta',
                'phone' => '0833333333',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}