@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-clock-history text-primary me-2" style="color: var(--primary-teal) !important;"></i> Stock Movement History</h2>
        <p class="text-muted mb-0">Automated audit log of stock movements from PO receiving and manual adjustments.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <select name="product_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter by Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (SKU: {{ $product->sku }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('product_id'))
                <div class="col-md-2 d-flex align-items-center">
                    <a href="{{ route('inventory.history') }}" class="btn btn-sm btn-secondary"><i class="bi bi-x-circle me-1"></i> Reset Filter</a>
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
                        <th>Transaction Date</th>
                        <th>Product Name</th>
                        <th>Stock Change</th>
                        <th>Reason / Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                    <tr>
                        <td class="text-muted"><i class="bi bi-clock me-1"></i> {{ $history->created_at->format('d M Y H:i') }}</td>
                        <td class="fw-bold text-dark">{{ $history->product->name }}</td>
                        <td>
                            @if($history->quantity_change > 0)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-6">
                                    <i class="bi bi-plus-circle me-1"></i> +{{ $history->quantity_change }} {{ $history->product->unit }}
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fs-6">
                                    <i class="bi bi-dash-circle me-1"></i> {{ $history->quantity_change }} {{ $history->product->unit }}
                                </span>
                            @endif
                        </td>
                        <td><span class="badge badge-teal fs-6">{{ $history->reason }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No stock movement records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $histories->links() }}
</div>
@endsection
