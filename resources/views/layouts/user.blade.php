<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<nav class="navbar" id="navbar">

    <a href="{{ route('dashboard') }}" class="logo">
        <img src="{{ asset('images/tg-logo.png') }}" alt="Total Gadgets Logo">
        <span>Total Gadgets</span>
    </a>

    <button type="button" class="mobile-btn" id="mobileBtn">☰</button>

    @if (!request()->routeIs('checkout') && !request()->routeIs('buy.now'))
    <div class="nav-menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Home
        </a>

        <a href="{{ route('products') }}" class="{{ request()->routeIs('products') || request()->routeIs('product.detail') ? 'active' : '' }}">
            Products
        </a>

        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
            About Us
        </a>

        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
            Contact Us
        </a>
    </div>

    <div class="right-menu">

        @auth
            <a href="{{ route('wishlist.index') }}" class="cart wishlist-nav">
                ❤️ Wishlist (<span id="wishlistCount">{{ $wishlistCount ?? 0 }}</span>)            
            </a>
            
            <a href="#" class="cart open-cart">
                🛒 Cart (<span id="cartCount">{{ $cartCount ?? 0 }}</span>)            
            </a>

            <div class="cart-sidebar" id="cartSidebar">

                <div class="cart-sidebar-header">
                    <h3>Shopping Cart</h3>
                    <button type="button" class="cart-close" id="cartClose">×</button>
                </div>

                <div class="cart-sidebar-body">

                    @if(isset($cartItems) && $cartItems->count())

                        @foreach($cartItems as $item)

                            @if($item->product)
                                <div class="cart-item" id="cartItem{{ $item->id }}">
                                    <a href="{{ route('product.detail', $item->product->slug) }}">
                                        @if($item->product->image)
                                            <img src="{{ asset('product/'.$item->product->image) }}" class="cart-item-img" alt="{{ $item->product->name }}">
                                        @else
                                            <div class="cart-item-img" style="background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;text-align:center;border-radius:8px;">
                                                No Image
                                            </div>
                                        @endif
                                    </a>
                                    <div class="cart-item-info">

                                        <div class="cart-single-row">
                                            <a href="{{ route('product.detail', $item->product->slug) }}" class="cart-name">
                                                {{ $item->product->name }}
                                            </a>

                                            <span class="cart-price">
                                                ₹{{ number_format($item->product->price, 2) }}
                                            </span>

                                            <div class="cart-qty">

                                                <form action="{{ route('cart.update_qty', $item->id) }}" method="POST" class="cart-update-form">
                                                    @csrf
                                                    <input type="hidden" name="cart_action" value="minus">
                                                    <button type="submit">-</button>
                                                </form>

                                                <span id="qty-{{ $item->id }}">
                                                    {{ $item->quantity }}
                                                </span>

                                                <form action="{{ route('cart.update_qty', $item->id) }}" method="POST" class="cart-update-form">
                                                    @csrf
                                                    <input type="hidden" name="cart_action" value="plus">
                                                    <button type="submit">+</button>
                                                </form>

                                            </div>

                                            <a href="{{ route('cart.remove', $item->id) }}" class="remove-cart">
                                                Remove
                                            </a>

                                        </div>

                                    </div>

                                </div>
                            @endif

                        @endforeach

                    @else

                        <div class="cart-empty">
                            <div class="cart-empty-icon">🛒</div>
                            <h4>Your cart is empty</h4>
                            <p>Add products to your cart.</p>
                        </div>

                    @endif

                </div>

                <div class="cart-sidebar-footer" id="cartFooter" style="{{ isset($cartItems) && $cartItems->count() > 0 ? '' : 'display: none;' }}">
                    <div class="cart-total">
                        <span>Total</span>
                        <strong>₹<span id="cartTotal">{{ number_format($cartTotal ?? 0, 2) }}</span></strong>                    
                    </div>

                    <a href="{{ route('checkout') }}" class="cart-checkout-btn">
                        Checkout
                    </a>
                </div>

            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <button type="button" class="avatar" id="profileToggle">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </button>

                <div class="profile-menu">
                    <a href="{{ route('profile') }}">👤 Profile</a>
                    <a href="{{ route('my.orders') }}">📦 My Orders</a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">↪ Logout</button>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="nav-auth">Login</a>
            <a href="{{ route('register') }}" class="nav-auth">Register</a>
        @endguest

    </div>
    @else

    @endif

</nav>

<div class="cart-overlay" id="cartOverlay"></div>

@if(session('successe') || session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session("successe") ?? session("success") }}',
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

<div class="container">
    @yield('content')
</div>

