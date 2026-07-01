@extends('layouts.user')

@section('title', $product->name)

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('successe'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('successe') }}",
});
</script>
@endif

<style>
.detail-page{
    max-width:1200px;
    margin:0 auto;
}

.back-link{
    display:inline-flex;
    color:#2563eb;
    text-decoration:none;
    font-weight:700;
    margin-bottom:22px;
}

.detail-card{
    background:#fff;
    border-radius:24px;
    box-shadow:0 18px 45px rgba(15,23,42,.10);
    overflow:hidden;
}

.detail-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
}

.detail-image-wrap{
    background:linear-gradient(180deg,#f8fafc,#eef4ff);
    min-height:480px;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:35px;
}

.detail-image{
    width:100%;
    max-width:560px;
    height:390px;
    object-fit:contain;
}

.detail-info{
    padding:45px;
}

.detail-breadcrumb{
    font-size:14px;
    color:#64748b;
    margin-bottom:16px;
}

.detail-breadcrumb a{
    color:#2563eb;
    text-decoration:none;
}

.category-badge{
    display:inline-block;
    background:#eef4ff;
    color:#2563eb;
    padding:7px 16px;
    border-radius:30px;
    font-size:13px;
    font-weight:700;
}

.detail-title{
    font-size:36px;
    color:#111827;
    margin:18px 0 10px;
    font-weight:800;
}

.detail-price{
    font-size:36px;
    color:#2563eb;
    font-weight:900;
    margin-bottom:18px;
}

.detail-description{
    font-size:15px;
    line-height:1.8;
    color:#475569;
    margin-bottom:25px;
}

.detail-meta{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
    margin-bottom:28px;
}

.meta-item{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:14px;
    padding:14px;
}

.meta-item span{
    display:block;
    font-size:12px;
    color:#64748b;
    margin-bottom:4px;
    text-transform:uppercase;
}

.meta-item strong{
    font-size:15px;
    color:#111827;
}

.detail-actions{
    display:flex;
    gap:14px;
    flex-wrap:wrap;
}

.btn-cart,
.btn-buy,
.btn-outline-custom{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    text-decoration:none;
    padding:14px 22px;
    border-radius:12px;
    font-weight:800;
    border:0;
    cursor:pointer;
    font-size:15px;
}

.btn-cart{
    background:#ffedd5;
    color:#ea580c;
}

.btn-cart:hover{
    background:#fed7aa;
    color:#c2410c;
}

.btn-buy{
    background:#2563eb;
    color:#fff;
}

.btn-buy:hover{
    background:#1d4ed8;
    color:#fff;
}

.btn-outline-custom{
    background:#fff;
    color:#2563eb;
    border:1px solid #bfdbfe;
}

.btn-outline-custom:hover{
    background:#eff6ff;
}

.related-section{
    margin-top:42px;
}

.related-section h2{
    font-size:26px;
    margin-bottom:20px;
}

.related-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.related-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    text-decoration:none;
    color:inherit;
    box-shadow:0 10px 25px rgba(15,23,42,.08);
    transition:.3s;
}

.related-card:hover{
    transform:translateY(-6px);
}

.related-img-box{
    height:150px;
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:12px;
}

.related-img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.related-body{
    padding:15px;
}

.related-body h3{
    font-size:15px;
    margin-bottom:8px;
}

.related-body p{
    color:#2563eb;
    font-size:18px;
    font-weight:900;
}

@media(max-width:992px){
    .detail-grid{
        grid-template-columns:1fr;
    }

    .related-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .detail-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:640px){
    .detail-info{
        padding:28px 22px;
    }

    .detail-page{
        max-width:100%;
        padding:0;
    }

    .detail-card{
        border-radius:18px;
    }

    .detail-grid{
        grid-template-columns:1fr;
    }

    .detail-title{
        font-size:28px;
        line-height:1.2;
    }

    .detail-breadcrumb{
        font-size:13px;
        word-break:break-word;
    }

    .detail-price{
        font-size:30px;
    }

    .detail-meta{
        grid-template-columns:1fr;
    }

    .detail-price{
        font-size:28px;
    }


    .detail-actions{
        flex-direction:column;
    }

    .detail-actions form,
    .detail-actions a,
    .detail-actions button{
        width:100%;
    }


    .related-grid{
        grid-template-columns:1fr;
    }
}
</style>

<div class="detail-page">

    <a href="{{ route('products') }}" class="back-link">← Back to Products</a>

    <div class="detail-card">
        <div class="detail-grid">

            <div class="detail-image-wrap">
                @if($product->image)
                    <img src="{{ asset('product/'.$product->image) }}"
                         class="detail-image"
                         alt="{{ $product->name }}">
                @else
                    <div>No product image available</div>
                @endif
            </div>

            <div class="detail-info">

                <div class="detail-breadcrumb">
                    <a href="{{ route('products') }}">Products</a>
                    @if($product->category)
                        / <a href="{{ route('products') }}">
                            {{ $product->category->name }}
                        </a>
                    @endif
                    / {{ $product->name }}
                </div>

                @if($product->category)
                    <span class="category-badge">{{ $product->category->name }}</span>
                @endif

                <h1 class="detail-title">{{ $product->name }}</h1>

                <div class="detail-price">
                    ₹{{ number_format($product->price, 2) }}
                </div>

                <p class="detail-description">
                    {{ $product->description ?: 'Detailed description for this product will be available soon.' }}
                </p>

                <div class="detail-meta">
                    <div class="meta-item">
                        <span>Category</span>
                        <strong>{{ $product->category->name ?? 'Uncategorized' }}</strong>
                    </div>
                </div>

                <div class="detail-actions">

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cart">
                        🛒 Add to Cart
                    </button>
                </form>

                    <a href="{{ route('buy.now',$product->slug) }}" class="btn-buy">
                        ⚡ Buy Now
                    </a>

                    <a href="{{ route('products') }}" class="btn-outline-custom">
                        Continue Shopping
                    </a>

                </div>

            </div>
        </div>
    </div>

    @if($relatedProducts->count())
    <section class="related-section">
        <h2>Related Products</h2>

        <div class="related-grid">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.detail', $related->slug) }}" class="related-card">
                    <div class="related-img-box">
                        @if($related->image)
                            <img src="{{ asset('product/'.$related->image) }}"
                                 class="related-img"
                                 alt="{{ $related->name }}">
                        @endif
                    </div>

                    <div class="related-body">
                        <h3>{{ $related->name }}</h3>
                        <p>₹{{ number_format($related->price, 2) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

</div>

@endsection