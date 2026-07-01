@extends('layouts.admin')

@section('title','Payment Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Payment Details</h3>
            <small class="text-muted">Dashboard / Payments / View</small>
        </div>

        <a href="{{ route('admin.payments') }}" class="btn btn-light">
            ← Back to Payments
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-credit-card"></i> Payment #{{ $payment->id }}
            </h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Order Number:</strong>
                    <p>{{ $payment->order->order_number ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Customer:</strong>
                    <p>{{ $payment->user->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Amount:</strong>
                    <p>₹{{ number_format($payment->amount, 2) }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Payment Method:</strong>
                    <p>{{ $payment->payment_method }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Payment Status:</strong>
                    <p>
                        @if($payment->payment_status == 'Paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($payment->payment_status == 'Refunded')
                            <span class="badge bg-info">Refunded</span>
                        @elseif($payment->payment_status == 'Failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </p>
                </div>

                <div class="col-md-6">
                    <strong>Razorpay Payment ID:</strong>
                    <p>{{ $payment->razorpay_payment_id ?? 'N/A' }}</p>
                </div>
            </div>

            <hr>

            <h5>Order Details</h5>

            <p><strong>Order Status:</strong> {{ $payment->order->status ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $payment->order->address ?? 'N/A' }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->created_at->format('d M Y, h:i A') }}</p>

        </div>
    </div>

</div>

@endsection