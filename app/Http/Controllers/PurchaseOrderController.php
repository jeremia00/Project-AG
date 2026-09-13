<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'items.product']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest()->paginate(10);
        $suppliers = Supplier::all();

        return view('purchase_orders.index', compact('purchaseOrders', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($request) {
            $poNumber = 'PO-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);
            $totalAmount = 0;

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'status' => 'Ordered',
                'total_amount' => 0,
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $lineTotal = $item['quantity'] * $product->purchase_price;
                $totalAmount += $lineTotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'purchase_price' => $product->purchase_price,
                    'line_total' => $lineTotal,
                ]);
            }

            $po->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully without increasing stock.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'receive_qty' => 'required|array',
            'receive_qty.*' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $allFullyReceived = true;
            $anyReceived = false;

            foreach ($purchaseOrder->items as $item) {
                $receiveQty = $request->receive_qty[$item->id] ?? 0;

                if ($receiveQty > 0) {
                    $maxAllowed = $item->quantity_ordered - $item->quantity_received;
                    if ($receiveQty > $maxAllowed) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'receive_qty' => "Cannot receive more than remaining ordered quantity ({$maxAllowed}) for item: " . $item->product->name
                        ]);
                    }

                    // 1. Update PO Item received quantity
                    $item->quantity_received += $receiveQty;
                    $item->save();

                    // 2. Update Product Inventory Stock
                    $product = $item->product;
                    $product->current_stock += $receiveQty;
                    $product->save();

                    // 3. Record Inventory Movement History
                    InventoryHistory::create([
                        'product_id' => $product->id,
                        'quantity_change' => $receiveQty,
                        'reason' => $purchaseOrder->po_number,
                    ]);

                    $anyReceived = true;
                }

                if ($item->quantity_received < $item->quantity_ordered) {
                    $allFullyReceived = false;
                }
            }

            // Update Status
            if ($allFullyReceived) {
                $purchaseOrder->status = 'Received';
            } elseif ($anyReceived || $purchaseOrder->items->sum('quantity_received') > 0) {
                $purchaseOrder->status = 'Partially Received';
            }
            $purchaseOrder->save();
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder->id)->with('success', 'Goods receiving processed successfully.');
    }
}
