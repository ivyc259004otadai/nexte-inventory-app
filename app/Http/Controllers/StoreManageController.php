<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreManageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Store::query();

        $query->where('status', '!=', 'closed');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->trim()->toString();

            $query->where(function ($builder) use ($keyword): void {
                $builder->where('store_code', 'like', "%{$keyword}%")
                    ->orWhere('store_name', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $stores = $query->orderBy('store_code')->get();
        $latestUpdatedAt = $stores->max('updated_at');

        return view('store-manage', [
            'sidebarCurrentPage' => 'store',
            'stores' => $stores,
            'storeCount' => $stores->count(),
            'lastUpdatedAt' => $latestUpdatedAt?->format('Y/m/d H:i') ?? now()->format('Y/m/d H:i'),
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }
}
