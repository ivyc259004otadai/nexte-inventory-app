<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['product_code' => 'P-1001', 'product_name' => 'オーバーサイズシャツ', 'category' => 'トップス', 'status' => 'active'],
            ['product_code' => 'P-1002', 'product_name' => 'テーパードパンツ', 'category' => 'ボトムス', 'status' => 'active'],
            ['product_code' => 'P-1003', 'product_name' => 'リブニットトップス', 'category' => 'トップス', 'status' => 'active'],
            ['product_code' => 'P-1004', 'product_name' => 'デニムジャケット', 'category' => 'アウター', 'status' => 'active'],
            ['product_code' => 'P-1005', 'product_name' => 'ロゴTシャツ', 'category' => 'トップス', 'status' => 'active'],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['product_code' => $product['product_code']],
                $product
            );
        }
    }
}
