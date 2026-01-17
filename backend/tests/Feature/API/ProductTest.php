<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Type;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API endpoint untuk mendapatkan daftar produk
     *
     * @return void
     */
    public function test_can_get_products_list()
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

        // Periksa bahwa respons berisi data produk
        $this->assertArrayHasKey('data', $responseContent);
        $this->assertArrayHasKey('message', $responseContent);

        // Debug: cetak isi response
        // var_dump($responseContent);

        // Harusnya ada produk dalam data
        if (empty($responseContent['data'])) {
            $this->fail('Tidak ada produk dalam response, padahal kita sudah membuat produk');
        }

        // Periksa struktur elemen pertama
        $firstProduct = $responseContent['data'][0];
        $this->assertArrayHasKey('id', $firstProduct);
        $this->assertArrayHasKey('name', $firstProduct);
        $this->assertArrayHasKey('selling_price', $firstProduct);
        $this->assertArrayHasKey('cost_price', $firstProduct);
    }
}