<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Base tables (order matters due to foreign keys)
            RoleSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            TypeSeeder::class,
            SizeSeeder::class,
            ColorSeeder::class,
            ShippingRateSeeder::class,
            OperationalHourSeeder::class,
            
            // Products
            ProductSeeder::class,
            ProductDetailSeeder::class,
            ProductPhotoSeeder::class,
            
            // Transactions
            OrderSeeder::class,
            OrderDetailSeeder::class,
            PaymentSeeder::class,
            TransactionSeeder::class,
            TransactionDetailSeeder::class,
            
            // Customer Service
            CustomerServiceChatSeeder::class,
        ]);
    }
}