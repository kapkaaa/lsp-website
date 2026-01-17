<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test untuk memastikan route API terdaftar
     *
     * @return void
     */
    public function test_api_routes_are_defined()
    {
        // Dapatkan semua route yang terdaftar
        $routes = Route::getRoutes();
        
        // Cari route yang berawalan /api/
        $apiRoutes = [];
        foreach ($routes as $route) {
            if (str_starts_with($route->uri, 'api/')) {
                $apiRoutes[] = $route->uri;
            }
        }
        
        // Pastikan ada route API yang terdaftar
        $this->assertNotEmpty($apiRoutes, 'Tidak ada route API yang ditemukan');
        
        // Cek apakah route products ada
        $this->assertContains('api/products', $apiRoutes, 'Route api/products tidak ditemukan');
    }
}