<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\InventoryHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $openPOs = PurchaseOrder::whereIn('status', ['Draft', 'Ordered', 'Partially Received'])->count();
        
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'minimum_stock')->get();
        $lowStockCount = $lowStockProducts->count();

        $recentMovements = InventoryHistory::with('product')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts', 
            'totalSuppliers', 
            'openPOs', 
            'lowStockCount', 
            'recentMovements'
        ));
    }
}
