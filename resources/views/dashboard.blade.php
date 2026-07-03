@extends('layouts.user')

@section('title','Dashboard')

@section('content')

<style>
.dashboard-page{
    max-width:1200px;
    margin:0 auto;
}

.dashboard-hero{
    background:linear-gradient(135deg,#0d6efd 0%,#6610f2 55%,#7c2cff 100%);
    color:#fff;
    padding:44px 48px;
    border-radius:24px;
    box-shadow:0 18px 45px rgba(13,110,253,.22);
    margin-bottom:28px;
    position:relative;
    overflow:hidden;
}

.dashboard-hero::after{
    content:'';
    position:absolute;
    right:-30px;
    top:-30px;
    width:180px;
    height:180px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
}

.dashboard-hero h1{
    font-size:38px;
    font-weight:800;
    margin-bottom:8px;
    position:relative;
    z-index:1;
}

.dashboard-hero p{
    font-size:17px;
    opacity:.92;
    position:relative;
    z-index:1;
}

.hero-badge{
    display:inline-block;
    margin-top:18px;
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.2);
    padding:8px 16px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
    position:relative;
    z-index:1;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:22px;
    margin-bottom:28px;
}

.stat-card{
    background:#fff;
    border-radius:20px;
    padding:26px;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
    border:1px solid #eef2f7;
    transition:.25s;
}

.stat-card:hover{
    transform:translateY(-5px);
    box-shadow:0 16px 35px rgba(15,23,42,.1);
}

.stat-icon{
    width:48px;
    height:48px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    margin-bottom:16px;
}

.stat-icon.blue{background:#eef5ff;}
.stat-icon.purple{background:#f3e8ff;}
.stat-icon.green{background:#ecfdf5;}

.stat-card h2{
    font-size:34px;
    font-weight:900;
    color:#111827;
    margin-bottom:6px;
}

.stat-card p{
    color:#64748b;
    font-size:15px;
    font-weight:600;
}

.dashboard-grid{
    display:grid;
    grid-template-columns:1.4fr 1fr;
    gap:24px;
    margin-bottom:28px;
}

.trending-section{
    background:#fff;
    border-radius:22px;
    padding:28px;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
    border:1px solid #eef2f7;
    margin-bottom:28px;
}

.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.section-header h2{
    font-size:24px;
    font-weight:900;
    color:#111827;
}

.section-header a{
    color:#0d6efd;
    font-size:14px;
    font-weight:700;
    text-decoration:none;
}

.trending-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.trending-card{
    text-decoration:none;
    color:inherit;
    border:1px solid #e5e7eb;
    border-radius:18px;
    overflow:hidden;
    background:#fff;
    transition:.25s;
}

.trending-card:hover{
    transform:translateY(-5px);
    box-shadow:0 14px 30px rgba(15,23,42,.10);
}

.trending-img{
    height:180px;
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
}

.trending-img img{
    width:100%;
    height:100%;
    object-fit:contain;
    padding:15px;
}

.trending-body{
    padding:16px;
}

.trending-body h3{
    font-size:16px;
    color:#111827;
    margin-bottom:8px;
}

.trending-body p{
    color:#0d6efd;
    font-size:17px;
    font-weight:900;
}

.empty-trending{
    grid-column:1 / -1;
    text-align:center;
    padding:30px;
    background:#f8fafc;
    border-radius:16px;
    color:#64748b;
}

@media(max-width:992px){
    .trending-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:640px){
    .trending-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:992px){
    .stats-grid{grid-template-columns:1fr;}
    .dashboard-grid{grid-template-columns:1fr;}
    .products-preview{grid-template-columns:1fr;}
}

@media(max-width:640px){
    .dashboard-hero{padding:32px 24px;}
    .dashboard-hero h1{font-size:30px;}
    .quick-actions{grid-template-columns:1fr;}
}
</style>

<div class="dashboard-page">

    <div class="dashboard-hero">
        <h1>Welcome, {{ Auth::check() ? Auth::user()->name : 'Guest' }}!</h1>

        <p>Your personal dashboard — browse products, track updates, and manage your account.</p>

        @if(Auth::check())
            <span class="hero-badge">
                Account status: {{ Auth::user()->status ?? 'Active' }}
            </span>
        @else
            <a href="{{ route('login') }}" class="hero-badge">Login to continue</a>
        @endif
    </div><br>

    <div class="trending-section">
        <div class="section-header">
            <h2>Trending Products</h2>
            <a href="{{ route('products') }}">View All</a>
        </div><br>

        <div class="trending-grid">
            @forelse($trendingProducts as $product)
                <a href="{{ route('product.detail', $product->slug) }}" class="trending-card">
                    <div class="trending-img">
                        <img src="{{ asset('product/'.$product->image) }}" alt="{{ $product->name }}">
                    </div>

                    <div class="trending-body">
                        <h3>{{ $product->name }}</h3>
                        <p>₹{{ number_format($product->price, 2) }}</p>
                    </div>
                </a>
            @empty
                <div class="empty-trending">
                    No trending products available.
                </div>
            @endforelse
        </div>
    </div><br>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">📦</div>
            <h2>{{ $totalProducts }}</h2>
            <p>Available Products</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">🏷️</div>
            <h2>{{ $totalCategories }}</h2>
            <p>Active Categories</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <h2>24/7</h2>
            <p>Customer Support</p>
        </div>
    </div>

</div>



@endsection
