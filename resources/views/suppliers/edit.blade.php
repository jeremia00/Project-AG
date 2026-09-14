@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Supplier: {{ $supplier->name }}</h2>
        <p class="text-muted mb-0">Update vendor contact details and address information.</p>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="card max-w-2xl">
    <div class="card-body p-4">
        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Company / Supplier Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Full Address</label>
                <textarea name="address" class="form-control" rows="3" required>{{ old('address', $supplier->address) }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i> Update Supplier</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
