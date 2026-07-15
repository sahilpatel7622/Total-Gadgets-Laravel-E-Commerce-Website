@extends('layouts.admin')

@section('title','View Coupon')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Coupon Details</h3>
            <small class="text-muted">Dashboard / Coupons / View</small>
        </div>

        <a href="{{ route('coupons.index') }}"
           class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-ticket"></i>
                Coupon Information
            </h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">Coupon Code</th>
                    <td>{{ $coupon->code }}</td>
                </tr>

                <tr>
                    <th>Coupon Type</th>
                    <td>{{ ucfirst($coupon->type) }}</td>
                </tr>

                <tr>
                    <th>Discount Value</th>
                    <td>
                        @if($coupon->type=='fixed')
                            ₹{{ number_format($coupon->discount_value,2) }}
                        @else
                            {{ $coupon->discount_value }} %
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Apply For</th>
                    <td>
                        {{ $coupon->user_type=='all' ? 'All Users' : 'Selected Users' }}
                    </td>
                </tr>

                @if($coupon->user_type=='selected')
                <tr>
                    <th>Selected Users</th>
                    <td>
                        @foreach($coupon->users as $user)
                            <span class="badge bg-primary mb-1">
                                {{ $user->name }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                @endif

                <tr>
                    <th>Minimum Order Amount</th>
                    <td>₹{{ number_format($coupon->minimum_order_amount,2) }}</td>
                </tr>

                <tr>
                    <th>Overall Usage Limit</th>
                    <td>{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                </tr>

                <tr>
                    <th>Per User Limit</th>
                    <td>{{ $coupon->per_user_limit }}</td>
                </tr>

                <tr>
                    <th>Start Date</th>
                    <td>{{ $coupon->start_date->format('d M Y') }}</td>
                </tr>

                <tr>
                    <th>End Date</th>
                    <td>{{ $coupon->end_date->format('d M Y') }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if($coupon->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{ $coupon->created_at->format('d M Y') }}</td>
                </tr>

            </table>

        </div>

    {{-- Coupon Used Orders --}}
    <div class="card shadow-sm border-0 mt-3">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-users"></i>
                Coupon Usage Details
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Order Number</th>
                            <th>Coupon Code</th>
                            <th>Discount</th>
                            <th>Final Amount</th>
                            <th>Used Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($usedOrders as $order)

                            <tr>
                                <td>
                                    <strong>
                                        {{ $order->user->name ?? 'Deleted User' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $order->user->email ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    <span class="text-success">
                                        #{{ $order->id }}
                                    </span>

                                    <br>

                                    <strong>
                                        {{ $order->order_number }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $order->coupon_code }}
                                    </span>
                                </td>

                                <td class="text-success fw-bold">
                                    - ₹{{ number_format($order->coupon_discount, 2) }}
                                </td>

                                <td class="fw-bold">
                                    ₹{{ number_format($order->amount, 2) }}
                                </td>

                                <td>
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    This coupon has not been used yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($usedOrders->hasPages())
                <div class="mt-3">
                    {{ $usedOrders->links() }}
                </div>
            @endif

        </div>

    </div>

    </div>

</div>

@endsection