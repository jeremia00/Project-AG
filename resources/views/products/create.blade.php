@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-plus-circle text-primary me-2" style="color: var(--primary-teal) !important;"></i> Add New Product</h2>
        <p class="text-muted mb-0">Fill out the form below to register a new product in the inventory.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="card max-w-2xl">
    <div class="card-body p-4">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Premium Rice 5kg" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="e.g. RICE-001" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="e.g. Food / Grocery" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unit</label>
                    <select name="unit" class="form-select" required>
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="gram">gram</option>
                        <option value="liter">liter</option>
                        <option value="bottle">bottle</option>
                        <option value="box">box</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Initial Stock</label>
                    <input type="number" step="0.01" min="0" name="current_stock" class="form-control" value="{{ old('current_stock', 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Minimum Stock (Alert)</label>
                    <input type="number" step="0.01" min="0" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', 0) }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Purchase Price Per Unit (Rp)</label>
                    <input type="number" step="0.01" min="0" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" placeholder="15000" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Primary Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
