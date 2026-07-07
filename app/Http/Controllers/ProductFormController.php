<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductFormController extends Controller
{
    public function __invoke(Request $request, ?Product $product = null): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'product_code' => ['required', 'string', 'max:50'],
                'product_name' => ['required', 'string', 'max:50'],
                'category' => ['nullable', 'string', 'max:50'],
                'status' => ['required', 'in:active,suspended,discontinued'],
            ]);

            $product = $product ?? new Product();
            $product->fill($validated)->save();

            return redirect()
                ->route('product.manage')
                ->with('success', $product->wasRecentlyCreated ? '商品情報を登録しました' : '商品情報を更新しました');
        }

        $categories = Product::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('product-form', [
            'sidebarCurrentPage' => 'product',
            'product' => $product,
            'categories' => $categories,
            'pageTitle' => $product ? '商品編集' : '商品登録',
        ]);
    }
}
