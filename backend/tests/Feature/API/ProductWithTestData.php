<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Type;

class ProductWithTestDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API endpoint untuk mendapatkan daftar produk dengan data
     *
     * @return void
     */
    public function test_can_get_products_list_with_data()
    {
        // Buat produk dengan relasi yang diperlukan
        $brand = Brand::factory()->create();
        $type = Type::factory()->create();
        
        $product = Product::factory()->create([
            'brand_id' => $brand->id,
            'type_id' => $type->id
        ]);

        // Lakukan request GET ke endpoint produk
        $response = $this->getJson('/api/products');

        // Periksa bahwa respons berhasil (status 200)
        $response->assertStatus(200);
        
        // Cetak isi respons untuk debugging
        $responseContent = json_decode($response->getContent(), true);
        
        // Periksa bahwa respons berisi struktur dasar
        $this->assertArrayHasKey('data', $responseContent);
        $this->assertArrayHasKey('message', $responseContent);
        
        // Harusnya ada produk dalam data
        $this->assertNotEmpty($responseContent['data']);
    }
}