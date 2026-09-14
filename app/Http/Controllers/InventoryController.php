<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function history(Request $request)
    {
        $query = InventoryHistory::with('product');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $histories = $query->latest()->paginate(15);
        $products = Product::all();

        return view('inventory.history', compact('histories', 'products'));
    }

    public function adjustForm()
    {
        $products = Product::all();
        return view('inventory.adjust', compact('products'));
    }

    public function adjustStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|numeric|gt:0',
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);
            $qty = $request->quantity;

            if ($request->type === 'decrease') {
                if ($product->current_stock < $qty) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Stock cannot be reduced below 0.'
                    ]);
                }
                $change = -$qty;
            } else {
                $change = $qty;
            }

            $product->current_stock += $change;
            $product->save();

            InventoryHistory::create([
                'product_id' => $product->id,
                'quantity_change' => $change,
                'reason' => 'Manual adjustment: ' . $request->reason,
            ]);
        });

        return redirect()->route('inventory.history')->with('success', 'Inventory adjusted successfully.');
    }
}
