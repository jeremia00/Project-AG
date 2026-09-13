@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Product: {{ $product->name }}</h2>
        <p class="text-muted mb-0">Update product metadata, category, price, or stock threshold.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="card max-w-2xl">
    <div class="card-body p-4">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $product->category) }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unit</label>
                    <select name="unit" class="form-select" required>
                        @foreach(['pcs', 'kg', 'gram', 'liter', 'bottle', 'box'] as $u)
                            <option value="{{ $u }}" {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Current Stock (Read-only)</label>
                    <input type="text" class="form-control bg-light" value="{{ $product->current_stock }} {{ $product->unit }}" disabled>
                    <small class="text-muted">Use PO Receiving or Manual Adjustment to change stock.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Minimum Stock Limit</label>
                    <input type="number" step="0.01" min="0" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Purchase Price Per Unit (Rp)</label>
                    <input type="number" step="0.01" min="0" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Primary Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i> Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
