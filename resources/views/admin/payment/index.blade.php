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
        timer: 3000,
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
                <i class="fa-solid fa-credit-card"></i> Payments List
            </h5>

            <form action="{{ route('admin.payments') }}" method="GET" class="d-flex">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search Payment, Order..."
                       value="{{ request('search') }}"
                       style="width:250px;">

                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fa-solid fa-search"></i>
                </button>
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
                            <th width="100" class="text-center">Action</th>
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
                                    <strong>{{ $payment->user->name ?? 'N/A' }}</strong>
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
        </div>
    </div>

</div>

@endsection