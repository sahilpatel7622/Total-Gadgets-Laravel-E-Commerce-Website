@extends('layouts.user')

@section('title','My Orders')

@section('content')

<link rel="stylesheet" href="{{ asset('css/my_orders.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<div class="orders-page">

    <h1 class="page-title">My Orders</h1>

    @forelse($orders as $order)

    <div class="order-card">

        <div class="order-header">

            <div class="header-item">
                <span>Order No</span>
                <strong>{{ $order->order_number }}</strong>
                <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
            </div>

            <div class="header-item">
                <span>Payment</span>
                <strong>{{ $order->payment?->payment_method ?? 'N/A' }}</strong>
            </div>

            <div class="header-item">
                <span>Payment Status</span>
                <strong>{{ $order->payment?->payment_status ?? 'N/A' }}</strong>
            </div>

            <div class="header-item text-right">
                <span>Status</span>
                <div class="status">{{ $order->status }}</div>
            </div>

        </div>

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
                        <img src="{{ asset('product/'.$item->product->image) }}">
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

        <div class="order-footer">

            <div class="address-box">
                <strong>Delivery Address</strong>
                <p>{{ $order->detail?->address ?? 'Address not available' }}</p>
            </div>

            <div class="total-box">
                <span>Total Amount</span>
                <strong>₹{{ number_format($order->amount, 2) }}</strong>
            </div>

            @if(in_array($order->status, ['Pending', 'Processing']))
                <form action="{{ route('order.cancel', $order->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" class="cancel-btn">
                        Cancel Order
                    </button>
                </form>
            @endif

            <a href="{{ route('invoice',$order->id) }}" class="invoice-btn">
                <i class="fa-solid fa-file-arrow-down"></i>
                Download Invoice
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