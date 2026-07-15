@forelse($product as $p)

<article class="product-card">
    <div class="product-img-box">
        <button class="wishlist-toggle {{ in_array($p->id, $wishlistProductIds ?? []) ? 'active' : '' }}" data-id="{{ $p->id }}">
            {{ in_array($p->id, $wishlistProductIds ?? []) ? '❤️' : '🤍' }}
        </button>
        @if($p->image)
            <a href="{{ route('product.detail', $p->slug) }}">
                <img src="{{ asset('product/'.$p->image) }}"
                    alt="{{ $p->name }}"
                    class="product-image">
            </a>
        @else
            <img src="https://via.placeholder.com/400x300?text=No+Image" alt="No Image" class="product-image">
        @endif
    </div>

    <div class="product-content">
        <span class="category-badge">
            {{ $p->category->name ?? 'Uncategorized' }}
        </span>
        <a href="{{ route('product.detail', $p->slug) }}" class="product-link">
            <h2 class="product-name">{{ $p->name }}</h2>
        </a>

        <p class="product-desc">
            {{ Str::limit($p->description, 90) ?: 'No description available for this product yet.' }}
        </p>

        <div class="product-footer">
            <div class="price">₹{{ number_format($p->price, 2) }}</div>

            <a href="{{ route('product.detail', $p->slug) }}" class="view-btn">
                View Details
            </a>
        </div>
    </div>
</article>

@empty

<div class="empty-state">
    <div class="empty-icon">
        📦
    </div>

    <h3>No Products Found</h3>

    <p>
        We couldn't find any products matching your search or selected category.
        Try changing your filters or browse all products.
    </p>

    <a href="#" class="reset-btn" id="clearFilters">
        🔄 Clear Filters
    </a>
</div>

@endforelse