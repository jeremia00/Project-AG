@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-file-earmark-check text-primary me-2" style="color: var(--primary-teal) !important;"></i> PO Details: {{ $purchaseOrder->po_number }}</h2>
        <p class="text-muted mb-0">Process goods receiving and automatically update warehouse inventory stock.</p>
    </div>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-semibold d-block">Supplier</span>
                <span class="fs-6 fw-bold text-dark"><i class="bi bi-building me-1"></i> {{ $purchaseOrder->supplier->name }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-semibold d-block">Order Date</span>
                <span class="fs-6 fw-bold text-dark"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d M Y') }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-semibold d-block">Current Status</span>
                <span class="fs-6">
                    @switch($purchaseOrder->status)
                        @case('Received')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Received</span>
                            @break
                        @case('Partially Received')
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" style="color: var(--primary-dark) !important;">Partially Received</span>
                            @break
                        @default
                            <span class="badge badge-teal">{{ $purchaseOrder->status }}</span>
                    @endswitch
                </span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-semibold d-block">Total Amount</span>
                <span class="fs-5 fw-bold text-success">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header fw-bold">
        <i class="bi bi-box-seam me-2" style="color: var(--primary-teal)"></i> Order Items & Goods Receiving Form
    </div>
    <div class="card-body p-4">
        <form action="{{ route('purchase-orders.receive', $purchaseOrder->id) }}" method="POST">
            @csrf
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity Ordered</th>
                            <th>Quantity Received</th>
                            <th>Remaining</th>
                            <th style="width: 220px;">Receive Now</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $item)
                        @php $remaining = $item->quantity_ordered - $item->quantity_received; @endphp
                        <tr>
                            <td class="fw-bold text-dark">{{ $item->product->name }}</td>
                            <td><span class="badge badge-teal">{{ $item->quantity_ordered }} {{ $item->product->unit }}</span></td>
                            <td class="text-success fw-semibold">{{ $item->quantity_received }} {{ $item->product->unit }}</td>
                            <td class="text-danger fw-semibold">{{ $remaining }} {{ $item->product->unit }}</td>
                            <td>
                                @if($remaining > 0)
                                    <input type="number" step="0.01" name="receive_qty[{{ $item->id }}]" class="form-control" max="{{ $remaining }}" min="0" value="0">
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-6">
                                        <i class="bi bi-check-all me-1"></i> Fully Received
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($purchaseOrder->status !== 'Received' && $purchaseOrder->status !== 'Cancelled')
                <button type="submit" class="btn btn-success"><i class="bi bi-box-arrow-in-down me-1"></i> Process Receiving & Update Stock</button>
            @endif
        </form>
    </div>
</div>
@endsection
