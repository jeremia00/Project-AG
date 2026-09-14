@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-sliders text-warning me-2"></i> Manual Stock Adjustment</h2>
        <p class="text-muted mb-0">Perform manual stock corrections for loss, damage, or stock count discrepancies.</p>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-body p-4">
        <form action="{{ route('inventory.adjust.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Select Product</label>
                <select name="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Current Stock: {{ $product->current_stock }} {{ $product->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Adjustment Type</label>
                    <select name="type" class="form-select" required>
                        <option value="increase">Increase (+ Stock In)</option>
                        <option value="decrease">Decrease (- Stock Out)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Quantity</label>
                    <input type="number" step="0.01" name="quantity" class="form-control" placeholder="e.g. 5" required min="0.01">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Reason / Note</label>
                <input type="text" name="reason" class="form-control" placeholder="e.g. Broken during transport, Stock count correction" required>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i> Save Stock Adjustment</button>
        </form>
    </div>
</div>
@endsection
