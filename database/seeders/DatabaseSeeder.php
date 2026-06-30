<?php

namespace Database\Seeders;

use Database\Seeders\InventorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductVariantSeeder;
use Database\Seeders\StoreProductSeeder;
use Database\Seeders\StoreSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
            StoreSeeder::class,
            ProductVariantSeeder::class,
            StoreProductSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
