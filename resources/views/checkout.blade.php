@extends('layouts.user')

@section('title','Checkout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">

<body>

<div id="loader">
    <div class="spinner"></div>
</div>

    <div class="order-page">
        <h1 class="checkout-title" style="position: relative; bottom: 25px">Checkout</h1>
        <form id="forgotForm" action="{{ route('place.order') }}" method="POST" novalidate>
            @csrf

            @if(isset($buyNowProductId))
                <input type="hidden" name="buy_now_product_id" value="{{ $buyNowProductId }}">
            @endif

            <div class="order-grid">

                <div class="left-area">

                    <div class="order-card">
                        <h2>📍 Shipping Address</h2>

                        <div class="two-col">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="text" name="number" value="{{ old('number', Auth::user()->number) }}">
                                @error('number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}">                        
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Full Address</label>
                            <textarea name="address" rows="4" placeholder="House no, street, area">{{ old('address') }}</textarea>                       
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="three-col">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="Mumbai">
                                @error('city')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state" value="{{ old('state') }}" placeholder="Maharastra">
                                @error('state')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Zip</label>
                                <input
                                    type="text"
                                    name="pincode"
                                    maxlength="6"
                                    inputmode="numeric"
                                    placeholder="400001"
                                    value="{{ old('pincode') }}"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)"
                                >
                                @error('pincode')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="order-card">
                        <h2>💳 Payment Method</h2>

                        <label class="payment-option">
                            <span>Cash on Delivery (COD)</span>
                            <input type="radio" name="payment_method" value="Cash On Delivery">
                        </label>

                        <label class="payment-option">
                            <span>Razorpay Online Payment</span>
                            <input type="radio" name="payment_method" value="RAZORPAY">
                        </label>

                        @error('payment_method')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="summary-box">
                    <div class="summary-head">
                        🛒 Order Summary
                    </div>

                    <div class="summary-body">

                        @if(isset($cartItems) && count($cartItems) > 0)
                            <div class="checkout-items-list" style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px dashed #ccc;">
                                @foreach($cartItems as $item)
                                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                        <div style="width: 50%; font-size: 14px; font-weight: 500; color: #333; line-height: 1.4; padding-right: 10px;">
                                            {{ $item->product->name }}
                                        </div>
                                        <div style="width: 25%; text-align: center; font-size: 13px; color: #666; white-space: nowrap;">
                                            Qty: {{ $item->quantity }}
                                        </div>
                                        <div style="width: 25%; text-align: right; font-size: 14px; font-weight: 600; color: #111; white-space: nowrap;">
                                            ₹{{ number_format(($item->product->price * $item->quantity), 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @php
                            $tax = $taxSettings ?? null;
                            $appliedCoupon = session('applied_coupon');
                            $discountAmount = $appliedCoupon ? $appliedCoupon['discount'] : 0;
                            
                            $afterDiscountTotal = $cartTotal - $discountAmount;
                            if ($afterDiscountTotal < 0) $afterDiscountTotal = 0;

                            $taxAmount = 0;
                            $deliveryChargeAmount = 0;
                            
                            if($tax) {
                                $taxAmount = ($afterDiscountTotal * $tax->tax_percentage) / 100;
                                if($tax->free_delivery_above !== null && $afterDiscountTotal >= $tax->free_delivery_above) {
                                    $deliveryChargeAmount = 0;
                                } else {
                                    $deliveryChargeAmount = $tax->delivery_charge ?? 0;
                                }
                            }
                            
                            $finalTotal = $afterDiscountTotal + $taxAmount + $deliveryChargeAmount;
                        @endphp

                        <div class="coupon-section" style="margin-bottom: 20px;">
                            @if(isset($availableCoupons) && $availableCoupons->count() > 0)
                                <div style="margin-bottom: 10px;">
                                    <select id="available_coupons_select" onchange="document.getElementById('coupon_code_input').value = this.value; if(this.value) { applyCoupon(); } else { removeCoupon(); }" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;">
                                        <option value="">-- Select an Available Coupon --</option>
                                        @foreach($availableCoupons as $coupon)
                                            <option value="{{ $coupon->code }}" {{ ($appliedCoupon && $appliedCoupon['code'] == $coupon->code) ? 'selected' : '' }}>
                                                {{ $coupon->code }} - 
                                                @if($coupon->type == 'fixed')
                                                    ₹{{ $coupon->discount_value }} OFF
                                                @else
                                                    {{ $coupon->discount_value }}% OFF
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div style="display: flex; gap: 10px;">
                                <input type="text" id="coupon_code_input" placeholder="Enter Coupon Code" 
                                       value="{{ $appliedCoupon ? $appliedCoupon['code'] : '' }}" 
                                       style="flex: 1; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;"
                                       {{ $appliedCoupon ? 'disabled' : '' }}>
                                
                                <button type="button" id="coupon_action_btn" onclick="{{ $appliedCoupon ? 'removeCoupon()' : 'applyCoupon()' }}" style="padding: 8px 15px; background: {{ $appliedCoupon ? '#dc3545' : '#4f46e5' }}; color: white; border: none; border-radius: 4px; cursor: pointer;">{{ $appliedCoupon ? 'Remove' : 'Apply' }}</button>
                            </div>
                            <small id="coupon_message" style="display: block; margin-top: 5px; color: #dc3545;"></small>
                        </div>

                        <hr>

                        <div class="summary-row">
                            <span>Items Subtotal</span>
                            <strong>₹{{ number_format($cartTotal,2) }}</strong>
                        </div>

                        <div class="summary-row" id="discount_row" style="color: #28a745; display: {{ $appliedCoupon ? 'flex' : 'none' }};">
                            <span id="discount_label">Coupon Discount ({{ $appliedCoupon ? $appliedCoupon['code'] : '' }})</span>
                            <strong id="discount_amount" style="color: #28a745;">- ₹{{ number_format($discountAmount, 2) }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Estimated Tax</span>
                            <strong id="tax_amount">₹{{ number_format($taxAmount, 2) }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Standard Delivery</span>
                            <strong id="delivery_amount">
                                @if($deliveryChargeAmount == 0)
                                    <span class="text-success">FREE</span>
                                @else
                                    ₹{{ number_format($deliveryChargeAmount, 2) }}
                                @endif
                            </strong>
                        </div>

                        <hr>

                        <div class="grand-total">
                            <span>Grand Total</span>
                            <strong id="grand_total_amount">₹{{ number_format($finalTotal,2) }}</strong>
                        </div>

                        <button type="submit" class="place-btn">
                            PLACE ORDER ✓
                        </button>

                        <p class="secure-text">🛡 Secure and encrypted payment</p>

                    </div>
                </div>

            </div>

        </form>

    </div>


@if(session('show_otp_modal'))

<div class="otp-overlay">

    <div id="otpLoader">
        <div class="otpSpinner"></div>
    </div>

    <div class="otp-popup">

        <div class="otp-popup-header">
            <h3>Order Verification</h3>
        </div>

        <div class="otp-popup-body">
            <p>Enter the OTP sent to your email.</p>



            <form id="otpForm" action="{{ route('order.otp.verify') }}" method="POST">
                @csrf

                <input
                    type="text"
                    name="otp"
                    value="{{ old('otp') }}"
                    maxlength="6"
                    placeholder="Enter 6 Digit OTP"
                    inputmode="numeric"
                    class="otp-input"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                >

                @error('otp')
                    <div class="otp-error">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" id="otpSubmitBtn" class="otp-btn">
                    Verify OTP
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    document.getElementById("otpForm").addEventListener("submit", function () {
        document.getElementById("otpLoader").style.display = "flex";
        let btn = document.getElementById("otpSubmitBtn");
        btn.disabled = true;
        btn.innerHTML = "Verifying...";

    });
</script>
@endif


</body>

<script>
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const placeBtn = document.querySelector('.place-btn');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'RAZORPAY') {
                placeBtn.innerText = 'PAY WITH RAZORPAY ✓';
            } else {
                placeBtn.innerText = 'PLACE ORDER ✓';
            }
        });
    });
</script>

<script>
document.getElementById("forgotForm").addEventListener("submit", function () {
    document.getElementById("loader").style.display = "flex";
    let btn = document.querySelector(".place-btn");
    btn.disabled = true;
    btn.innerHTML = "Sending...";
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}",
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}",
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

<script>
function applyCoupon() {
    const code = document.getElementById('coupon_code_input').value;
    const msg = document.getElementById('coupon_message');
    msg.style.color = '#dc3545';
    if (!code) {
        msg.innerText = 'Please enter a coupon code';
        return;
    }
    msg.innerText = 'Applying...';
    
    fetch('{{ route('apply.coupon') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            coupon_code: code,
            buy_now_product_id: '{{ $buyNowProductId ?? "" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('discount_row').style.display = 'flex';
            document.getElementById('discount_label').innerText = 'Coupon Discount (' + data.code + ')';
            document.getElementById('discount_amount').innerText = '- ₹' + parseFloat(data.discount).toFixed(2);
            document.getElementById('grand_total_amount').innerText = '₹' + parseFloat(data.new_total).toFixed(2);
            document.getElementById('tax_amount').innerText = '₹' + parseFloat(data.tax_amount).toFixed(2);
            if(parseFloat(data.delivery_charge) === 0) {
                document.getElementById('delivery_amount').innerHTML = '<span class="text-success">FREE</span>';
            } else {
                document.getElementById('delivery_amount').innerText = '₹' + parseFloat(data.delivery_charge).toFixed(2);
            }
            
            document.getElementById('coupon_code_input').disabled = true;
            let select = document.getElementById('available_coupons_select');
            if (select) {
                select.value = data.code;
            }

            let btn = document.getElementById('coupon_action_btn');
            btn.innerText = 'Remove';
            btn.style.background = '#dc3545';
            btn.onclick = removeCoupon;
            
            msg.innerText = '';
        } else {
            msg.innerText = data.message;
        }
    })
    .catch(error => {
        msg.innerText = 'Something went wrong';
    });
}

