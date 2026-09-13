@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-file-earmark-plus text-primary me-2" style="color: var(--primary-teal) !important;"></i> Create Purchase Order (PO)</h2>
        <p class="text-muted mb-0">Note: Creating a PO does not increase inventory stock until goods receiving is processed.</p>
    </div>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Select Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Order Date</label>
                    <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-list-check me-2" style="color: var(--primary-teal);"></i> Order Line Items</h4>
            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="width: 250px;">Order Quantity</th>
                            <th style="width: 100px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][product_id]" class="form-select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Price: Rp {{ number_format($product->purchase_price, 0, ',', '.') }} / {{ $product->unit }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0.01" name="items[0][quantity]" class="form-control" placeholder="Qty" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row" style="border-radius: 8px;"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-secondary btn-sm mb-4" id="addRow">
                <i class="bi bi-plus-circle me-1"></i> Add Line Item
            </button>

            <div class="pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Create Purchase Order</button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let itemIndex = 1;
    document.getElementById('addRow').addEventListener('click', function() {
        const tableBody = document.querySelector('#itemsTable tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Price: Rp {{ number_format($product->purchase_price, 0, ',', '.') }} / {{ $product->unit }})</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.01" min="0.01" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm remove-row" style="border-radius: 8px;"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tableBody.appendChild(newRow);
        itemIndex++;
    });

    document.querySelector('#itemsTable').addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('remove-row') || e.target.closest('.remove-row'))) {
            const rowCount = document.querySelectorAll('#itemsTable tbody tr').length;
            if (rowCount > 1) {
                const btn = e.target.classList.contains('remove-row') ? e.target : e.target.closest('.remove-row');
                btn.closest('tr').remove();
            } else {
                alert('At least one order line item is required.');
            }
        }
    });
</script>
@endsection
