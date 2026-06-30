<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Database\Seeder;

class StoreProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storeIds = Store::query()->pluck('id', 'store_code');
        $productIds = Product::query()->pluck('id', 'product_code');

        $relations = [
            ['store_code' => 'S-01', 'product_code' => 'P-1001'],
            ['store_code' => 'S-01', 'product_code' => 'P-1002'],
            ['store_code' => 'S-01', 'product_code' => 'P-1003'],
            ['store_code' => 'S-02', 'product_code' => 'P-1001'],
            ['store_code' => 'S-02', 'product_code' => 'P-1003'],
            ['store_code' => 'S-02', 'product_code' => 'P-1004'],
            ['store_code' => 'S-03', 'product_code' => 'P-1002'],
            ['store_code' => 'S-03', 'product_code' => 'P-1004'],
            ['store_code' => 'S-03', 'product_code' => 'P-1005'],
        ];

        foreach ($relations as $relation) {
            StoreProduct::query()->updateOrCreate(
                [
                    'store_id' => $storeIds[$relation['store_code']],
                    'product_id' => $productIds[$relation['product_code']],
                ],
                ['is_active' => true]
            );
        }
    }
}
