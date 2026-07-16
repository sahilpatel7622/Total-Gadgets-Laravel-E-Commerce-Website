@extends('layouts.admin')

@section('title','Payments')

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

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Payments Management</h3>
            <small class="text-muted">Dashboard / Payments</small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-credit-card"></i> Payments List ({{ $payments->total() }})
            </h5>

            <form action="{{ route('admin.payments') }}" method="GET" class="d-flex align-items-center" style="gap: 20px;">
                <div class="d-flex align-items-center gap-3">
                    <select name="per_page" class="form-select" style="width: 80px;">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>

                    <a href="{{ route('admin.payments.export') }}" class="btn btn-success text-nowrap">
                        Export Excel
                    </a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search Payment, Order..."
                           value="{{ request('search') }}"
                           style="width:250px;">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-search"></i>
                    </button>

                    @if(request('search'))
                    <a href="{{ route('admin.payments') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="160">Payment Info</th>
                            <th width="130">Customer</th>
                            <th width="130">Method</th>
                            <th width="120">Amount</th>
                            <th width="130">Status</th>
                            <th width="75" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <span style="color:green">#{{ $payment->id }}</span><br>
                                    <small class="text-muted">
                                        {{ $payment->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </td>

                                <td>
                                    <strong>{{ $payment->order->detail->name ?? 'N/A' }}</strong>
                                </td>

                                <td>
                                    <strong>{{ $payment->payment_method }}</strong>
                                </td>

                                <td>
                                    ₹{{ number_format($payment->amount, 2) }}
                                </td>

                                <td>
                                    <form action="{{ route('admin.payment.status', $payment->id) }}" method="POST">
                                        @csrf

                                        <select name="payment_status"
                                                class="form-select form-select-sm"
                                                onchange="this.form.submit()">

                                            @foreach(['Pending','Paid','Failed','Refunded'] as $status)
                                                <option value="{{ $status }}"
                                                    {{ $payment->payment_status == $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </form>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.payment.view', $payment->id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No Payments Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            {{-- Pagination --}}
            @if($payments->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <small class="text-muted">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} payments
                </small>
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </div>

</div>

@endsection