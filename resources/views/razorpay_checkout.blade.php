@extends('layouts.user')

@section('title','Razorpay Payment')

@section('content')

<form id="razorpayForm" action="{{ route('razorpay.success') }}" method="POST">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
window.onload = function () {
    var options = {
        key: "{{ $key }}",
        amount: "{{ (int)($amount * 100) }}",
        currency: "INR",
        name: "TG",
        description: "Order Payment",
        handler: function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpayForm').submit();
        },
        prefill: {
            name: "{{ Auth::user()->name }}",
            email: "{{ Auth::user()->email }}",
            contact: "{{ Auth::user()->number }}"
        },
        theme: {
            color: "#4f46e5"
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
};
</script>

@endsection