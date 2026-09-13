<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('category');
            $table->string('unit'); // pcs, kg, gram, liter, bottle, box
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('purchase_price', 15, 2);
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->date('order_date');
            $table->enum('status', ['Draft', 'Ordered', 'Partially Received', 'Received', 'Cancelled'])->default('Draft');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_ordered', 10, 2);
            $table->decimal('quantity_received', 10, 2)->default(0);
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('line_total', 15, 2);
            $table->timestamps();
        });

        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_change', 10, 2); // Positive or Negative value
            $table->string('reason'); // 'PO-0001', 'Manual adjustment', etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_histories');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
    }
};
