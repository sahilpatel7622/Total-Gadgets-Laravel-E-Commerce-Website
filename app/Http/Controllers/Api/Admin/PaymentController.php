<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with([
            'order.detail',
            'order.items.product',
            'user',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%")
                    ->orWhere('razorpay_payment_id', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order.detail', function ($detailQuery) use ($search) {
                        $detailQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $payments = $query->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Payments fetched successfully.',
            'payments' => $payments,
        ], 200);
    }

    public function show($id)
    {
        $payment = Payment::with([
            'order.detail',
            'order.items.product',
            'user',
        ])->find($id);

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment fetched successfully.',
            'payment' => $payment,
        ], 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:Pending,Paid,Failed,Refunded',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $payment->payment_status = $request->payment_status;
        $payment->save();

        $payment->load([
            'order.detail',
            'order.items.product',
            'user',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Payment status updated successfully.',
            'payment' => $payment,
        ], 200);
    }
}