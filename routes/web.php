<?php

use App\Http\Controllers\InventoryEditController;
use App\Http\Controllers\InventoryListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductFormController;
use App\Http\Controllers\ProductManageController;
use App\Http\Controllers\StoreFormController;
use App\Http\Controllers\StoreManageController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::get('/inventory-list', InventoryListController::class)->name('inventory.list');
Route::match(['get', 'post'], '/inventory/{inventory}', InventoryEditController::class)->name('inventory.edit');

Route::get('/products', [ProductManageController::class, 'index'])->name('product.manage');
Route::match(['get', 'post'], '/products/form/{product?}', ProductFormController::class)->name('product.form');

Route::get('/stores', [StoreManageController::class, 'index'])->name('store.manage');
Route::match(['get', 'post'], '/stores/form/{store?}', StoreFormController::class)->name('store.form');
