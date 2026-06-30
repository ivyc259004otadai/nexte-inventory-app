<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storeIds = Store::query()->pluck('id', 'store_code');
        $variantIds = ProductVariant::query()->pluck('id', 'sku');

        $inventories = [
            ['store_code' => 'S-01', 'sku' => 'NX-SH-001-WHT-M', 'current_stock' => 0, 'required_stock' => 20, 'weekly_sales' => 5, 'last_checked_at' => '2026-06-30', 'note' => '欠品確認'],
            ['store_code' => 'S-01', 'sku' => 'NX-SH-001-BLK-L', 'current_stock' => 8, 'required_stock' => 15, 'weekly_sales' => 10, 'last_checked_at' => '2026-06-30', 'note' => '要補充候補'],
            ['store_code' => 'S-01', 'sku' => 'NX-PT-002-BEG-M', 'current_stock' => 12, 'required_stock' => 12, 'weekly_sales' => 3, 'last_checked_at' => '2026-06-30', 'note' => '基準在庫付近'],
            ['store_code' => 'S-01', 'sku' => 'NX-PT-002-NVY-L', 'current_stock' => 48, 'required_stock' => 10, 'weekly_sales' => 12, 'last_checked_at' => '2026-06-30', 'note' => '過剰気味'],
            ['store_code' => 'S-02', 'sku' => 'NX-KN-003-IVR-S', 'current_stock' => 10, 'required_stock' => 10, 'weekly_sales' => 0, 'last_checked_at' => '2026-06-30', 'note' => '週間販売数ゼロ'],
            ['store_code' => 'S-02', 'sku' => 'NX-KN-003-RED-M', 'current_stock' => 4, 'required_stock' => 6, 'weekly_sales' => 2, 'last_checked_at' => '2026-06-30', 'note' => '在庫過少'],
            ['store_code' => 'S-02', 'sku' => 'NX-DJ-004-DEN-M', 'current_stock' => 20, 'required_stock' => 14, 'weekly_sales' => 6, 'last_checked_at' => '2026-06-30', 'note' => '適正在庫'],
            ['store_code' => 'S-02', 'sku' => 'NX-DJ-004-DEN-L', 'current_stock' => 40, 'required_stock' => 8, 'weekly_sales' => 10, 'last_checked_at' => '2026-06-30', 'note' => '過剰在庫'],
            ['store_code' => 'S-03', 'sku' => 'NX-SH-001-WHT-M', 'current_stock' => 2, 'required_stock' => 5, 'weekly_sales' => 3, 'last_checked_at' => '2026-06-30', 'note' => '在庫過少'],
            ['store_code' => 'S-03', 'sku' => 'NX-TS-005-WHT-S', 'current_stock' => 0, 'required_stock' => 8, 'weekly_sales' => 4, 'last_checked_at' => '2026-06-30', 'note' => '欠品'],
            ['store_code' => 'S-03', 'sku' => 'NX-TS-005-GRY-M', 'current_stock' => 16, 'required_stock' => 14, 'weekly_sales' => 5, 'last_checked_at' => '2026-06-30', 'note' => '適正在庫'],
            ['store_code' => 'S-03', 'sku' => 'NX-KN-003-RED-M', 'current_stock' => 60, 'required_stock' => 12, 'weekly_sales' => 15, 'last_checked_at' => '2026-06-30', 'note' => '過剰在庫'],
        ];

        foreach ($inventories as $inventory) {
            Inventory::query()->updateOrCreate(
                [
                    'store_id' => $storeIds[$inventory['store_code']],
                    'product_variant_id' => $variantIds[$inventory['sku']],
                ],
                [
                    'current_stock' => $inventory['current_stock'],
                    'required_stock' => $inventory['required_stock'],
                    'weekly_sales' => $inventory['weekly_sales'],
                    'last_checked_at' => $inventory['last_checked_at'],
                    'note' => $inventory['note'],
                ]
            );
        }
    }
}
