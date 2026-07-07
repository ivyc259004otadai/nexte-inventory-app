<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreFormController extends Controller
{
    public function __invoke(Request $request, ?Store $store = null): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'store_code' => ['required', 'string', 'max:10'],
                'store_name' => ['required', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:50'],
                'phone_number' => ['nullable', 'string', 'max:20'],
                'status' => ['required', 'in:open,suspended,closed'],
            ]);

            $store = $store ?? new Store();
            $store->fill($validated)->save();

            return redirect()
                ->route('store.manage')
                ->with('success', $store->wasRecentlyCreated ? '店舗情報を登録しました' : '店舗情報を更新しました');
        }

        return view('store-form', [
            'sidebarCurrentPage' => 'store',
            'store' => $store,
            'pageTitle' => $store ? '店舗編集' : '店舗登録',
        ]);
    }
}
