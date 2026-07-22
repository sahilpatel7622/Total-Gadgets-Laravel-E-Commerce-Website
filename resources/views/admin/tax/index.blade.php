@extends('layouts.admin')

@section('title', 'Tax & Delivery Settings')

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
});
</script>
@endif

@if(session('info'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: "{{ session('info') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333'
    });
});
</script>
@endif

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Tax & Delivery Settings</h3>
            <small class="text-muted">Dashboard / Coupons / Tax & Delivery</small>
        </div>

        <a href="{{ route('coupons.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">Checkout Charges</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.tax.update') }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Tax --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Tax Percentage (%)
                        </label>

                        <input type="number"
                               class="form-control @error('tax_percentage') is-invalid @enderror"
                               name="tax_percentage"
                               value="{{ old('tax_percentage', $setting->tax_percentage) }}"
                               step="0.01"
                               min="0"
                               max="100"
                               placeholder="Enter Tax %">

                        @error('tax_percentage')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Delivery --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Delivery Charge (₹)
                        </label>

                        <input type="number"
                               class="form-control @error('delivery_charge') is-invalid @enderror"
                               name="delivery_charge"
                               value="{{ old('delivery_charge', $setting->delivery_charge) }}"
                               step="0.01"
                               min="0"
                               placeholder="Enter Delivery Charge">

                        @error('delivery_charge')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Free Delivery --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Free Delivery Above (₹)
                        </label>

                        <input type="number"
                               class="form-control @error('free_delivery_above') is-invalid @enderror"
                               name="free_delivery_above"
                               value="{{ old('free_delivery_above', $setting->free_delivery_above) }}"
                               step="0.01"
                               min="0"
                               placeholder="Example : 2000">

                        @error('free_delivery_above')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Leave empty if free delivery is not available.
                        </small>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Save Settings
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
