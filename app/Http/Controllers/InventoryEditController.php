<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InventoryEditController extends Controller
{
    public function __invoke(Request $request, Inventory $inventory): View|RedirectResponse
    {
        $inventory->load(['productVariant.product', 'store']);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'required_stock' => ['required', 'integer', 'min:0'],
                'desired_order_quantity' => ['required', 'integer', 'min:0'],
                'weekly_sales' => ['required', 'integer', 'min:0'],
                'last_checked_at' => ['nullable', 'date'],
                'note' => ['nullable', 'string', 'max:255'],
            ]);

            $supplementCandidates = max(
                (int) $validated['required_stock'] - (int) $inventory->current_stock,
                0
            );

            if ((int) $validated['desired_order_quantity'] < $supplementCandidates) {
                return back()
                    ->withErrors([
                        'desired_order_quantity' =>
                        '補充候補数より少ない値は指定できません',
                    ])
                    ->withInput();
            }

            $inventory->update($validated);

            return redirect()
                ->route('inventory.edit', [
                    'inventory' => $inventory,
                    'from' => $request->query('from'),
                ])
                ->with('success', '在庫情報を更新しました');
        }

        $currentStock = (int) $inventory->current_stock;
        $requiredStock = (int) $inventory->required_stock;
        $weeklySales = (int) $inventory->weekly_sales;

        $weeks = $weeklySales > 0 ? $currentStock / $weeklySales : null;
        $supplementCandidates = max($requiredStock - $currentStock, 0);
        $desiredOrderQuantity = $inventory->desired_order_quantity ?? $supplementCandidates;
        $difference = $currentStock - $requiredStock;

        $status = $this->resolveStatus(
            $currentStock,
            $requiredStock,
            $weeks
        );

        return view('inventory-edit', [
            'sidebarCurrentPage' => 'inventory',
            'inventory' => $inventory,
            'status' => $status,
            'difference' => $difference,
            'weeksText' => $weeks === null ? '算出不可' : number_format($weeks, 1) . '週',
            'supplementCandidates' => $supplementCandidates,
            'desiredOrderQuantity' => $desiredOrderQuantity,
            'productName' => $inventory->productVariant?->product?->product_name ?? '-',
            'sku' => $inventory->productVariant?->sku ?? '-',
            'color' => $inventory->productVariant?->color ?? '-',
            'size' => $inventory->productVariant?->size ?? '-',
            'storeName' => $inventory->store?->store_name ?? '-',
        ]);
    }

    private function resolveStatus(int $currentStock, int $requiredStock, ?float $weeks): array
    {
        if ($currentStock === 0) {
            return ['status' => 'D', 'label' => 'D：欠品', 'tone' => 'danger'];
        }

        if ($currentStock < $requiredStock || ($weeks !== null && $weeks < 1)) {
            return ['status' => 'C', 'label' => 'C：在庫過少', 'tone' => 'warning'];
        }

        if ($currentStock > $requiredStock && $weeks !== null && $weeks >= 4) {
            return ['status' => 'B', 'label' => 'B：在庫過多', 'tone' => 'violet'];
        }

        return ['status' => 'A', 'label' => 'A：適正在庫', 'tone' => 'success'];
    }
}
