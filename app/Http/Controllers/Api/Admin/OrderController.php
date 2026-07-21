<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'detail',
            'items.product',
            'user',
            'payment',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('details', function ($detailQuery) use ($search) {
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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($paymentQuery) use ($request) {
                $paymentQuery->where(
                    'payment_status',
                    $request->payment_status
                );
            });
        }

        if ($request->filled('payment_method')) {
            $query->whereHas('payment', function ($paymentQuery) use ($request) {
                $paymentQuery->where(
                    'payment_method',
                    $request->payment_method
                );
            });
        }

        $orders = $query->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Orders fetched successfully.',
            'orders' => $orders,
        ], 200);
    }

    public function show($id)
    {
        $order = Order::with([
            'detail',
            'items.product',
            'user',
            'payment',
        ])->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order fetched successfully.',
            'order' => $order,
        ], 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                'in:Pending,Processing,Packed,Shipped,Out For Delivery,Delivered,Cancelled',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $order->status = $request->status;
        $order->save();

        $order->load([
            'detail',
            'items.product',
            'user',
            'payment',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully.',
            'order' => $order,
        ], 200);
    }
}