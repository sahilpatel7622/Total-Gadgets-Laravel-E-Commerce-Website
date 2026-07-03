@extends('layouts.user')

@section('title','Checkout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">

<body>

<div id="loader">
    <div class="spinner"></div>
</div>

    <div class="order-page">
        <h1 class="checkout-title" style="position: relative; bottom: 40px">Checkout</h1>
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
                            <input type="radio" name="payment_method" value="COD" checked>
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

                        <div class="summary-row">
                            <span>Items Subtotal</span>
                            <strong>₹{{ number_format($cartTotal,2) }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Estimated Tax</span>
                            <strong>₹0.00</strong>
                        </div>

                        <div class="summary-row">
                            <span>Standard Delivery</span>
                            <strong>FREE</strong>
                        </div>

                        <hr>

                        <div class="grand-total">
                            <span>Grand Total</span>
                            <strong>₹{{ number_format($cartTotal,2) }}</strong>
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

            @if(session('success'))
                <div class="otp-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="otp-error text-center">
                    {{ session('error') }}
                </div>
            @endif

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
