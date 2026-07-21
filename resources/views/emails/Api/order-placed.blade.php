<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:30px;">

<div style="max-width:650px; margin:auto; background:white; padding:25px; border-radius:8px;">

    <h2 style="color:#4f46ff; text-align:center;">
        Order Confirmed 🎉
    </h2>

    <p>Hello <strong>{{ $order->detail->name }}</strong>,</p>

    <p>Your order has been placed successfully.</p>

    <hr style="border:0; border-top:2px solid #555;">

    <p>
        <strong>Order Number:</strong>
        {{ $order->order_number }}
    </p>

    <p>
        <strong>Product:</strong>
        {{ $order->items->first()->product->name }}
    </p>

    <p>
        <strong>Quantity:</strong>
        {{ $order->items->first()->quantity }}
    </p>

    <p>
        <strong>Total Amount:</strong>
        ₹{{ number_format($order->amount, 2) }}
    </p>

    <hr style="border:0; border-top:2px solid #555;">

    <p>
        Thank you for shopping with <strong>Total Gadgets.</strong>
    </p>

    <p>
        Regards,<br>
        <strong>Total Gadgets Team</strong>
    </p>

</div>

</body>
</html>