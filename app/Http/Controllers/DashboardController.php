<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $inventories = Inventory::query()
            ->with(['productVariant.product', 'store'])
            ->get();

        $productCards = $inventories
            ->map(fn(Inventory $inventory) => $this->formatInventory($inventory))
            ->filter(
                fn(array $card) =>
                in_array($card['status'], ['D', 'C'], true)
                    && $card['desired_order_quantity'] === null
            )
            ->sortBy(fn(array $card) => sprintf('%02d-%06d-%s', $card['status_order'], $card['current_stock'], $card['sku']))
            ->values();

        $statusCounts = array_fill_keys(['D', 'C', 'A', 'B'], 0);

        foreach ($inventories as $inventory) {
            $statusCounts[$this->resolveStatus($inventory)['status']]++;
        }

        $latestUpdatedAt = $inventories->max('updated_at');

        return view('dashboard', [
            'displayScope' => '全店舗',
            'inventoryCount' => $inventories->count(),
            'lastUpdatedAt' => $latestUpdatedAt?->format('Y/m/d H:i') ?? now()->format('Y/m/d H:i'),
            'summaryCards' => [
                [
                    'title' => '欠品',
                    'count' => $statusCounts['D'],
                    'description' => '今すぐ対応が必要',
                    'tone' => 'danger',
                ],
                [
                    'title' => '在庫過少',
                    'count' => $statusCounts['C'],
                    'description' => '補充を検討してください',
                    'tone' => 'warning',
                ],
                [
                    'title' => '適正在庫',
                    'count' => $statusCounts['A'],
                    'description' => '問題ありません',
                    'tone' => 'success',
                ],
                [
                    'title' => '在庫過多',
                    'count' => $statusCounts['B'],
                    'description' => '発注を一時停止',
                    'tone' => 'violet',
                ],
            ],
            'productCards' => $productCards,
        ]);
    }

    private function formatInventory(Inventory $inventory): array
    {
        $status = $this->resolveStatus($inventory);
        $productVariant = $inventory->productVariant;
        $product = $productVariant?->product;
        $store = $inventory->store;

        $currentStock = (int) $inventory->current_stock;
        $requiredStock = (int) $inventory->required_stock;
        $weeklySales = (int) $inventory->weekly_sales;
        $supplementCandidates = max($requiredStock - $currentStock, 0);
        $difference = $currentStock - $requiredStock;

        return [
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
            'supplement_candidates' => $supplementCandidates,
            'desired_order_quantity' => $inventory->desired_order_quantity,
            'weekly_stock_weeks' => $status['weeks_text'],
            'inventory_id' => $inventory->id,
        ];
    }

    private function resolveStatus(Inventory $inventory): array
    {
        $currentStock = (int) $inventory->current_stock;
        $requiredStock = (int) $inventory->required_stock;
        $weeklySales = (int) $inventory->weekly_sales;
        $weeks = $weeklySales > 0 ? $currentStock / $weeklySales : null;

        if ($currentStock === 0) {
            return [
                'status' => 'D',
                'label' => 'D：欠品',
                'tone' => 'danger',
                'order' => 0,
                'weeks_text' => '算出不可',
            ];
        }

        if ($currentStock < $requiredStock || ($weeks !== null && $weeks < 1)) {
            return [
                'status' => 'C',
                'label' => 'C：在庫過少',
                'tone' => 'warning',
                'order' => 1,
                'weeks_text' => $weeks === null ? '算出不可' : number_format($weeks, 1),
            ];
        }

        if ($currentStock > $requiredStock && $weeks !== null && $weeks >= 4) {
            return [
                'status' => 'B',
                'label' => 'B：在庫過多',
                'tone' => 'violet',
                'order' => 3,
                'weeks_text' => number_format($weeks, 1),
            ];
        }

        return [
            'status' => 'A',
            'label' => 'A：適正在庫',
            'tone' => 'success',
            'order' => 2,
            'weeks_text' => $weeks === null ? '算出不可' : number_format($weeks, 1),
        ];
    }
}
