<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            color:#111827;
            font-size:14px;
            margin:0;
            padding:0;
        }

        .invoice-box{
            padding:35px;
        }

        .top{
            display:flex;
            justify-content:space-between;
            border-bottom:2px solid #4f46e5;
            padding-bottom:20px;
            margin-bottom:25px;
        }

        .brand h1{
            margin:0;
            color:#f97316;
            font-size:30px;
        }

        .brand p{
            margin:5px 0;
            color:#64748b;
        }

        .invoice-title{
            text-align:right;
        }

        .invoice-title h2{
            margin:0;
            color:#4f46e5;
            font-size:28px;
        }

        .section{
            width:100%;
            margin-bottom:25px;
        }

        .grid{
            display:flex;
            justify-content:space-between;
            gap:20px;
        }

        .left-box{
            width:47%;
            float:left;
        }

        .right-box{
            width:47%;
            float:right;
        }

        .box{
            background:#f8fafc;
            border:1px solid #e5e7eb;
            border-radius:8px;
            padding:15px;
        }

        .box h3{
            margin-top:0;
            font-size:16px;
            color:#4f46e5;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        table th{
            background:#1f2937;
            color:#fff;
            padding:12px;
            text-align:left;
            font-size:13px;
        }

        table td{
            border:1px solid #e5e7eb;
            padding:12px;
        }

        .text-right{
            text-align:right;
        }

        .status{
            display:inline-block;
            padding:6px 12px;
            border-radius:20px;
            font-weight:bold;
            font-size:12px;
        }

        .paid{
            background:#dcfce7;
            color:#15803d;
        }

        .pending{
            background:#fef3c7;
            color:#92400e;
        }

        .total-box{
            width:300px;
            margin-left:auto;
            margin-top:25px;
            border:1px solid #e5e7eb;
            border-radius:8px;
            padding:18px;
            background:#f8fafc;
        }

        .total-row{
            display:flex;
            justify-content:space-between;
            font-size:18px;
            font-weight:bold;
        }

        .footer{
            margin-top:40px;
            text-align:center;
            color:#64748b;
            font-size:13px;
            border-top:1px solid #e5e7eb;
            padding-top:15px;
        }
    </style>
</head>

<body>

<div class="invoice-box">

    <div class="top">
        <div class="brand">
            <h1>Total Gadgets</h1>
            <p>Ahmedabad, Gujarat</p>
            <p>Email: support@totalgadgets.com</p>
        </div>

        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>Order:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <div class="section">

        <div class="left-box">
            <div class="box">
                <h3>Customer Details</h3>
                <p><strong>Name:</strong> {{ $order->detail->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->detail->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $order->detail->number ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="right-box">
            <div class="box">
                <h3>Payment Details</h3>
                <p><strong>Method:</strong> {{ $order->payment?->payment_method ?? 'N/A' }}</p>
            </div>
        </div>

        <div style="clear:both;"></div>

    </div>

    <div class="section box" style="width:100%;">
        <h3>Shipping Address</h3>
        <p>{{ $order->detail->address }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th width="80">Qty</th>
                <th width="120">Price</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        @if($order->coupon_discount > 0)
        <div class="total-row" style="margin-bottom: 12px; color: #16a34a; font-size: 12px;">
            <span>Discount</span>
            <span>- Rs. {{ number_format($order->coupon_discount, 2) }}</span>
        </div>
        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 12px 0;">
        @endif
        <div class="total-row">
            <span>Grand Total</span>
            <span>Rs. {{ number_format($order->amount, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for shopping with Total Gadgets.</p>
        <p>This is a computer generated invoice.</p>
    </div>

</div>

</body>
</html>