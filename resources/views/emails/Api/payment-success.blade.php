<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
</head>

<body style="margin:0;padding:30px;background:#f5f5f5;font-family:Arial,sans-serif;">

    <div style="max-width:650px;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">

        <h2 style="text-align:center;color:#4f46ff;">
            Payment Successful 🎉
        </h2>

        <p>
            Hello <strong>{{ $payment->order->detail->name }}</strong>,
        </p>

        <p>
            Your payment has been completed successfully.
        </p>

        <hr>

        <p>
            <strong>Order Number:</strong>
            {{ $payment->order->order_number }}
        </p>

        <p>
            <strong>Razorpay ID:</strong>
            {{ $payment->razorpay_payment_id }}
        </p>

        <p>
            <strong>Payment Method:</strong>
            {{ $payment->payment_method }}
        </p>

        <p>
            <strong>Payment Status:</strong>
            {{ $payment->payment_status }}
        </p>

        <p>
            <strong>Total Amount:</strong>
            ₹{{ number_format($payment->amount,2) }}
        </p>

        <p>
            <strong>Payment Date:</strong>
            {{ $payment->updated_at->format('d-m-Y h:i A') }}
        </p>

        <hr>

        <p>
            Thank you for shopping with
            <strong>Total Gadgets</strong>.
        </p>

        <p>
            Regards,<br>
            <strong>Total Gadgets Team</strong>
        </p>

    </div>

</body>

</html>