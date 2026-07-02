<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Otp;

class CartController extends Controller
{
    public function addToCart($id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->quantity = $cart->quantity + 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        return back()
            ->with('successe', 'Product added to cart.')
            ->with('product');
    }

    public function updateCart(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($request->cart_action == 'plus') {
            $cart->quantity++;
            $cart->save();
        }

        if ($request->cart_action == 'minus') {
            $cart->quantity--;

            if ($cart->quantity <= 0) {
                $cart->delete();
            } else {
                $cart->save();
            }
        }

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $cartCount = $cartItems->sum('quantity');

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        return response()->json([
            'success' => true,
            'cartId' => $id,
            'quantity' => $cart->exists ? $cart->quantity : 0,
            'removed' => !$cart->exists,
            'cartCount' => $cartCount,
            'cartTotal' => number_format($cartTotal, 2),
        ]);
    }

    public function removeCart($id)
    {
        Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return back()
            ->with('successe', 'Product removed from cart.')
            ->with('cart_open', true);
    }

    // Buy Now

    public function buyNow($slug)
    {
        $product = product::where('slug', $slug)->firstOrFail();

        $cartItems = collect([
            (object)[
                'product' => $product,
                'product_id' => $product->id,
                'quantity' => 1
            ]
        ]);

        $cartTotal = $product->price;
        $buyNowProductId = $product->id;

        return view('checkout', compact(
            'cartItems',
            'cartTotal',
            'buyNowProductId'
        ));
    }

    // Checkout
    
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        return view('checkout', compact('cartItems', 'cartTotal'));
    }

    // Place Order - OTP Send
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'number' => 'required|digits:10',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|digits:6',
            'payment_method' => 'required|in:COD,RAZORPAY',
        ]);

        if ($request->buy_now_product_id) {
            $product = product::findOrFail($request->buy_now_product_id);

            $cartItems = collect([
                (object)[
                    'product' => $product,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]
            ]);
        } else {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')
                ->with('error', 'Cart is empty.');
        }

        $otp = rand(100000, 999999);
        Otp::where('user_id', Auth::id())
            ->where('type', 'order_verify')
            ->delete();

        Otp::create([
            'user_id' => Auth::id(),
            'email' => $request->email,
            'otp' => $otp,
            'type' => 'order_verify',
            'expiry' => now()->addMinutes(5),
        ]);

        session([
            'order_data' => $request->all()
        ]);

            Mail::html("
                <div style='max-width:600px;margin:auto;padding:30px;
                            font-family:Arial,sans-serif;
                            border:1px solid #e5e7eb;
                            border-radius:10px;
                            background:#f9fafb;'>

                    <div style='text-align:center'>
                        <h2 style='color:#4f46e5;margin-bottom:10px;'>
                            Total Gadgets
                        </h2>

                        <p style='font-size:16px;color:#555;'>
                            Password Reset OTP
                        </p>

                        <div style='margin:30px 0'>
                            <span style='font-size:34px;
                                        font-weight:bold;
                                        color:#111827;
                                        letter-spacing:6px;'>
                                {$otp}
                            </span>
                        </div>

                        <p style='color:#666'>
                            This OTP is valid for
                            <strong>5 Minutes</strong>.
                        </p>

                        <p style='color:#999;font-size:13px'>
                            Do not share this OTP with anyone.
                        </p>
                    </div>

                </div>
        
        ",function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Order Verification OTP');
        });

        return redirect()->route('order.otp.form')
            ->with('success', 'OTP sent successfully.');
    }


    // OTP Page
    public function orderOtpForm()
    {
        if (!session()->has('order_data')) {
            return redirect()->route('checkout')
                ->with('error', 'Session expired.');
        }
        return view('order_verify_otp');
    }


    // Verify OTP + Create Order
    public function verifyOrderOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otpData = Otp::where('user_id', Auth::id())
            ->where('otp', $request->otp)
            ->where('type', 'order_verify')
            ->latest()
            ->first();

        if (!$otpData) {
            return back()->with('error', 'Invalid OTP.');
        }

        if (now()->gt($otpData->expiry)) {
            return back()->with('error', 'OTP expired.');
        }

        $orderData = session('order_data');

        if (!$orderData) {
            return redirect()->route('checkout')
                ->with('error', 'Session expired.');
        }

        if (!empty($orderData['buy_now_product_id'])) {
            $product = product::findOrFail($orderData['buy_now_product_id']);

            $cartItems = collect([
                (object)[
                    'product' => $product,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]
            ]);
        } else {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
        }

        DB::beginTransaction();

        try {
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });

            $fullAddress = $orderData['address'] . ', ' .
                $orderData['city'] . ', ' .
                $orderData['state'] . ' - ' .
                $orderData['pincode'];

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'TG' . date('YmdHis'),
                'amount' => $cartTotal,
                'address' => $fullAddress,
                'status' => 'Pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'amount' => $cartTotal,
                'payment_method' => $orderData['payment_method'],
                'payment_status' => 'Pending',
                'razorpay_payment_id' => null,
            ]);

            if ($orderData['payment_method'] == 'COD' && empty($orderData['buy_now_product_id'])) {
                Cart::where('user_id', Auth::id())->delete();
            }

            DB::commit();
            if ($orderData['payment_method'] == 'RAZORPAY') {
                return view('razorpay_checkout', [
                    'order' => $order,
                    'amount' => $cartTotal,
                    'key' => env('RAZORPAY_KEY'),
                    'buyNowProductId' => $orderData['buy_now_product_id'] ?? null,
                ]);
            }

            $order->load('items.product');
            $this->sendOrderConfirmMail(
                $order,
                $orderData['email'],
                $orderData['name'],
                'Cash On Delivery'
            );

            return redirect()
                ->route('products')
                ->with('successe', 'Order placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    // My Order
    public function myOrders()
    {
         $orders = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('my_orders', compact('orders'));
    }

    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        if (in_array($order->status, ['Shipped', 'Delivered', 'Cancelled'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->status = 'Cancelled';
        $order->save();

        return back()->with('successe', 'Order cancelled successfully.');
    }

    // Rezorpay
    public function razorpaySuccess(Request $request)
    {
        $payment = Payment::where('order_id', $request->order_id)->firstOrFail();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $razorpayPayment = $api->payment->fetch($request->razorpay_payment_id);
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $payment->update([
            'payment_method' => strtoupper($razorpayPayment['method']),
            'payment_status' => 'Paid',
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        $this->sendOrderConfirmMail(
            $order,
            Auth::user()->email,
            Auth::user()->name,
            'Paid'
        );

        if (!$request->buy_now_product_id) {
            Cart::where('user_id', Auth::id())->delete();
        }

        return redirect()
            ->route('products')
            ->with('successe', 'Payment successful.');
    }

    private function sendOrderConfirmMail($order, $email, $name, $paymentStatus)
    {
        try {
            $order->load('items.product');
            $productNames = $order->items
                ->pluck('product.name')
                ->implode(', ');

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
                        <p><b>Total Amount:</b> ₹" . number_format($order->amount, 2) . "</p>
                        <p><b>Payment Status:</b> {$paymentStatus}</p>
                        <p><b>Order Status:</b> {$order->status}</p>

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
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    // Invoice
    public function invoice($id)
    {
        $order = Order::with([
            'user',
            'items.product',
            'payment'
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);
        $pdf = Pdf::loadView('invoice', compact('order'));
        return $pdf->download('In   voice-'.$order->order_number.'.pdf');
    }

}