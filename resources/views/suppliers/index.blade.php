@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-truck text-primary me-2" style="color: var(--primary-teal) !important;"></i> Suppliers & Vendors</h2>
        <p class="text-muted mb-0">Manage contact details and address information for inventory suppliers.</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add New Supplier
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Contact Person</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="fw-bold text-dark"><i class="bi bi-building me-1 text-muted"></i> {{ $supplier->name }}</td>
                        <td class="fw-semibold">{{ $supplier->contact_person }}</td>
                        <td><i class="bi bi-telephone text-muted me-1"></i> {{ $supplier->phone }}</td>
                        <td><i class="bi bi-envelope text-muted me-1"></i> {{ $supplier->email }}</td>
                        <td class="text-muted" style="max-width: 250px;">{{ $supplier->address }}</td>
                        <td class="text-end">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 10px;">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No suppliers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $suppliers->links() }}
</div>
@endsection