@if (
    !request()->is('profile*') &&
    !request()->routeIs('checkout') &&
    !request()->routeIs('my.orders') &&
    !request()->routeIs('wishlist.index') &&
    !request()->routeIs('buy.now')
)
<footer class="footer">
    <div class="footer-container">

        <div class="footer-col">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('images/tg-logo.png') }}" alt="Total Gadgets Logo">
                <span style="position: relative; bottom:18px">Total Gadgets</span>
            </a>

            <p>
                Your trusted destination for Mobiles, Laptops, Smart TVs and
                Accessories at the best prices.
            </p>
        </div>

        <div class="footer-col">
            <h3>Quick Links</h3>
            <a href="{{ route('dashboard') }}">Home</a>
            <a href="{{ route('products') }}">Products</a>
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('contact') }}">Contact Us</a>
        </div>

        <div class="footer-col">
            <h3>Categories</h3>
            <a href="{{ route('products') }}?category=mobiles">Mobiles</a>
            <a href="{{ route('products') }}?category=laptops">Laptops</a>
            <a href="{{ route('products') }}?category=smart-tvs">Smart TVs</a>
            <a href="{{ route('products') }}?category=accessories">Accessories</a>
        </div>

        <div class="footer-col">
            <h3>Contact</h3>
            <p>📍 Ahmedabad, Gujarat</p>
            <p>📞 +91 9876543210</p>
            <p>✉️ support@totalgadgets.com</p>
        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Total Gadgets. All Rights Reserved.
    </div>
</footer>
@endif

<script>
document.getElementById('mobileBtn')?.addEventListener('click', function(){
    document.getElementById('navbar')?.classList.toggle('open');
});

document.getElementById('profileToggle')?.addEventListener('click', function(e){
    e.stopPropagation();
    document.getElementById('profileDropdown')?.classList.toggle('open');
});

document.addEventListener('click', function(){
    document.getElementById('profileDropdown')?.classList.remove('open');
});

document.addEventListener('DOMContentLoaded', function () {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartClose = document.getElementById('cartClose');

    document.querySelectorAll('.open-cart').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            cartSidebar?.classList.add('active');
            cartOverlay?.classList.add('active');
        });
    });

    function closeCart(){
        cartSidebar?.classList.remove('active');
        cartOverlay?.classList.remove('active');
    }

    cartClose?.addEventListener('click', closeCart);
    cartOverlay?.addEventListener('click', closeCart);

    @if(session('cart_open'))
        cartSidebar?.classList.add('active');
        cartOverlay?.classList.add('active');
    @endif
});
</script>

{{-- Cart --}}
@if(session('cart_open'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('cartSidebar')?.classList.add('active');
    document.getElementById('cartOverlay')?.classList.add('active');
});
</script>
@endif

<script>
document.addEventListener('submit', function(e){
    if(!e.target.classList.contains('cart-update-form')) return;

    e.preventDefault();

    const form = e.target;

    fetch(form.getAttribute('action'), {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(!data.success) return;

        document.getElementById('cartCount').innerText = data.cartCount;
        document.getElementById('cartTotal').innerText = data.cartTotal;

        if(data.removed){
            document.getElementById('cartItem' + data.cartId)?.remove();

            if(data.cartCount == 0){
                document.querySelector('.cart-sidebar-body').innerHTML = `
                    <div class="cart-empty">
                        <div class="cart-empty-icon">🛒</div>
                        <h4>Your cart is empty</h4>
                        <p>Add products to your cart.</p>
                    </div>
                `;
                let cartFooter = document.getElementById('cartFooter');
                if (cartFooter) cartFooter.style.display = 'none';
            }
        }else{
            document.getElementById('qty-' + data.cartId).innerText = data.quantity;
        }
    })
    .catch(err => console.log(err));
});

document.addEventListener('click', function(e){
    let btn = e.target.closest('.wishlist-toggle');
    if(!btn) return;
    
    e.preventDefault();
    
    let productId = btn.dataset.id;
    let url = `/wishlist/toggle/${productId}`;
    let csrf = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('wishlistCount').innerText = data.wishlistCount;
            
            if(data.status === 'added') {
                if(btn.innerText.includes('Wishlist')) {
                    btn.innerHTML = '❤️ Remove from Wishlist';
                } else {
                    btn.innerHTML = '❤️'; 
                }
                btn.classList.add('active');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Product added to wishlist.',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                if(btn.innerText.includes('Wishlist')) {
                    btn.innerHTML = '🤍 Add to Wishlist';
                } else {
                    btn.innerHTML = '🤍';
                }
                btn.classList.remove('active');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Product removed from wishlist.',
                    showConfirmButton: false,
                    timer: 1500
                });
                
                // If we are on the wishlist page, remove the card
                let card = document.getElementById('wishlist-item-' + productId);
                if(card) {
                    card.remove();
                    // If no items left, reload to show empty state
                    if(data.wishlistCount == 0) {
                        window.location.reload();
                    }
                }
            }
        } else if (data.message === "Unauthenticated.") {
            window.location.href = "{{ route('login') }}";
        }
    })
    .catch(err => {
        console.log(err);
        if(err.message.includes('Unexpected token')) {
            window.location.href = "{{ route('login') }}";
        }
    });
});
</script>

@yield('script')

</body>
</html>