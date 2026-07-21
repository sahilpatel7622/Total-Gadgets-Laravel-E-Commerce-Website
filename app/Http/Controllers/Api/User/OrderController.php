<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with([
                'detail',
                'items.product.category'
            ])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Orders fetched successfully',
            'data' => $orders
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with([
                'detail',
                'items.product.category'
            ])
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order fetched successfully',
            'data' => $order
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500'
        ]);

        $user = $request->user();

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ], 422);
        }

        foreach ($cartItems as $cartItem) {
            if (!$cartItem->product) {
                return response()->json([
                    'status' => false,
                    'message' => 'One or more products are unavailable'
                ], 422);
            }

            if ($cartItem->quantity < 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid product quantity'
                ], 422);
            }
        }

        try {
            $order = DB::transaction(function () use (
                $user,
                $cartItems,
                $validated
            ) {
                $totalAmount = $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                $order = Order::create([
                    'user_id' => $user->id,
                    'coupon_id' => null,
                    'coupon_code' => null,
                    'coupon_discount' => 0,
                    'order_number' => $this->generateOrderNumber(),
                    'amount' => $totalAmount,
                    'status' => 'Processing'
                ]);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'name' => $validated['name'],
                    'number' => $validated['number'],
                    'email' => $validated['email'],
                    'address' => $validated['address']
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);
                }

                Cart::where('user_id', $user->id)->delete();

                return $order;
            });

            Log::info("Order ID: " . $order->id . " - Detail count: " . \App\Models\OrderDetail::where("order_id", $order->id)->count());
            $order->load([
                'detail',
                'items.product.category'
            ]);

            $mailSent = $this->sendOrderMail($order);

            return response()->json([
                'status' => true,
                'message' => $mailSent
                    ? 'Order placed successfully and confirmation email sent'
                    : 'Order placed successfully but confirmation email could not be sent',
                'mail_sent' => $mailSent,
                'data' => $order
            ], 201);

        } catch (\Throwable $exception) {
            Log::error('Order place error', [
                'user_id' => $user->id,
                'error' => $exception->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to place order',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function buyNow(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string|max:100',
            'number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500'
        ]);

        $user = $request->user();

        $product = product::find($validated['product_id']);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        try {
            $order = DB::transaction(function () use (
                $user,
                $product,
                $validated
            ) {
                $quantity = $validated['quantity'];
                $amount = $product->price * $quantity;

                $order = Order::create([
                    'user_id' => $user->id,
                    'coupon_id' => null,
                    'coupon_code' => null,
                    'coupon_discount' => 0,
                    'order_number' => $this->generateOrderNumber(),
                    'amount' => $amount,
                    'status' => 'Processing'
                ]);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'name' => $validated['name'],
                    'number' => $validated['number'],
                    'email' => $validated['email'],
                    'address' => $validated['address']
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ]);

                return $order;
            });

            Log::info("Order ID: " . $order->id . " - Detail count: " . \App\Models\OrderDetail::where("order_id", $order->id)->count());
            $order->load([
                'detail',
                'items.product.category'
            ]);

            $mailSent = $this->sendOrderMail($order);

            return response()->json([
                'status' => true,
                'message' => $mailSent
                    ? 'Order placed successfully and confirmation email sent'
                    : 'Order placed successfully but confirmation email could not be sent',
                'mail_sent' => $mailSent,
                'data' => $order
            ], 201);

        } catch (\Throwable $exception) {
            Log::error('Buy now order error', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'error' => $exception->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to place order',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    private function sendOrderMail(Order $order): bool
    {
        try {
            Mail::to($order->detail->email)
                ->send(new OrderPlacedMail($order));

            return true;

        } catch (\Throwable $exception) {
            Log::error('Order confirmation email failed', [
                'order_id' => $order->id,
                'email' => $order->detail->email ?? null,
                'error' => $exception->getMessage()
            ]);

            return false;
        }
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'TG' . date('YmdHis');
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}