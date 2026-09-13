<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InventoryController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);

Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'create', 'store', 'show']);
Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

Route::get('inventory/history', [InventoryController::class, 'history'])->name('inventory.history');
Route::get('inventory/adjust', [InventoryController::class, 'adjustForm'])->name('inventory.adjust');
Route::post('inventory/adjust', [InventoryController::class, 'adjustStore'])->name('inventory.adjust.store');
