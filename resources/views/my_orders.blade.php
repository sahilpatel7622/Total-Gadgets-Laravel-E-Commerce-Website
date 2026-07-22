@extends('layouts.user')

@section('title','My Orders')

@section('content')

<link rel="stylesheet" href="{{ asset('css/my_orders.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<div class="orders-page">

    <h1 class="page-title"><i class="fa-solid fa-bag-shopping" style="color: #4f46e5; margin-right: 10px;"></i> My Orders</h1>

    @forelse($orders as $order)

    <div class="order-card">

        <div class="order-header">

            <div class="header-item">
                <span><i class="fa-solid fa-hashtag"></i> Order No</span>
                <strong>{{ $order->order_number }}</strong>
                <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
            </div>

            <div class="header-item">
                <span><i class="fa-regular fa-credit-card"></i> Payment</span>
                <strong>{{ $order->payment?->payment_method ?? 'N/A' }}</strong>
            </div>

            <div class="header-item">
                <span><i class="fa-solid fa-money-check-dollar"></i> Payment Status</span>
                <strong>{{ $order->payment?->payment_status ?? 'N/A' }}</strong>
            </div>

            <div class="header-item text-right">
                <span style="justify-content: flex-end"><i class="fa-solid fa-signal"></i> Status</span>
                <div class="status">
                    @if(strtolower($order->status) == 'pending') <i class="fa-solid fa-clock" style="margin-right: 6px;"></i>
                    @elseif(strtolower($order->status) == 'processing') <i class="fa-solid fa-spinner fa-spin" style="margin-right: 6px;"></i>
                    @elseif(strtolower($order->status) == 'shipped') <i class="fa-solid fa-truck-fast" style="margin-right: 6px;"></i>
                    @elseif(strtolower($order->status) == 'delivered') <i class="fa-solid fa-check-circle" style="margin-right: 6px;"></i>
                    @elseif(strtolower($order->status) == 'cancelled') <i class="fa-solid fa-xmark-circle" style="margin-right: 6px;"></i>
                    @endif
                    {{ $order->status }}
                </div>
            </div>

        </div>

        <div class="order-body">
            <div class="order-body-left">
                <div class="products-list">

            @foreach($order->items as $item)

            <div class="product-row">

                @if($item->product && ($item->product->trashed() || ($item->product->category && $item->product->category->trashed())))
                    <div class="product-left">
                        <span style="
                            display:inline-flex;
                            align-items:center;
                            gap:6px;
                            background:#fee2e2;
                            color:#dc2626;
                            font-size:15px;
                            font-weight:600;
                            padding:5px 12px;
                            border-radius:20px;
                            letter-spacing:0.4px;
                        ">
                            🗑 Product Deleted
                        </span>
                    </div>

                    <div class="product-qty">Qty : {{ $item->quantity }}</div>
                    <div class="product-price" style="color:#9ca3af;">—</div>   

                @elseif($item->product && ($item->product->status == 0 || ($item->product->category && $item->product->category->status == 0)))

                    <div class="product-left">
                        <span style="
                            display:inline-flex;
                            align-items:center;
                            gap:6px;
                            background:#fef3c7;
                            color:#d97706;
                            font-size:15px;
                            font-weight:600;
                            padding:5px 12px;
                            border-radius:20px;
                            letter-spacing:0.4px;
                        ">
                            ⚠️ Product Inactive
                        </span>
                    </div>

                    <div class="product-qty">Qty : {{ $item->quantity }}</div>
                    <div class="product-price" style="color:#9ca3af;">—</div>

                @elseif($item->product)

                    <div class="product-left">
                        @if($item->product->image)
                            <img src="{{ asset('product/'.$item->product->image) }}">
                        @else
                            <img src="https://via.placeholder.com/400x300?text=No+Image" alt="No Image">
                        @endif
                        <strong>{{ $item->product->name }}</strong>
                    </div>

                    <div class="product-qty">Qty : {{ $item->quantity }}</div>

                    <div class="product-price">
                        ₹{{ number_format($item->price * $item->quantity, 2) }}
                    </div>

                @else

                    <div class="product-left">
                        <span style="color:#9ca3af; font-style:italic;">Product unavailable</span>
                    </div>
                    <div class="product-qty">Qty : {{ $item->quantity }}</div>
                    <div class="product-price" style="color:#9ca3af;">—</div>

                @endif

            </div>

            @endforeach

                </div>
                
                <div class="address-box">
                    <strong><i class="fa-solid fa-location-dot"></i> Delivery Address</strong>
                    <p>{{ $order->detail?->address ?? 'Address not available' }}</p>
                </div>
            </div>

            <div class="order-body-right">

            <div class="summary-wrapper">
                @php
                    $subTotal = 0;
                    foreach($order->items as $item) {
                        $subTotal += $item->price * $item->quantity;
                    }
                @endphp
                
                <h4 class="summary-title"><i class="fa-solid fa-receipt"></i> Order Summary</h4>
                
                <div class="summary-line">
                    <span class="summary-label"><i class="fa-solid fa-cart-shopping"></i> Subtotal</span>
                    <span class="summary-value">₹{{ number_format($subTotal, 2) }}</span>
                </div>

                @if($order->coupon_discount > 0)
                <div class="summary-line highlight-discount">
                    <span class="summary-label"><i class="fa-solid fa-tags"></i> Coupon ({{ $order->coupon_code }})</span>
                    <span class="summary-value">-₹{{ number_format($order->coupon_discount, 2) }}</span>
                </div>
                @endif

                <div class="summary-line">
                    <span class="summary-label"><i class="fa-solid fa-building-columns"></i> Estimated Tax</span>
                    <span class="summary-value">₹{{ number_format($order->tax_amount, 2) }}</span>
                </div>

                <div class="summary-line">
                    <span class="summary-label"><i class="fa-solid fa-truck-fast"></i> Delivery</span>
                    <span class="summary-value">
                        @if($order->delivery_charge == 0)
                            <span class="free-badge">FREE</span>
                        @else
                            ₹{{ number_format($order->delivery_charge, 2) }}
                        @endif
                    </span>
                </div>

                <div class="summary-total">
                    <span class="total-label">Grand Total</span>
                    <span class="total-value">₹{{ number_format($order->amount, 2) }}</span>
                </div>
            </div>
            </div>
        </div>

        <div class="footer-actions">
                @if(in_array($order->status, ['Pending', 'Processing']))
                    <form action="{{ route('order.cancel', $order->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button type="submit" class="cancel-btn">
                            <i class="fa-solid fa-xmark" style="margin-right: 5px;"></i> Cancel Order
                        </button>
                    </form>
                @endif

                <a href="{{ route('invoice',$order->id) }}" class="invoice-btn">
                    <i class="fa-solid fa-file-arrow-down"></i> Download Invoice
                </a>
            </div>

    </div>

    @empty

    <div class="empty-orders">
        <h2>No Orders Found</h2>
    </div>

    @endforelse

</div>

@endsection