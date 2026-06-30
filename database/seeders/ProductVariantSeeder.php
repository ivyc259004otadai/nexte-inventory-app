<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productIds = Product::query()->pluck('id', 'product_code');

        $variants = [
            ['product_code' => 'P-1001', 'sku' => 'NX-SH-001-WHT-M', 'color' => 'White', 'size' => 'M'],
            ['product_code' => 'P-1001', 'sku' => 'NX-SH-001-BLK-L', 'color' => 'Black', 'size' => 'L'],
            ['product_code' => 'P-1002', 'sku' => 'NX-PT-002-BEG-M', 'color' => 'Beige', 'size' => 'M'],
            ['product_code' => 'P-1002', 'sku' => 'NX-PT-002-NVY-L', 'color' => 'Navy', 'size' => 'L'],
            ['product_code' => 'P-1003', 'sku' => 'NX-KN-003-IVR-S', 'color' => 'Ivory', 'size' => 'S'],
            ['product_code' => 'P-1003', 'sku' => 'NX-KN-003-RED-M', 'color' => 'Red', 'size' => 'M'],
            ['product_code' => 'P-1004', 'sku' => 'NX-DJ-004-DEN-M', 'color' => 'Denim', 'size' => 'M'],
            ['product_code' => 'P-1004', 'sku' => 'NX-DJ-004-DEN-L', 'color' => 'Denim', 'size' => 'L'],
            ['product_code' => 'P-1005', 'sku' => 'NX-TS-005-WHT-S', 'color' => 'White', 'size' => 'S'],
            ['product_code' => 'P-1005', 'sku' => 'NX-TS-005-GRY-M', 'color' => 'Gray', 'size' => 'M'],
        ];

        foreach ($variants as $variant) {
            ProductVariant::query()->updateOrCreate(
                ['sku' => $variant['sku']],
                [
                    'product_id' => $productIds[$variant['product_code']],
                    'color' => $variant['color'],
                    'size' => $variant['size'],
                ]
            );
        }
    }
}
