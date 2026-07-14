<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            ['store_code' => 'S-01', 'store_name' => '渋谷店', 'address' => '東京都渋谷区1-1-1', 'phone_number' => '03-0000-0001', 'status' => 'open'],
            ['store_code' => 'S-02', 'store_name' => '新宿店', 'address' => '東京都新宿区2-2-2', 'phone_number' => '03-0000-0002', 'status' => 'open'],
            ['store_code' => 'S-03', 'store_name' => '池袋店', 'address' => '東京都豊島区3-3-3', 'phone_number' => '03-0000-0003', 'status' => 'open'],
        ];

        foreach ($stores as $store) {
            Store::query()->updateOrCreate(
                ['store_code' => $store['store_code']],
                $store
            );
        }
    }
}
