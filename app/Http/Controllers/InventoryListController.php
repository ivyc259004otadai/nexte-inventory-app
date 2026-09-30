<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryListController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = Inventory::query()
            ->with(['productVariant.product', 'store']);

        $stores = Store::query()->orderBy('store_name')->get();
        $selectedStoreCode = $request->string('store')->toString();
        $displayScope = $selectedStoreCode === ''
            ? '全店舗'
            : ($stores->firstWhere('store_code', $selectedStoreCode)?->store_name ?? '該当店舗なし');

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

        $filteredRows = $inventories
            ->map(fn(Inventory $inventory) => $this->formatInventory($inventory))
            ->filter(function (array $row) use ($request): bool {
                if (! $request->filled('status')) {
                    return true;
                }

                return $row['status'] === $request->string('status')->toString();
            })
            ->sortBy(fn(array $row) => sprintf(
                '%02d-%02d-%s-%s',
                $row['order_decision_order'],
                $row['status_order'],
                $row['store_name'],
                $row['sku']
            ))
            ->values();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $inventoryRows = new LengthAwarePaginator(
            $filteredRows->forPage($currentPage, $perPage)->values(),
            $filteredRows->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
            ]
        );

        $inventoryRows->appends($request->except('page'));

        $inventoryRows->withQueryString();

        $latestUpdatedAt = $inventories->max('updated_at');

        return view('inventory-list', [
            'sidebarCurrentPage' => 'inventory',
            'displayScope' => $displayScope,
            'inventoryCount' => $filteredRows->count(),
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

        $supplementCandidates = max($requiredStock - $currentStock, 0);
        $desiredOrderQuantity = $inventory->desired_order_quantity;

        if ($desiredOrderQuantity === null) {
            $orderDecision = [
                'status' => 'pending',
                'label' => '未',
                'tone' => 'danger',
                'order' => 0,
            ];
        } elseif ((int) $desiredOrderQuantity < $supplementCandidates) {
            $orderDecision = [
                'status' => 'insufficient',
                'label' => '不足',
                'tone' => 'warning',
                'order' => 0,
            ];
        } else {
            $orderDecision = [
                'status' => 'completed',
                'label' => '済',
                'tone' => 'success',
                'order' => 1,
            ];
        }

        return [
            'id' => $inventory->id,
            'status' => $status['status'],
            'status_label' => $status['label'],
            'status_tone' => $status['tone'],
            'status_symbol' => $status['symbol'],
            'status_order' => $status['order'],
            'product_name' => $product?->product_name ?? '商品名未設定',
            'product_code' => $product?->product_code ?? '-',
            'sku' => $productVariant?->sku ?? '-',
            'color' => $productVariant?->color ?? '-',
            'size' => $productVariant?->size ?? '-',
            'store_name' => $store?->store_name ?? '-',
            'current_stock' => $currentStock,
            'required_stock' => $requiredStock,
            'weekly_sales' => $weeklySales,
            'difference' => $difference,
            'difference_label' => $difference > 0 ? '+' . $difference : (string) $difference,
            'supplement_candidates' => $supplementCandidates,
            'desired_order_quantity' => $desiredOrderQuantity,
            'order_decision_status' => $orderDecision['status'],
            'order_decision_label' => $orderDecision['label'],
            'order_decision_tone' => $orderDecision['tone'],
            'order_decision_order' => $orderDecision['order'],
        ];
    }

    private function resolveStatus(int $currentStock, int $requiredStock, ?float $weeks): array
    {
        if ($currentStock === 0) {
            return ['status' => 'D', 'label' => '欠品', 'tone' => 'danger', 'symbol' => '×', 'order' => 0];
        }

        if ($currentStock < $requiredStock || ($weeks !== null && $weeks < 1)) {
            return ['status' => 'C', 'label' => '在庫過少', 'tone' => 'warning', 'symbol' => '⚠', 'order' => 1];
        }

        if ($currentStock > $requiredStock && $weeks !== null && $weeks >= 4) {
            return ['status' => 'B', 'label' => '在庫過多', 'tone' => 'violet', 'symbol' => '▲', 'order' => 2];
        }

        return ['status' => 'A', 'label' => '適正在庫', 'tone' => 'success', 'symbol' => '○', 'order' => 3];
    }
}
