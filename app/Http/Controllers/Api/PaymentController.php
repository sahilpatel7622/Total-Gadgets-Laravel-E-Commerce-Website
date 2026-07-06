<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Order;

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
            'payment_status' => 'required',
            'razorpay_payment_id' => 'nullable',
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

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

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