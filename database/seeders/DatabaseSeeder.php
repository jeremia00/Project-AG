<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryHistory;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed 5 Suppliers
        $suppliers = [];
        for ($i = 1; $i <= 5; $i++) {
            $suppliers[] = Supplier::create([
                'name' => "Supplier $i Corp",
                'contact_person' => "Contact Person $i",
                'phone' => "+62 812-3456-780$i",
                'email' => "supplier$i@example.com",
                'address' => "Industrial Park Avenue No. $i, Block B",
            ]);
        }

        // 2. Seed Rice Product (Main Demonstration Focus)
        $rice = Product::create([
            'name' => 'Rice',
            'sku' => 'RICE-001',
            'category' => 'Food',
            'unit' => 'kg',
            'current_stock' => 20,
            'minimum_stock' => 10,
            'purchase_price' => 15000,
            'supplier_id' => $suppliers[0]->id,
        ]);

        // 3. Seed 19 Other Products (Total 20 Products)
        $units = ['pcs', 'kg', 'gram', 'liter', 'bottle', 'box'];
        for ($i = 2; $i <= 20; $i++) {
            Product::create([
                'name' => "Product $i",
                'sku' => "SKU-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'category' => $i % 2 == 0 ? 'Food' : 'Beverage',
                'unit' => $units[array_rand($units)],
                'current_stock' => rand(5, 50),
                'minimum_stock' => 15,
                'purchase_price' => rand(10, 100) * 1000,
                'supplier_id' => $suppliers[rand(0, 4)]->id,
            ]);
        }

        // 4. Run Core Scenario (PO-0001 with Rice 50kg, partial delivery tests)
        DB::transaction(function () use ($rice, $suppliers) {
            $poScenario = PurchaseOrder::create([
                'po_number' => 'PO-0001',
                'supplier_id' => $suppliers[0]->id,
                'order_date' => now()->subDays(3),
                'status' => 'Ordered',
                'total_amount' => 50 * 15000,
            ]);

            $poItem = PurchaseOrderItem::create([
                'purchase_order_id' => $poScenario->id,
                'product_id' => $rice->id,
                'quantity_ordered' => 50,
                'quantity_received' => 0,
                'purchase_price' => 15000,
                'line_total' => 50 * 15000,
            ]);

            // First Receiving: 30kg
            $poItem->quantity_received += 30;
            $poItem->save();
            $rice->current_stock += 30; // 20 -> 50
            $rice->save();
            $poScenario->status = 'Partially Received';
            $poScenario->save();

            InventoryHistory::create([
                'product_id' => $rice->id,
                'quantity_change' => 30,
                'reason' => 'PO-0001',
            ]);

            // Second Receiving: 20kg
            $poItem->quantity_received += 20;
            $poItem->save();
            $rice->current_stock += 20; // 50 -> 70
            $rice->save();
            $poScenario->status = 'Received';
            $poScenario->save();

            InventoryHistory::create([
                'product_id' => $rice->id,
                'quantity_change' => 20,
                'reason' => 'PO-0001',
            ]);
        });

        // 5. Seed 4 Additional POs (Total 5 POs)
        $statuses = ['Draft', 'Ordered', 'Partially Received', 'Cancelled'];
        for ($i = 2; $i <= 5; $i++) {
            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $suppliers[rand(0, 4)]->id,
                'order_date' => now()->subDays($i),
                'status' => $statuses[$i - 2],
                'total_amount' => rand(100, 500) * 1000,
            ]);

            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => rand(2, 20),
                'quantity_ordered' => rand(10, 50),
                'quantity_received' => 0,
                'purchase_price' => 20000,
                'line_total' => 200000,
            ]);
        }
    }
}
