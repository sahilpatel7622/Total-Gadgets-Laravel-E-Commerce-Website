@extends('layouts.admin')

@section('title','Order Details')

@section('content')

<style>
.order-view-page{
    padding:35px;
}

.order-view-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.order-view-top h1{
    font-size:30px;
    font-weight:800;
    color:#111827;
}

.order-view-top p{
    color:#64748b;
    margin-top:5px;
}

.back-btn{
    background:#f8fafc;
    color:#111827;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
}

.info-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
    margin-bottom:25px;
}

.info-card,
.product-card,
.address-card{
    background:#fff;
    border-radius:14px;
    padding:25px;
    box-shadow:0 8px 25px rgba(15,23,42,.08);
}

.info-card h3,
.product-card h3,
.address-card h3{
    font-size:22px;
    margin-bottom:20px;
    color:#111827;
}

.info-card p{
    font-size:17px;
    margin-bottom:10px;
}

.badge{
    padding:6px 14px;
    border-radius:8px;
    font-weight:700;
    font-size:14px;
}

.badge-success{
    background:#dcfce7;
    color:#15803d;
}

.badge-warning{
    background:#fef3c7;
    color:#92400e;
}

.product-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 0;
    border-bottom:1px solid #e5e7eb;
}

.product-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.product-left img{
    width:80px;
    height:80px;
    object-fit:contain;
    border:1px solid #ddd;
    border-radius:8px;
    padding:5px;
}

.product-left h4{
    margin:0;
    font-size:20px;
    font-weight:700;
}

.product-right{
    display:flex;
    align-items:center;
    gap:70px;
}

.qty{
    font-size:20px;
    font-weight:700;
    color:#374151;
    min-width:100px;
    text-align:center;
}

.price{
    font-size:24px;
    font-weight:800;
    color:#2563eb;
    min-width:180px;
    text-align:right;
}

.product-name{
    font-size:20px;
    font-weight:700;
    color:#111827;
}

.product-qty{
    text-align:center;
    font-size:15    px;
    font-weight:600;
}

.product-total{
    text-align:right;
    font-size:20px;
    font-weight:800;
    color:#2563eb;
}

.order-total{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:20px;
    padding-top:20px;
    border-top:2px solid #e5e7eb;
    font-size:28px;
    font-weight:800;
}

.order-total strong{
    color:#2563eb;
}

.product-item img{
    width:75px;
    height:75px;
    object-fit:contain;
    border:1px solid #ddd;
    border-radius:8px;
    padding:5px;
}

.product-item h4{
    font-size:18px;
    margin-bottom:8px;
    color:#111827;
}

.product-item p{
    color:#64748b;
    margin-bottom:8px;
}

.product-item strong{
    font-size:18px;
    color:#111827;
}

.address-card p{
    font-size:18px;
    color:#1f2937;
}

.order-total{
    margin-top:20px;
    padding-top:18px;
    border-top:2px solid #e5e7eb;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:22px;
    font-weight:800;
    border-top: none;
}

.order-total strong{
    color:#2563eb;
}

@media(max-width:900px){
    .info-grid{
        grid-template-columns:1fr;
    }

    .order-view-top{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }
}
</style>

<div class="order-view-page">

    <div class="order-view-top">
        <div>
            <h1>Order {{ $order->order_number }}</h1>
            <p>Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</p>
        </div>

        <a href="{{ route('admin.orders') }}" class="back-btn">
            ← Back to Orders
        </a>
    </div>

    <div class="info-grid">

        <div class="info-card">
            <h3>Customer Details</h3>

            <p><strong>ID:</strong> <a style="color: green">#{{ $order->user->id ?? 'N/A' }}</a></p>
            <p><strong>Name:</strong> {{ $order->detail->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $order->detail->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $order->detail->number ?? 'N/A' }}</p>
        </div>

        <div class="info-card">
            <h3>Order Status</h3>

            <p>
                <strong>Status:</strong>
                <span class="badge badge-success">{{ $order->status }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ $order->payment->payment_method ?? 'N/A' }}</p>
        </div>

    </div>

    <div class="product-card">
        <h3>Product Details</h3>

       @foreach($order->items as $item)

        <div class="product-item">

            <div class="product-left">
                <img src="{{ asset('product/'.$item->product->image) }}">
                <h4>{{ $item->product->name }}</h4>
            </div>

            <div class="product-right">

                <div class="qty">
                    Qty : {{ $item->quantity }}
                </div>

                <div class="price">
                    ₹{{ number_format($item->price * $item->quantity,2) }}
                </div>

            </div>

        </div>

        @endforeach

        <div class="order-total">
            <span>Grand Total</span>
            <strong>₹{{ number_format($order->amount,2) }}</strong>
        </div>



    </div>

    <br><div class="address-card">
        <h3>Shipping Address</h3>
        <p>{{ $order->detail->address }}</p>
    </div>

@endsection