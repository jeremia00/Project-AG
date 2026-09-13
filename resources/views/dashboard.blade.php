@extends('layouts.app')

@section('content')
<!-- Hero Banner inspired by Mekari Jurnal -->
<div class="card mb-4 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, var(--surface-light) 60%, var(--accent-soft) 100%); border-color: var(--accent-soft);">
    <div class="card-body p-4 p-lg-5 position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge badge-teal mb-2"><i class="bi bi-stars"></i> All-in-One Inventory & Purchase Order System</span>
                <h1 class="fw-bold display-6 text-dark mb-2" style="font-weight: 800;">Accurate Stock Control & Real-Time PO Tracking</h1>
                <p class="text-muted fs-6 mb-0">Monitor inventory movements, automate purchase order receiving, and manage minimum reorder thresholds efficiently.</p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="bi bi-box-seam-fill text-primary" style="font-size: 5.5rem; opacity: 0.25; color: var(--primary-teal) !important;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="metric-card style-1">
            <div class="metric-icon style-1">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="text-muted fw-semibold small text-uppercase">Total Products</div>
            <div class="fs-2 fw-bold text-dark mt-1">{{ $totalProducts }}</div>
            <div class="small text-muted mt-2"><i class="bi bi-arrow-up-right text-success"></i> Registered Inventory</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card style-2">
            <div class="metric-icon style-2">
                <i class="bi bi-building"></i>
            </div>
            <div class="text-muted fw-semibold small text-uppercase">Total Suppliers</div>
            <div class="fs-2 fw-bold text-dark mt-1">{{ $totalSuppliers }}</div>
            <div class="small text-muted mt-2"><i class="bi bi-check-circle text-info"></i> Active Vendors</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card style-3">
            <div class="metric-icon style-3">
                <i class="bi bi-cart-check"></i>
            </div>
            <div class="text-muted fw-semibold small text-uppercase">Open POs</div>
            <div class="fs-2 fw-bold text-dark mt-1">{{ $openPOs }}</div>
            <div class="small text-muted mt-2"><i class="bi bi-clock-history text-warning"></i> Pending / Partial</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card style-4">
            <div class="metric-icon style-4">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="text-muted fw-semibold small text-uppercase">Low Stock Alert</div>
            <div class="fs-2 fw-bold text-danger mt-1">{{ $lowStockCount }}</div>
            <div class="small text-muted mt-2"><i class="bi bi-shield-exclamation text-danger"></i> Needs Reorder</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fs-5 fw-bold"><i class="bi bi-activity me-2" style="color: var(--primary-teal)"></i> Recent Inventory Movements</span>
        <a href="{{ route('inventory.history') }}" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i> View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product Name</th>
                        <th>Stock Change</th>
                        <th>Reason / Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMovements as $history)
                    <tr>
                        <td class="text-muted"><i class="bi bi-calendar-event me-1"></i> {{ $history->created_at->format('d M Y H:i') }}</td>
                        <td class="fw-bold">{{ $history->product->name }}</td>
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
                        <td><span class="badge badge-teal">{{ $history->reason }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No inventory movements recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
