<x-mail::layout>
<x-slot:header>
<x-mail::header :url="config('app.url')">
Total Gadgets
</x-mail::header>
</x-slot:header>

# Hello {{ $user->name }},

A new coupon is available for you.

<x-mail::panel>
**Coupon Code:** {{ $coupon->code }}

**Discount:**
@if($coupon->type === 'fixed')
    ₹{{ number_format($coupon->discount_value, 2) }}
@else
    {{ number_format($coupon->discount_value, 2) }}%
@endif

**Minimum Order:** ₹{{ number_format($coupon->minimum_order_amount, 2) }}

**Valid From:** {{ $coupon->start_date->format('d M Y') }}

**Valid Until:** {{ $coupon->end_date->format('d M Y') }}
</x-mail::panel>

<x-mail::button :url="'http://127.0.0.1:8000/products'">
    Shop Now
</x-mail::button>

<p>
    Regards,<br>
    <strong>Total Gadgets Team</strong>
</p>

<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} Total Gadgets. All Rights Reserved.
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>