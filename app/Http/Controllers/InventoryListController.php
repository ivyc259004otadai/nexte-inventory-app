<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InventoryListController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = Inventory::query()
            ->with(['productVariant.product', 'store']);

        $stores = Store::query()->orderBy('store_name')->get();
        $categories = Inventory::query()
            ->with('productVariant.product')
            ->get()
            ->map(fn(Inventory $inventory) => $inventory->productVariant?->product?->category)
            ->filter()
            ->unique()
            ->values();

        if ($request->filled('q')) {
            $keyword = $request->string('q')->trim()->toString();

            $query->where(function ($builder) use ($keyword): void {
                $builder->whereHas('productVariant.product', function ($productQuery) use ($keyword): void {
                    $productQuery->where('product_name', 'like', "%{$keyword}%")
                        ->orWhere('product_code', 'like', "%{$keyword}%");
                })
                    ->orWhereHas('productVariant', function ($variantQuery) use ($keyword): void {
                        $variantQuery->where('sku', 'like', "%{$keyword}%")
                            ->orWhere('color', 'like', "%{$keyword}%")
                            ->orWhere('size', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('store', function ($storeQuery) use ($keyword): void {
                        $storeQuery->where('store_name', 'like', "%{$keyword}%")
                            ->orWhere('store_code', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('store')) {
            $query->whereHas('store', function ($storeQuery) use ($request): void {
                $storeQuery->where('store_code', $request->string('store')->toString());
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('productVariant.product', function ($productQuery) use ($request): void {
                $productQuery->where('category', $request->string('category')->toString());
            });
        }

        $inventories = $query->get();

        $inventoryRows = $inventories
            ->map(fn(Inventory $inventory) => $this->formatInventory($inventory))
            ->filter(function (array $row) use ($request): bool {
                if (! $request->filled('status')) {
                    return true;
                }

                return $row['status'] === $request->string('status')->toString();
            })
            ->sortBy(fn(array $row) => sprintf('%02d-%s-%s', $row['status_order'], $row['store_name'], $row['sku']))
            ->values();

        $latestUpdatedAt = $inventories->max('updated_at');

        return view('inventory-list', [
            'sidebarCurrentPage' => 'inventory',
            'inventoryCount' => $inventoryRows->count(),
            'lastUpdatedAt' => $latestUpdatedAt?->format('Y/m/d H:i') ?? now()->format('Y/m/d H:i'),
            'stores' => $stores,
            'categories' => $categories,
            'inventoryRows' => $inventoryRows,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'store' => $request->string('store')->toString(),
                'status' => $request->string('status')->toString(),
                'category' => $request->string('category')->toString(),
            ],
        ]);
    }

    private function formatInventory(Inventory $inventory): array
    {
        $productVariant = $inventory->productVariant;
        $product = $productVariant?->product;
        $store = $inventory->store;

        $currentStock = (int) $inventory->current_stock;
        $requiredStock = (int) $inventory->required_stock;
        $weeklySales = (int) $inventory->weekly_sales;
        $weeks = $weeklySales > 0 ? $currentStock / $weeklySales : null;

        $status = $this->resolveStatus($currentStock, $requiredStock, $weeks);
        $difference = $currentStock - $requiredStock;

        return [
            'id' => $inventory->id,
            'status' => $status['status'],
            'status_label' => $status['label'],
            'status_tone' => $status['tone'],
            'status_order' => $status['order'],
            'product_name' => $product?->product_name ?? '商品名未設定',
            'sku' => $productVariant?->sku ?? '-',
            'color' => $productVariant?->color ?? '-',
            'size' => $productVariant?->size ?? '-',
            'store_name' => $store?->store_name ?? '-',
            'current_stock' => $currentStock,
            'required_stock' => $requiredStock,
            'weekly_sales' => $weeklySales,
            'difference' => $difference,
            'difference_label' => $difference > 0 ? '+' . $difference : (string) $difference,
        ];
    }

    private function resolveStatus(int $currentStock, int $requiredStock, ?float $weeks): array
    {
        if ($currentStock === 0) {
            return ['status' => 'D', 'label' => '欠品', 'tone' => 'danger', 'order' => 0];
        }

        if ($currentStock < $requiredStock || ($weeks !== null && $weeks < 1)) {
            return ['status' => 'C', 'label' => '在庫過少', 'tone' => 'warning', 'order' => 1];
        }

        if ($currentStock > $requiredStock && $weeks !== null && $weeks >= 4) {
            return ['status' => 'B', 'label' => '在庫過多', 'tone' => 'violet', 'order' => 3];
        }

        return ['status' => 'A', 'label' => '適正在庫', 'tone' => 'success', 'order' => 2];
    }
}
