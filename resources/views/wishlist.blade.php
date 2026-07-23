@extends('layouts.user')

@section('title', 'My Wishlist')

@section('content')
<style>
.wishlist-page{
    max-width:1280px;
    margin:auto;
    padding:20px;
}

.wishlist-hero{
    background:linear-gradient(135deg, #3b82f6);
    color:#fff;
    padding:35px 40px;
    border-radius:18px;
    margin-bottom:25px;
}

.wishlist-hero h1{
    font-size:34px;
    margin-bottom:8px;
    font-weight:700;
}

.wishlist-hero p{
    font-size:15px;
}

.products-grid{
    display:grid;
    grid-template-columns:repeat(3,380px);
    gap:40px;
    justify-content:center;
}

.product-card{
    width:370px;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    border:1px solid #eee;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    transition:.3s;
    display:flex;
    flex-direction:column;
}

.product-card:hover{
    transform:translateY(-5px);
    box-shadow:0 12px 25px rgba(0,0,0,.12);
}

.product-img-box{
    height:180px;
    background:#fafafa;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:15px;
    position: relative;
}

.product-img-box a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.wishlist-toggle{
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fff;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    font-size: 16px;
    transition: 0.3s;
    text-decoration: none;
    z-index: 2;
}

.wishlist-toggle:hover {
    transform: scale(1.1);
}

.product-img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
    transition:.3s;
}

.product-card:hover .product-img{
    transform:scale(1.05);
}

.product-content{
    padding:15px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.category-badge{
    display:inline-block;
    background:#eef4ff;
    color:#2563eb;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
    margin-bottom:8px;
}

.product-name{
    font-size:20px;
    font-weight:700;
    color:#111827;
    line-height:1.3;
    min-height:48px;
    transition: .3s;
    margin-bottom:8px;
}

.product-link{
    text-decoration: none;
}

.product-link .product-name{
    color: #111827;
    transition: .3s;
}

.product-link:hover .product-name{
    color: #2563eb;
}

.product-desc{
    font-size:13px;
    color:#64748b;
    line-height:1.5;
    height:55px;
    overflow:hidden;
}

.product-footer{
    margin-top:auto;
    padding-top:14px;
    border-top:1px solid #eee;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.price{
    font-size:24px;
    font-weight:800;
    color:#2563eb;
}

.view-btn{
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
}

.view-btn:hover{
    background:#1d4ed8;
}

.empty-state{
    grid-column:1/-1;
    background:#fff;
    border-radius:18px;
    padding:70px 30px;
    text-align:center;
    border:1px solid #e5e7eb;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.empty-icon{
    width:90px;
    height:90px;
    margin:0 auto 20px;
    border-radius:50%;
    background:#fdf2f8;
    color:#ec4899;
    font-size:42px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.empty-state h3{
    font-size:28px;
    color:#111827;
    margin-bottom:12px;
    font-weight:700;
}

.empty-state p{
    max-width:500px;
    margin:0 auto 28px;
    color:#6b7280;
    font-size:15px;
    line-height:1.7;
}

.browse-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    padding:12px 28px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    transition:.3s;
}

.browse-btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

@media(max-width:992px){
    .products-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:768px){
    .products-grid{
        grid-template-columns:1fr;
    }
}
</style>

<div class="wishlist-page">

    <div class="wishlist-hero">
        <h1>My Wishlist</h1>
        <p>Your saved products. Ready when you are.</p>
    </div>

    <div class="products-grid" id="productsGrid">
        @forelse($wishlists as $wishlist)
            @if($wishlist->product)
            <article class="product-card" id="wishlist-item-{{ $wishlist->product->id }}">
                <div class="product-img-box">
                    <button class="wishlist-toggle active" data-id="{{ $wishlist->product->id }}">
                        ❤️
                    </button>
                    @if($wishlist->product->image)
                        <a href="{{ route('product.detail', $wishlist->product->slug) }}">
                            <img src="{{ asset('product/'.$wishlist->product->image) }}"
                                alt="{{ $wishlist->product->name }}"
                                class="product-img">
                        </a>
                    @else
                        <img src="https://via.placeholder.com/400x300?text=No+Image" alt="No Image" class="product-img">
                    @endif
                </div>

                <div class="product-content">
                    <span class="category-badge">
                        {{ $wishlist->product->category->name ?? 'Uncategorized' }}
                    </span>
                    <a href="{{ route('product.detail', $wishlist->product->slug) }}" class="product-link">
                        <h2 class="product-name">{{ $wishlist->product->name }}</h2>
                    </a>

                    <p class="product-desc">
                        {{ Str::limit($wishlist->product->description, 90) ?: 'No description available for this product yet.' }}
                    </p>

                   <div class="product-footer">
                    <div class="price">₹{{ number_format($wishlist->product->price, 2) }}</div>
                        <div style="display:flex; gap:8px;">
                            <form action="{{ route('cart.move') }}" method="POST">
                                @csrf
                                <input type="hidden" name="wishlist_id" value="{{ $wishlist->id }}">
                                <button type="submit" class="view-btn" style="background:#10b981; width: 90px">
                                    Move to Cart
                                </button>
                            </form>

                            <a href="{{ route('product.detail', $wishlist->product->slug) }}" class="view-btn" style="width: 90px">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            @endif
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    ❤️
                </div>

                <h3>Your Wishlist is Empty</h3>

                <p>
                    Looks like you haven't added any products to your wishlist yet.
                    Explore our collection and save your favorites!
                </p>

                <a href="{{ route('products') }}" class="browse-btn">
                    🛍️ Browse Products
                </a>
            </div>
        @endforelse
    </div>

</div>

<script>
// Optionally we can add a listener specifically for this page to remove the card from the DOM when toggled off
document.addEventListener('click', function(e){
    let btn = e.target.closest('.wishlist-toggle');
    if(!btn) return;
    
    let productId = btn.dataset.id;
    let card = document.getElementById('wishlist-item-' + productId);
    
    if(card) {
        // Wait a small delay so the ajax request fires first
        setTimeout(() => {
            if(!btn.classList.contains('active')){
                card.style.display = 'none';
            }
        }, 100);
    }
});
</script>

@endsection
