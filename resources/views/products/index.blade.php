@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-box-seam text-primary me-2" style="color: var(--primary-teal) !important;"></i> Products & Inventory</h2>
        <p class="text-muted mb-0">Manage product catalog, minimum stock thresholds, and purchase pricing.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add New Product
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-3">
            <div class="col-md-6 col-lg-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--accent-soft);"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or SKU..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
            @if(request('search'))
                <div class="col-md-2 d-flex align-items-center">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-x-circle me-1"></i> Reset</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Current Stock</th>
                        <th>Min. Stock</th>
                        <th>Purchase Price</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-bold text-dark"><span class="badge badge-teal">{{ $product->sku }}</span></td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td><span class="badge bg-secondary">{{ $product->category }}</span></td>
                        <td class="text-muted"><i class="bi bi-building me-1"></i> {{ $product->supplier->name ?? 'N/A' }}</td>
                        <td>
                            <span class="{{ $product->isLowStock() ? 'text-danger fw-bold' : 'text-success fw-bold' }} fs-6">
                                {{ $product->current_stock }} {{ $product->unit }}
                            </span>
                            @if($product->isLowStock())
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 ms-1">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock
                                </span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $product->minimum_stock }} {{ $product->unit }}</td>
                        <td class="fw-bold">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 10px;">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
