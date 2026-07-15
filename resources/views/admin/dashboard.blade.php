@extends('layouts.admin')

@section('title', 'Dashboard Overview')

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
@endif
</script>

<style>
    .dashboard-title {
        font-weight: 800;
        color: #111827;
    }

    .stat-card {
        border-radius: 20px;
        padding: 24px;
        color: #fff;
        min-height: 145px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

    .stat-card h4 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .stat-card p {
        margin: 0;
        font-size: 15px;
        opacity: .95;
    }

    .stat-card i {
        position: absolute;
        right: 22px;
        bottom: 18px;
        font-size: 48px;
        opacity: .22;
    }

    .bg-blue {
        background: linear-gradient(135deg, #2563eb, #1e40af);
    }

    .bg-green {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .bg-orange {
        background: linear-gradient(135deg, #f97316, #c2410c);
    }

    .bg-purple {
        background: linear-gradient(135deg, #7c3aed, #4c1d95);
    }

    .bg-red {
        background: linear-gradient(135deg, #dc2626, #991b1b);
    }

    .bg-dark {
        background: linear-gradient(135deg, #111827, #374151);
    }

    .content-card {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .content-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }

    .content-card-header h5 {
        margin: 0;
        font-weight: 800;
        color: #111827;
    }

    .table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        padding: 14px;
        border-color: #e5e7eb;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 14px;
        vertical-align: middle;
        border-color: #e5e7eb;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-paid {
        background: #dcfce7;
        color: #166534;
    }

    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }
    .bg-pink{
        background: linear-gradient(135deg, #ff4d6d, #c9184a);
        color: #fff;
    }

    .product-img {
        width: 48px;
        height: 48px;
        object-fit: contain;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: 5px;
    }

    .quick-box {
        border-radius: 16px;
        padding: 18px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        transition: .3s;
        text-decoration: none;
        display: block;
        color: #111827;
    }

    .quick-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        color: #111827;
    }

    .quick-box i {
        font-size: 26px;
        margin-bottom: 10px;
        color: #2563eb;
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="dashboard-title mb-0">Dashboard Overview</h3>
            <small class="text-muted">Dashboard / Home</small>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-blue">
                <h4>{{ $totalOrders }}</h4>
                <p>Total Orders</p>
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-green">
                <h4>₹{{ number_format($totalRevenue, 2) }}</h4>
                <p>Total Revenue</p>
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-orange">
                <h4>{{ $totalProducts }}</h4>
                <p>Total Products</p>
                <i class="fa-solid fa-box"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-purple">
                <h4>{{ $totalCustomers }}</h4>
                <p>Total Users</p>
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-dark">
                <h4>{{ $totalCategories }}</h4>
                <p>Total Categories</p>
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-red">
                <h4>{{ $pendingOrders }}</h4>
                <p>Pending Orders</p>
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-green">
                <h4>{{ $completedOrders }}</h4>
                <p>Completed Orders</p>
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-pink">
                <h4>{{ $totalCoupons }}</h4>
                <p>Total Coupons</p>
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

    </div>

    {{-- Latest Orders --}}
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5>Latest Orders</h5>
            <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-primary">View All</a>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($latestOrders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number ?? '#ORD'.$order->id }}</strong>
                            </td>

                            <td>{{ $order->user->name ?? 'Guest User' }}</td>

                            <td>₹{{ number_format($order->amount, 2) }}</td>

                            <td>
                                <span class="status-badge status-pending">
                                    {{ $order->payment->payment_status ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="status-badge status-completed">{{ $order->status }}</span>
                            </td>

                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>


    @if(session('successe') && !session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Congrats!',
                    text: '{!! session("successe") !!}',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

</div>

@endsection

@section('script')
<script>
    function preventBack() { window.history.forward(); }
    setTimeout("preventBack()", 0);
    window.onunload = function () { null };
</script>
@endsection