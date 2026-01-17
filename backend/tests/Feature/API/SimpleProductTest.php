<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API endpoint untuk mendapatkan daftar produk
     *
     * @return void
     */
    public function test_can_access_products_endpoint()
    {
        // Lakukan request GET ke endpoint produk
        $response = $this->getJson('/api/products');

        // Periksa bahwa respons berhasil (status 200)
        $response->assertStatus(200);
        
        // Cetak isi respons untuk debugging
        $responseContent = json_decode($response->getContent(), true);
        
        // Periksa bahwa respons berisi struktur dasar
        $this->assertArrayHasKey('data', $responseContent);
        $this->assertArrayHasKey('message', $responseContent);
    }
}