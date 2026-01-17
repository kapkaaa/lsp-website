<?php

// database/seeders/ProductPhotoSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            ['id' => 3, 'product_detail_id' => 4, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/2/product_2_5f2fd542-1468-4c62-ab2f-951a8b28b057.jpg'],
            ['id' => 4, 'product_detail_id' => 4, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/4/product_4_bbd0726c-2d5c-4234-a279-d2052cf941f4.png'],
            ['id' => 5, 'product_detail_id' => 4, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/4/product_4_e895801d-87fc-4909-bcea-818479b41bab.png'],
            ['id' => 6, 'product_detail_id' => 6, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/6/product_6_bcfa4f12-b613-4f24-ab8f-9ae10a0374fc.png'],
            ['id' => 7, 'product_detail_id' => 7, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/7/product_7_c9487019-fdd3-4763-9e38-2d1f596a0143.png'],
            ['id' => 9, 'product_detail_id' => 8, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/8/product_8_932fd642-72b0-4986-a417-4e07f7842238.png'],
            ['id' => 10, 'product_detail_id' => 8, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/8/product_8_910649c2-ab7d-4d1d-9a25-a2debaeef378.png'],
            ['id' => 11, 'product_detail_id' => 10, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/10/product_10_26ecfca9-91e8-4174-a13c-15253b69bea3.png'],
            ['id' => 16, 'product_detail_id' => 19, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_cb5c308f-9456-4380-8c80-e27e4f9d65c9.png'],
            ['id' => 17, 'product_detail_id' => 19, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_c0435cfa-0a4f-4d91-ac55-48ceeb419a8e.png'],
            ['id' => 18, 'product_detail_id' => 20, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_a1b6221b-4ad2-4e5e-b0e8-e1591e31f3e9.png'],
            ['id' => 19, 'product_detail_id' => 20, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_f45b8176-e217-48a9-a80b-92d31dc0e5b0.png'],
            ['id' => 20, 'product_detail_id' => 21, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_01fa0403-8bf3-4008-bb23-d7c737dbd8a3.png'],
            ['id' => 21, 'product_detail_id' => 21, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_263815ad-b8e4-460d-877c-3c38b693a4ee.png'],
            ['id' => 22, 'product_detail_id' => 22, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_0da5c0b9-3402-4a04-9ae4-a6c4dbd041e6.png'],
            ['id' => 23, 'product_detail_id' => 22, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_813a1dc6-349f-4240-b254-8215a23a0008.png'],
            ['id' => 24, 'product_detail_id' => 23, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_21996dcb-de83-4552-a01b-315ad6a3149c.jpg'],
            ['id' => 35, 'product_detail_id' => 35, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/9/product_9_f54259d4-cdb5-449a-9494-5eb357bfb980.jpg'],
            ['id' => 37, 'product_detail_id' => 37, 'photo_url' => 'https://qvjfgnkmrhpgimkszpnm.supabase.co/storage/v1/object/public/product-images/products/3/product_3_bf19f8a6-742a-4259-90cf-17649830f6d2.jpg'],
            ['id' => 38, 'product_detail_id' => 38, 'photo_url' => 'products/6apN89P7bkqRPIFGdQ6wmoFqI9g2NCtd0u6QjmhO.jpg'],
            ['id' => 39, 'product_detail_id' => 38, 'photo_url' => 'products/3LayeNtlUUxYWvutQCJEYSTbpSdOPKFqpx7H6tIi.jpg'],
            ['id' => 40, 'product_detail_id' => 38, 'photo_url' => 'products/TYI9MiatodwmCVho2gXXFPi7yj1AQi19712eO9jG.jpg'],
            ['id' => 41, 'product_detail_id' => 38, 'photo_url' => 'products/hdneZ84tPGxIXJcFJ1o12pqn5bzmtovHGfwK1odC.jpg'],
            ['id' => 42, 'product_detail_id' => 39, 'photo_url' => 'products/LEUnpacc3HqzvIxM4KWC8zhHuPQlSoXYJUHC329o.jpg'],
            ['id' => 43, 'product_detail_id' => 39, 'photo_url' => 'products/T3vZ750NdYUQGVuW1gk8ymiP1yv6zPcR5dPp7H0N.jpg'],
            ['id' => 44, 'product_detail_id' => 40, 'photo_url' => 'products/skTIdIOnbh1fcLQQtCxZEtwFVDNTEAZiQ59YhCyP.jpg'],
            ['id' => 45, 'product_detail_id' => 40, 'photo_url' => 'products/z4g9v4ovxQknVGUpgBFvsvobFmW8W44fNrbSxO40.jpg'],
            ['id' => 46, 'product_detail_id' => 41, 'photo_url' => 'products/XAgqbq5f4hOhOfjJeunu5zbzAjBT2dOwVyVo2gNY.jpg'],
            ['id' => 47, 'product_detail_id' => 41, 'photo_url' => 'products/L1tqo3gj40B9nxCAaj7No9J4jWHQ9rHwu98cWo27.jpg'],
        ];

        foreach ($photos as $photo) {
            DB::table('product_photos')->insert(array_merge($photo, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}