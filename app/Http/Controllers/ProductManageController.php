<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductManageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->withCount('variants');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->trim()->toString();
            $query->where(function ($builder) use ($keyword): void {
                $builder->where('product_code', 'like', "%{$keyword}%")
                    ->orWhere('product_name', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        $products = $query->orderBy('product_code')->get();
        $categories = Product::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        $latestUpdatedAt = $products->max('updated_at');

        return view('product-manage', [
            'sidebarCurrentPage' => 'product',
            'products' => $products,
            'categories' => $categories,
            'productCount' => $products->count(),
            'lastUpdatedAt' => $latestUpdatedAt?->format('Y/m/d H:i') ?? now()->format('Y/m/d H:i'),
            'filters' => [
                'q' => $request->string('q')->toString(),
                'category' => $request->string('category')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }

    public function toggle(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return redirect()
            ->route('product.manage')
            ->with('success', '商品情報を更新しました');
    }
}
