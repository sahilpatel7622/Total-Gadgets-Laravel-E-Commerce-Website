<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\CouponCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::withCount('users');
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('user_type', 'like', "%{$search}%");

                if (strtolower($search) === 'active') {
                    $q->orWhere('status', 1);
                }
                if (strtolower($search) === 'inactive') {
                    $q->orWhere('status', 0);
                }
            });
        }
        $coupons = $query->latest()->paginate($request->input('per_page', 10))->withQueryString();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $users = User::where('status', 1)
            ->where('role', 'user')
            ->orderBy('name')
            ->get();
        return view('admin.coupons.create', compact('users'));
    }

    public function generateCode()
    {
        return response()->json([
            'code' => $this->generateUniqueCouponCode(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required','string','max:20',
                'unique:coupons,code',],

            'type' => ['required',
                Rule::in(['fixed', 'percentage']),
            ],

            'discount_value' => ['required','numeric','min:0.01',],
            'user_type' => ['required',
                Rule::in(['all', 'selected']),],

            'user_ids' => ['nullable','array',
                'required_if:user_type,selected',],

            'user_ids.*' => ['integer','exists:user,id'],

            'minimum_order_amount' => ['required','numeric','min:2000'],

            'usage_limit' => ['nullable','integer','min:1'],

            'per_user_limit' => ['required','integer','min:1'],

            'start_date' => ['required','date'],

            'end_date' => ['required','date','after:start_date'],

            'status' => ['required','boolean'],
        ]);

        if ($validated['type'] === 'percentage') {
            if ($validated['discount_value'] < 2) {
                return back()->withInput()->withErrors(['discount_value' => 'Percentage discount must be at least 2%.']);
            }
            if ($validated['discount_value'] > 100) {
                return back()->withInput()->withErrors(['discount_value' => 'Percentage discount cannot be greater than 100%.']);
            }
        } elseif ($validated['type'] === 'fixed') {
            if ($validated['discount_value'] < 10) {
                return back()->withInput()->withErrors(['discount_value' => 'Fixed discount must be at least 10.']);
            }
        }
        $coupon = DB::transaction(function () use ($validated) {
            $code = !empty($validated['code'])
                ? strtoupper(trim($validated['code']))
                : $this->generateUniqueCouponCode();

            $coupon = Coupon::create([
                'code' => $code,
                'type' => $validated['type'],
                'discount_value' => $validated['discount_value'],
                'user_type' => $validated['user_type'],
                'minimum_order_amount' =>
                    $validated['minimum_order_amount'],
                'usage_limit' => $validated['usage_limit'] ?? null,
                'per_user_limit' => $validated['per_user_limit'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
            ]);

            if ($validated['user_type'] === 'selected') {
                $coupon->users()->sync($validated['user_ids']);
            }
            return $coupon;
        });

        if ($coupon->user_type === 'all') { 
            // All active normal users
            $users = User::where('role', 'user')
                ->where('status', 1)
                ->whereNotNull('email')
                ->get();

        } else {
            // Only selected active normal users
            $users = User::whereIn(
                    'id',
                    $validated['user_ids'] ?? []
                )
                ->where('role', 'user')
                ->where('status', 1)
                ->whereNotNull('email')
                ->get();
        }
        foreach ($users as $user) {
            Mail::to($user->email)
            ->queue(new CouponCreatedMail($coupon, $user));
        }

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::with('users')->findOrFail($id);
        $users = User::where('status', 1)
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view(
            'admin.coupons.edit',
            compact('coupon', 'users')
        );
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $validated = $request->validate([
            'type' => ['required',
                Rule::in(['fixed', 'percentage'])],

            'discount_value' => ['required','numeric','min:0.01' ],

            'user_type' => ['required',
                Rule::in(['all', 'selected'])],

            'user_ids' => ['nullable','array',
                'required_if:user_type,selected'],

            'user_ids.*' => ['integer','exists:user,id'],

            'minimum_order_amount' => ['required','numeric','min:2000'],

            'usage_limit' => ['nullable','integer','min:1'],

            'per_user_limit' => ['required','integer', 'min:1'],

            'start_date' => ['required','date',],

            'end_date' => ['required', 'date', 'after:start_date',],
        ]);

        if ($validated['type'] === 'percentage') {
            if ($validated['discount_value'] < 2) {
                return back()->withInput()->withErrors(['discount_value' => 'Percentage discount must be at least 2%.']);
            }
            if ($validated['discount_value'] > 100) {
                return back()->withInput()->withErrors(['discount_value' => 'Percentage discount cannot be greater than 100%.']);
            }
        } elseif ($validated['type'] === 'fixed') {
            if ($validated['discount_value'] < 10) {
                return back()->withInput()->withErrors(['discount_value' => 'Fixed discount must be at least 10.']);
            }
        }

        $hasChanges = false;

        DB::transaction(function () use ($coupon, $validated, &$hasChanges) {
            $coupon->fill([
                'type' => $validated['type'],
                'discount_value' => $validated['discount_value'],
                'user_type' => $validated['user_type'],
                'minimum_order_amount' =>
                    $validated['minimum_order_amount'],
                'usage_limit' => $validated['usage_limit'] ?? null,
                'per_user_limit' => $validated['per_user_limit'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            if ($coupon->isDirty()) {
                $hasChanges = true;
                $coupon->save();
            }

            if ($validated['user_type'] === 'selected') {
                $syncResult = $coupon->users()->sync($validated['user_ids'] ?? []);
            } else {
                $syncResult = $coupon->users()->sync([]);
            }

            if (count($syncResult['attached']) > 0 || count($syncResult['detached']) > 0 || count($syncResult['updated']) > 0) {
                $hasChanges = true;
            }
        });

        if ($hasChanges) {
            return redirect()
                ->route('coupons.index')
                ->with('success', 'Coupon updated successfully.');
        }

        return redirect()
            ->route('coupons.index')
            ->with('info', 'No changes were made to the coupon.');
    }

    public function changeStatus($id)
    {
        $coupon = Coupon::findOrFail($id);

        if (now()->gt($coupon->end_date)) {
            return back()->with(
                'error',
                'Cannot change status of an expired coupon.'
            );
        }

        $coupon->update([
            'status' => !$coupon->status,
        ]);

        return back()->with(
            'success',
            'Coupon status updated successfully.'
        );
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->users()->detach();
        $coupon->delete();
        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    private function generateUniqueCouponCode(): string
    {
        do {
            $code = 'TG' . strtoupper(Str::random(6));
        } while (Coupon::where('code', $code)->exists());
        return $code;
    }

    public function show($id)
    {
        $coupon = Coupon::with('users')->findOrFail($id);
        $usedOrders = Order::with('user')
            ->where('coupon_id', $coupon->id)
            ->latest()
            ->paginate(10);

        $totalUsage = Order::where('coupon_id', $coupon->id)->count();
        $uniqueUsers = Order::where('coupon_id', $coupon->id)
            ->distinct()
            ->count('user_id');

        $totalDiscount = Order::where('coupon_id', $coupon->id)
            ->sum('coupon_discount');
        return view('admin.coupons.view', compact(
            'coupon',
            'usedOrders',
            'totalUsage',
            'uniqueUsers',
            'totalDiscount'
        ));
    }


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $code = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        if (!$coupon->status || now()->lt($coupon->start_date) || now()->gt($coupon->end_date)) {
            return response()->json(['success' => false, 'message' => 'This coupon is inactive or expired.']);
        }

        if (session()->has('applied_coupon') && session('applied_coupon')['code'] === $coupon->code) {
            return response()->json(['success' => false, 'message' => 'This coupon is already applied to your cart.']);
        }

        if ($coupon->usage_limit !== null) {
            $totalUsed = Order::where('coupon_code', $coupon->code)
                ->where('status', '!=', 'Cancelled')
                ->count();
            if ($totalUsed >= $coupon->usage_limit) {
                return response()->json(['success' => false, 'message' => 'This coupon has reached its maximum usage limit.']);
            }
        }

        $userUsed = Order::where('user_id', Auth::id())
            ->where('coupon_code', $coupon->code)
            ->where('status', '!=', 'Cancelled')
            ->count();

        if ($userUsed >= $coupon->per_user_limit) {
            return response()->json(['success' => false, 'message' => 'You have already used this coupon maximum allowed times.']);
        }

        if ($coupon->user_type === 'selected') {
            $isEligible = $coupon->users()->where('user_id', Auth::id())->exists();
            if (!$isEligible) {
                return response()->json(['success' => false, 'message' => 'You are not eligible for this coupon.']);
            }
        }

        $cartTotal = 0;
        if ($request->buy_now_product_id) {
            $product = \App\Models\product::find($request->buy_now_product_id);
            if ($product) {
                $cartTotal = $product->price;
            }
        } else {
            $cartItems = \App\Models\Cart::with('product')->where('user_id', Auth::id())->get();
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });
        }

        if ($cartTotal == 0) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }

        if ($cartTotal < $coupon->minimum_order_amount) {
            return response()->json(['success' => false, 'message' => 'Minimum order amount of ₹' . number_format($coupon->minimum_order_amount, 2) . ' is required.']);
        }

        $discountAmount = 0;
        if ($coupon->type === 'fixed') {
            $discountAmount = $coupon->discount_value;
        } else {
            $discountAmount = ($cartTotal * $coupon->discount_value) / 100;
        }

        if ($discountAmount > $cartTotal) {
            $discountAmount = $cartTotal;
        }

        session([
            'applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $discountAmount,
                'type' => $coupon->type,
                'value' => $coupon->discount_value
            ]
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Coupon applied successfully!',
            'discount' => $discountAmount,
            'new_total' => $cartTotal - $discountAmount,
            'code' => $coupon->code
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true, 'message' => 'Coupon removed successfully.']);
    }
}