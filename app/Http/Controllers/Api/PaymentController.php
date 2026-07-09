<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with('order')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'payments' => $payments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required',
        ]);

        $order = Order::where('user_id', Auth::id())
            ->find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $oldPayment = Payment::where('order_id', $order->id)->first();
        if ($oldPayment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment already exists for this order',
            ], 409);
        }

        $paymentStatus = $request->payment_method == 'Cash On Delivery'
            ? 'Pending'
            : 'Paid';

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
        ]);

        $orderDetail = OrderDetail::where('order_id', $order->id)->first();

        $name = $orderDetail->name;
        $email = $orderDetail->email;
        $productNames = $order->Items()
            ->with('product')
            ->get()
            ->pluck('product.name')
            ->implode(', ');
        $totalQuantity = $order->Items()->sum('quantity');

        Mail::html("
        <div style='max-width:600px;margin:auto;padding:30px;
                    font-family:Arial,sans-serif;
                    border:1px solid #e5e7eb;
                    border-radius:12px;
                    background:#f9fafb;'>

            <div style='background:#ffffff;padding:25px;border-radius:10px;'>

                <h2 style='text-align:center;color:#16a34a;'>
                    Payment Successful 💳
                </h2>
                <p>Hello <b>{$name}</b>,</p>
                <p>Your payment has been received successfully.</p>

                <hr>

                <p><b>Order Number:</b> {$order->order_number}</p>
                <p><b>Products:</b> {$productNames}</p>
                <p><b>Total Quantity:</b> {$totalQuantity}</p>
                <p><b>Total Amount:</b> ₹" . number_format($payment->amount, 2) . "</p>
                <p><b>Payment Method:</b> {$payment->payment_method}</p>
                <p><b>Payment Status:</b> {$payment->payment_status}</p>

                <hr>
                <p>Thank you for shopping with <b>Total Gadgets</b>.</p>
                <p style='margin-top:20px'>
                    Regards,<br>
                    <b>Total Gadgets Team</b>
                </p>

            </div>
        </div>
        ", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Payment Confirmation - Total Gadgets');
        });

        return response()->json([
            'status' => true,
            'message' => 'Payment added successfully',
            'payment' => $payment,
        ]);
    }

    public function show(string $id)
    {
        $payment = Payment::where('user_id', Auth::id())
            ->with('order')
            ->find($id);

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'payment' => $payment,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'payment_status' => 'required',
        ]);
        $payment = Payment::where('user_id', Auth::id())->find($id);
        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        $payment->payment_status = $request->payment_status;
        $payment->razorpay_payment_id = $request->razorpay_payment_id ?? $payment->razorpay_payment_id;
        $payment->save();
        return response()->json([
            'status' => true,
            'message' => 'Payment updated successfully',
            'payment' => $payment,
        ]);
    }

    public function destroy(string $id)
    {
        $payment = Payment::where('user_id', Auth::id())->find($id);
        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        $payment->delete();
        return response()->json([
            'status' => true,
            'message' => 'Payment deleted successfully',
        ]);
    }
}