<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PresentationInventorySeeder extends Seeder
{
    /**
     * Create or reset only the inventory data reserved for the October 5 presentation.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $store = Store::query()
                ->where('store_code', 'S-02')
                ->where('status', 'open')
                ->first();

            if ($store === null) {
                throw new RuntimeException('Open presentation store S-02 was not found.');
            }

            $products = [
                [
                    'product_code' => 'DEMO1005-P01',
                    'product_name' => 'ソフトカーディガン',
                    'category' => 'トップス',
                    'variants' => [
                        ['sku' => 'DEMO1005-CD-IVR-S', 'color' => 'Ivory', 'size' => 'S', 'current_stock' => 0, 'required_stock' => 10, 'weekly_sales' => 4, 'note' => '欠品表示デモ'],
                        ['sku' => 'DEMO1005-CD-BLU-M', 'color' => 'Blue', 'size' => 'M', 'current_stock' => 8, 'required_stock' => 12, 'weekly_sales' => 4, 'note' => '基準在庫変更デモ'],
                    ],
                ],
                [
                    'product_code' => 'DEMO1005-P02',
                    'product_name' => 'ワイドパンツ',
                    'category' => 'ボトムス',
                    'variants' => [
                        ['sku' => 'DEMO1005-PT-BEG-M', 'color' => 'Beige', 'size' => 'M', 'current_stock' => 6, 'required_stock' => 10, 'weekly_sales' => 3, 'note' => '希望発注数保存デモ'],
                        ['sku' => 'DEMO1005-PT-NVY-L', 'color' => 'Navy', 'size' => 'L', 'current_stock' => 0, 'required_stock' => 8, 'weekly_sales' => 2, 'note' => '欠品表示デモ'],
                    ],
                ],
                [
                    'product_code' => 'DEMO1005-P03',
                    'product_name' => '軽量ブルゾン',
                    'category' => 'アウター',
                    'variants' => [
                        ['sku' => 'DEMO1005-BL-KHK-M', 'color' => 'Khaki', 'size' => 'M', 'current_stock' => 3, 'required_stock' => 7, 'weekly_sales' => 2, 'note' => '在庫過少表示デモ'],
                        ['sku' => 'DEMO1005-BL-BLK-L', 'color' => 'Black', 'size' => 'L', 'current_stock' => 5, 'required_stock' => 9, 'weekly_sales' => 2, 'note' => '在庫過少表示デモ'],
                    ],
                ],
            ];

            foreach ($products as $productData) {
                $product = Product::query()->updateOrCreate(
                    ['product_code' => $productData['product_code']],
                    [
                        'product_name' => $productData['product_name'],
                        'category' => $productData['category'],
                        'status' => 'active',
                    ]
                );

                StoreProduct::query()->updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'product_id' => $product->id,
                    ],
                    ['is_active' => true]
                );

                foreach ($productData['variants'] as $variantData) {
                    $variant = ProductVariant::query()->updateOrCreate(
                        ['sku' => $variantData['sku']],
                        [
                            'product_id' => $product->id,
                            'color' => $variantData['color'],
                            'size' => $variantData['size'],
                        ]
                    );

                    Inventory::query()->updateOrCreate(
                        [
                            'store_id' => $store->id,
                            'product_variant_id' => $variant->id,
                        ],
                        [
                            'current_stock' => $variantData['current_stock'],
                            'required_stock' => $variantData['required_stock'],
                            'desired_order_quantity' => null,
                            'weekly_sales' => $variantData['weekly_sales'],
                            'last_checked_at' => '2026-09-29',
                            'note' => $variantData['note'],
                        ]
                    );
                }
            }
        });
    }
}
