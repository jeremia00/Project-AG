@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-file-earmark-text text-primary me-2" style="color: var(--primary-teal) !important;"></i> Purchase Orders (PO)</h2>
        <p class="text-muted mb-0">Manage orders sent to suppliers and track goods receiving status.</p>
    </div>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create New PO
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="supplier_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter by Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter by Status --</option>
                    @foreach(['Draft', 'Ordered', 'Partially Received', 'Received', 'Cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('supplier_id') || request('status'))
                <div class="col-md-2 d-flex align-items-center">
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-x-circle me-1"></i> Reset Filters</a>
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
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td class="fw-bold"><span class="badge badge-teal fs-6">{{ $po->po_number }}</span></td>
                        <td class="fw-semibold">{{ $po->supplier->name }}</td>
                        <td class="text-muted"><i class="bi bi-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                        <td>
                            @switch($po->status)
                                @case('Received')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-6">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ $po->status }}
                                    </span>
                                    @break
                                @case('Partially Received')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 fs-6" style="color: var(--primary-dark) !important;">
                                        <i class="bi bi-hourglass-split me-1"></i> {{ $po->status }}
                                    </span>
                                    @break
                                @case('Ordered')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fs-6" style="color: var(--primary-teal) !important;">
                                        <i class="bi bi-send me-1"></i> {{ $po->status }}
                                    </span>
                                    @break
                                @case('Cancelled')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fs-6">
                                        <i class="bi bi-x-circle me-1"></i> {{ $po->status }}
                                    </span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ $po->status }}</span>
                            @endswitch
                        </td>
                        <td class="fw-bold">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-box-arrow-in-down me-1"></i> View / Receive Goods
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No purchase orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $purchaseOrders->links() }}
</div>
@endsection
