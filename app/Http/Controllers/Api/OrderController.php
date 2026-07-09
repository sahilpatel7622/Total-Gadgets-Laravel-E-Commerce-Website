<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'number' => 'required|digits:10',
            'address' => 'required|string|max:500',
        ]);

        $user_id = Auth::id();
        $cartItems = Cart::where('user_id', $user_id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty',
            ], 400);
        }
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => $user_id,
            'order_number' => 'TG' . date('YmdHis'),
            'amount' => $totalAmount,
            'status' => 'Pending',
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'address' => $request->address,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        Cart::where('user_id', $user_id)->delete();
        $name = $request->name;
        $email = $request->email;
        $productNames = $cartItems->pluck('product.name')->implode(', ');
        $totalQuantity = $cartItems->sum('quantity');

        Mail::html("
            <div style='max-width:600px;margin:auto;padding:30px;
                        font-family:Arial,sans-serif;
                        border:1px solid #e5e7eb;
                        border-radius:12px;
                        background:#f9fafb;'>

                <div style='background:#ffffff;padding:25px;border-radius:10px;'>

                    <h2 style='text-align:center;color:#4f46e5;margin-bottom:10px;'>
                        Order Confirmed 🎉
                    </h2>

                    <p>Hello <b>{$name}</b>,</p>
                    <p>Your order has been placed successfully.</p>

                    <hr>

                    <p><b>Order Number:</b> {$order->order_number}</p>
                    <p><b>Product:</b> {$productNames}</p>
                    <p><b>Quantity:</b> {$totalQuantity}</p>
                    <p><b>Total Amount:</b> ₹" . number_format($order->amount, 2) . "</p>
                    <hr>

                    <p>Thank you for shopping with <b>Total Gadgets</b>.</p>

                    <p style='margin-top:25px;'>
                        Regards,<br>
                        <b>Total Gadgets Team</b>
                    </p>

                </div>
            </div>
        ", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Order Confirmation - Total Gadget');
        });

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully',
            'order' => $order,
        ]);
    }

    public function show(string $id)
    {
        $order = Order::with('Items')
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'order' => $order,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $request->validate([
            'status' => 'required',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully',
            'order' => $order,
        ]);
    }

    public function destroy(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }

        OrderItem::where('order_id', $order->id)->delete();
        $order->delete();
        return response()->json([
            'status' => true,
            'message' => 'Order deleted successfully',
        ]);
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'number' => 'required|digits:10',
            'address' => 'required|string|max:500',
        ]);

        $product = product::findOrFail($request->product_id);
        $totalAmount = $product->price * $request->quantity;

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'TG' . date('YmdHis'),
            'amount' => $totalAmount,
            'status' => 'Pending',
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'address' => $request->address,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        $name = $request->name;
        $email = $request->email;
        $productNames = $product->name;

        Mail::html("
            <div style='max-width:600px;margin:auto;padding:30px;
                        font-family:Arial,sans-serif;
                        border:1px solid #e5e7eb;
                        border-radius:12px;
                        background:#f9fafb;'>

                <div style='background:#ffffff;padding:25px;border-radius:10px;'>

                    <h2 style='text-align:center;color:#4f46e5;'>
                        Order Confirmed 🎉
                    </h2>
                    <p>Hello <b>{$name}</b>,</p>
                    <p>Your order has been placed successfully.</p>

                    <hr>

                    <p><b>Order Number:</b> {$order->order_number}</p>
                    <p><b>Product:</b> {$productNames}</p>
                    <p><b>Quantity:</b> {$request->quantity}</p>
                    <p><b>Total Amount:</b> ₹" . number_format($order->amount, 2) . "</p>

                    <hr>

                    <p>Thank you for shopping with <b>Total Gadget</b>.</p>

                </div>
            </div>
        ", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Order Confirmation - Total Gadget');
        });

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully',
            'order' => $order,
        ]);
    }


    // My Order
    public function myOrders()
    {
        $orders = Order::with(['user', 'items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        $result = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $result[] = [
                    'user_name'       => $order->user->name,
                    'order_number'    => $order->order_number,
                    'product'         => $item->product->name,
                    'quantity'        => $item->quantity,
                    'total_amount'    => $order->amount,
                    'status'          => $order->status,
                    'payment_method'  => $order->payment->payment_method ?? null,
                    'payment_status'  => $order->payment->payment_status ?? null,
                ];
            }
        }
        return response()->json([
            'status' => true,
            'orders' => $result,
        ]);
    }
}