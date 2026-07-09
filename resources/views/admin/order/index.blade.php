@extends('layouts.admin')

@section('title','Orders')

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
            <h3 class="fw-bold mb-0">Orders Management</h3>
            <small class="text-muted">Dashboard / Orders</small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-cart-shopping"></i> Orders List
            </h5>

            <form action="{{ route('admin.orders') }}" method="GET" class="d-flex">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search Order, Customer..."
                       value="{{ request('search') }}"
                       style="width:250px;">

                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fa-solid fa-search"></i>
                </button>

                @if(request('search'))
                <a href="{{ route('admin.orders') }}" class="btn btn-secondary ms-2">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
                @endif
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="170">Order Info</th>
                            <th width="130">Customer</th>
                            <th width="170">Status</th>
                            <th>Address</th>
                            <th width="100" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <span style="color:green">#{{ $order->id }}</span><br>
                                    <strong>{{ $order->order_number }}</strong><br>
                                    <small class="text-muted">
                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </td>

                                <td>
                                    <strong>{{ $order->detail->name ?? 'N/A' }}</strong>
                                </td>

                                <td>
                                    <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                                        @csrf

                                        <select name="status"
                                                class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                            @foreach(['Pending','Processing','Shipped','Delivered','Cancelled'] as $status)
                                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>

                                <td>{{ $order->detail->address ?? 'N/A' }}</td>

                                <td class="text-center">
                                    <a href="{{ route('admin.order.view', $order->id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No Orders Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <small class="text-muted">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </small>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </div>

</div>

@endsection