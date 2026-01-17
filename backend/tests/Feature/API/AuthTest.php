<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * Test API endpoint untuk registrasi pengguna
     *
     * @return void
     */
    public function test_can_register_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'customer'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User registered successfully',
            'user' => [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'customer'
            ]
        ]);

        // Verifikasi bahwa pengguna benar-benar dibuat di database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    /**
     * Test API endpoint untuk login pengguna
     *
     * @return void
     */
    public function test_can_login_user()
    {
        // Buat pengguna dummy
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'user',
            'token'
        ]);
    }
}