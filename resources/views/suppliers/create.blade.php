@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-person-plus text-primary me-2" style="color: var(--primary-teal) !important;"></i> Add New Supplier</h2>
        <p class="text-muted mb-0">Enter detailed information for the vendor or supplier company.</p>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="card max-w-2xl">
    <div class="card-body p-4">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Company / Supplier Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. PT Food Supplier International" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="e.g. John Doe" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+62 812-3456-7890" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contact@supplier.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Full Address</label>
                <textarea name="address" class="form-control" rows="3" placeholder="123 Commercial Street, Suite 400" required>{{ old('address') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Supplier</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
