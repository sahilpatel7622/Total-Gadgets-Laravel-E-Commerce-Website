<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccessMail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],

            'payment_method' => [
                'required',
                Rule::in([
                    'Cash On Delivery',
                    'Razorpay',
                ]),
            ],

            'razorpay_payment_id' => [
                'nullable',
                'string',
            ],
        ]);

        $user = $request->user();

        $order = Order::with('detail')
            ->where('id', $validated['order_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        try {
            if ($validated['payment_method'] === 'Cash On Delivery') {
                return $this->cashOnDelivery($order, $user->id);
            }

            if (empty($validated['razorpay_payment_id'])) {
                return $this->createRazorpayOrder($order, $user->id);
            }

            return $this->completeRazorpayPayment(
                $order,
                $user->id,
                $validated['razorpay_payment_id']
            );

        } catch (\Throwable $exception) {
            Log::error('Payment API error', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'payment_method' => $validated['payment_method'],
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to process payment.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    private function cashOnDelivery(Order $order, int $userId)
    {
        $existingPayment = Payment::where('order_id', $order->id)
            ->where('user_id', $userId)
            ->first();

        if (
            $existingPayment &&
            $existingPayment->payment_status === 'Paid'
        ) {
            return response()->json([
                'status' => false,
                'message' => 'This order is already paid.',
            ], 422);
        }

        $payment = Payment::updateOrCreate(
            [
                'order_id' => $order->id,
                'user_id' => $userId,
            ],
            [
                'amount' => $order->amount,
                'payment_method' => 'Cash On Delivery',
                'payment_status' => 'Pending',
                'razorpay_payment_id' => null,
            ]
        );

        $order->update([
            'status' => 'Pending',
        ]);

        $payment->refresh();
        $payment->load('order.detail');
        $this->sendPaymentMail($payment);

        return response()->json([
            'status' => true,
            'message' => 'Cash On Delivery selected successfully.',
            'data' => [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $payment->amount,
                'payment_status' => $payment->payment_status,
                'name' => $order->detail->name ?? null,
                'email' => $order->detail->email ?? null,
                'number' => $order->detail->number ?? null,
            ],
        ], 200);
    }

    private function createRazorpayOrder(Order $order, int $userId)
    {
        $existingPayment = Payment::where('order_id', $order->id)
            ->where('user_id', $userId)
            ->first();

        if (
            $existingPayment &&
            $existingPayment->payment_status === 'Paid'
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Payment already completed.',
            ], 422);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($order->amount * 100),
            'currency' => 'INR',
        ]);

        $payment = Payment::updateOrCreate(
            [
                'order_id' => $order->id,
                'user_id' => $userId,
            ],
            [
                'amount' => $order->amount,
                'payment_method' => 'Razorpay',
                'payment_status' => 'Paid',

                // Temporary order_... ID store hogi.
                // Payment complete hone ke baad pay_... se replace hogi.
                'razorpay_payment_id' => $razorpayOrder['id'],
            ]
        );

        $payment->refresh();
        $payment->load('order.detail');
        $this->sendPaymentMail($payment);

        return response()->json([
            'status' => true,
            'message' => 'Razorpay order created successfully.',
            'data' => [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'razorpay_payment_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'payment_status' => $payment->payment_status,
                'name' => $order->detail->name ?? null,
                'email' => $order->detail->email ?? null,
                'number' => $order->detail->number ?? null,
            ],
        ], 201);
    }

    private function completeRazorpayPayment(
        Order $order,
        int $userId,
        string $razorpayPaymentId
    ) {
        $payment = Payment::where('order_id', $order->id)
            ->where('user_id', $userId)
            ->where('payment_method', 'Razorpay')
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Create Razorpay order first.',
            ], 404);
        }

        if ($payment->payment_status === 'Paid') {
            return response()->json([
                'status' => false,
                'message' => 'Payment already completed.',
            ], 422);
        }

        $storedRazorpayOrderId = $payment->razorpay_payment_id;

        if (!str_starts_with($storedRazorpayOrderId, 'order_')) {
            return response()->json([
                'status' => false,
                'message' => 'Razorpay order ID not found.',
            ], 422);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayPayment = $api->payment->fetch(
            $razorpayPaymentId
        );

        if (($razorpayPayment['order_id'] ?? null) !== $storedRazorpayOrderId) {
            return response()->json([
                'status' => false,
                'message' => 'Razorpay order does not match.',
            ], 422);
        }

        if (($razorpayPayment['status'] ?? null) !== 'captured') {
            return response()->json([
                'status' => false,
                'message' => 'Razorpay payment is not captured.',
                'razorpay_status' => $razorpayPayment['status'] ?? null,
            ], 422);
        }

        $expectedAmount = (int) round($order->amount * 100);
        $paidAmount = (int) ($razorpayPayment['amount'] ?? 0);

        if ($paidAmount !== $expectedAmount) {
            return response()->json([
                'status' => false,
                'message' => 'Payment amount does not match.',
            ], 422);
        }

        DB::transaction(function () use (
            $payment,
            $order,
            $razorpayPayment,
            $razorpayPaymentId
        ) {
            $payment->update([
                'payment_method' => strtoupper(
                    $razorpayPayment['method'] ?? 'Razorpay'
                ),
                'payment_status' => 'Paid',
                'razorpay_payment_id' => $razorpayPaymentId,
            ]);

            $order->update([
                'status' => 'Processing',
            ]);
        });

        $payment->refresh();
        $payment->load('order.detail');

        $mailSent = $this->sendPaymentMail($payment);

        return response()->json([
            'status' => true,
            'message' => $mailSent
                ? 'Payment successful and confirmation email sent.'
                : 'Payment successful but confirmation email could not be sent.',
            'mail_sent' => $mailSent,
            'data' => $payment,
        ], 200);
    }

    private function sendPaymentMail(Payment $payment): bool
    {
        try {
            $email = $payment->order->detail->email ?? null;

            if (!$email) {
                return false;
            }

            Mail::to($email)->send(
                new PaymentSuccessMail($payment)
            );

            return true;

        } catch (\Throwable $exception) {
            Log::error('Payment confirmation email failed', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'email' => $payment->order->detail->email ?? null,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}