function removeCoupon() {
    const msg = document.getElementById('coupon_message');
    msg.innerText = 'Removing...';
    msg.style.color = '#333';
    
    fetch('{{ route('remove.coupon') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            buy_now_product_id: '{{ $buyNowProductId ?? "" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('discount_row').style.display = 'none';
            document.getElementById('grand_total_amount').innerText = '₹' + parseFloat(data.new_total).toFixed(2);
            document.getElementById('tax_amount').innerText = '₹' + parseFloat(data.tax_amount).toFixed(2);
            if(parseFloat(data.delivery_charge) === 0) {
                document.getElementById('delivery_amount').innerHTML = '<span class="text-success">FREE</span>';
            } else {
                document.getElementById('delivery_amount').innerText = '₹' + parseFloat(data.delivery_charge).toFixed(2);
            }
            
            let input = document.getElementById('coupon_code_input');
            input.disabled = false;
            input.value = '';
            
            let select = document.getElementById('available_coupons_select');
            if (select) {
                select.disabled = false;
                select.value = '';
            }
            
            let btn = document.getElementById('coupon_action_btn');
            btn.innerText = 'Apply';
            btn.style.background = '#4f46e5';
            btn.onclick = applyCoupon;
            
            msg.innerText = '';
        }
    })
    .catch(error => {
        msg.innerText = 'Something went wrong';
        msg.style.color = '#dc3545';
    });
}
</script>
