@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
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
        title: @json(session('info')),
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: @json(session('error')),
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
});
</script>
@endif

<div class="container-fluid">

    {{-- Page heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Coupons Management</h3>
            <small class="text-muted">Dashboard / Coupons</small>
        </div>

        <a href="{{ route('coupons.create') }}"
           class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            Add Coupon
        </a>
    </div>

    <div class="card shadow-sm border-0">

        {{-- Card header --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">

            <h5 class="mb-0">
                <i class="fa-solid fa-ticket me-1"></i>
                Coupons List
            </h5>

            <form action="{{ route('coupons.index') }}"
                  method="GET"
                  class="d-flex">

                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search coupon, type..."
                       value="{{ request('search') }}"
                       style="width: 250px;">

                <button type="submit"
                        class="btn btn-primary ms-2">
                    <i class="fa-solid fa-search"></i>
                </button>

                @if(request('search'))
                    <a href="{{ route('coupons.index') }}"
                       class="btn btn-secondary ms-2">
                        <i class="fa-solid fa-xmark"></i>
                        Reset
                    </a>
                @endif
            </form>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0 text-nowrap" style="font-size: 0.875rem;">

                    <thead class="table-dark text-nowrap">
                        <tr>
                            <th>ID</th>
                            <th>Coupon Code</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Min. Order</th>
                            <th>Users</th>
                            <th>Usage Limit</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($coupons as $coupon)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    <strong style="color: green">#{{ $coupon->id }}</strong>
                                </td>

                                {{-- Coupon code --}}
                                <td>
                                    <span class="badge bg-primary fs-6">
                                        {{ $coupon->code }}
                                    </span>
                                </td>

                                {{-- Coupon type --}}
                                <td>
                                    @if($coupon->type === 'fixed')
                                        <span class="badge bg-info text-dark">
                                            Fixed
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Percentage
                                        </span>
                                    @endif
                                </td>

                                {{-- Discount value --}}
                                <td>
                                    <strong>
                                        @if($coupon->type === 'fixed')
                                            ₹{{ number_format($coupon->discount_value, 2) }}
                                        @else
                                            {{ number_format($coupon->discount_value, 2) }}%
                                        @endif
                                    </strong>
                                </td>

                                {{-- Minimum order --}}
                                <td>
                                    ₹{{ number_format($coupon->minimum_order_amount, 2) }}
                                </td>

                                {{-- User type --}}
                                <td>
                                    @if($coupon->user_type === 'all')
                                        <span class="badge bg-success">
                                            All Users
                                        </span>
                                    @else
                                        <span class="badge bg-secondary mb-1">
                                            Selected Users
                                        </span>
                                        <small class="d-block text-muted">
                                            <i class="fa-solid fa-users me-1"></i>{{ $coupon->users_count ?? 0 }}
                                        </small>
                                    @endif
                                </td>

                                {{-- Usage limits --}}
                                <td>
                                    <div class="mb-1">
                                        <small class="text-muted">Total:</small>
                                        <strong>{{ $coupon->usage_limit ?? '∞' }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted">User:</small>
                                        <strong>{{ $coupon->per_user_limit }}</strong>
                                    </div>
                                </td>

                                {{-- Start and end date --}}
                                <td>
                                    <div class="mb-1">
                                        <strong class="text-nowrap">{{ $coupon->start_date->format('d M y') }}</strong> - 
                                        <strong class="text-nowrap">{{ $coupon->end_date->format('d M y') }}</strong>
                                    </div>
                                    @if(now()->gt($coupon->end_date))
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif(now()->lt($coupon->start_date))
                                        <span class="badge bg-warning text-dark">Upcoming</span>
                                    @else
                                        <span class="badge bg-success">Running</span>
                                    @endif
                                </td>

                                {{-- Status toggle --}}
                                <td>
                                    @if(now()->gt($coupon->end_date))
                                        <span class="badge bg-danger" style="opacity: 0.8; cursor: not-allowed;" title="Expired coupons are automatically inactive">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>
                                            Inactive
                                        </span>
                                    @else
                                        <a href="{{ route('coupons.status', $coupon->id) }}"
                                           class="text-decoration-none">

                                            @if($coupon->status)
                                                <span class="badge bg-success">
                                                    <i class="fa-solid fa-circle-check me-1"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i>
                                                    Inactive
                                                </span>
                                            @endif

                                        </a>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('coupons.view', $coupon->id) }}"
                                        class="btn btn-primary btn-sm px-2 py-1"
                                        title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="{{ route('coupons.edit', $coupon->id) }}"
                                           class="btn btn-warning btn-sm px-2 py-1"
                                           title="Edit Coupon">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('coupons.delete', $coupon->id) }}"
                                              method="POST"
                                              class="delete-coupon-form m-0">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="btn btn-danger btn-sm px-2 py-1 delete-coupon-btn"
                                                    title="Delete Coupon">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10"
                                    class="text-center text-muted py-4">

                                    <i class="fa-solid fa-ticket fa-2x mb-2"></i>

                                    <div>No Coupons Found</div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($coupons->hasPages())

                <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">

                    <small class="text-muted">
                        Showing
                        {{ $coupons->firstItem() }}
                        to
                        {{ $coupons->lastItem() }}
                        of
                        {{ $coupons->total() }}
                        coupons
                    </small>

                    {{ $coupons->withQueryString()->links('pagination::bootstrap-5') }}

                </div>

            @endif

        </div>

    </div>

</div>

{{-- Delete confirmation --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteButtons = document.querySelectorAll('.delete-coupon-btn');

    deleteButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const form = this.closest('.delete-coupon-form');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This coupon will be deleted permanently.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

    });

});
</script>

@endsection