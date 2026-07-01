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

.panel{
    background:#fff;
    border-radius:22px;
    padding:28px;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
    border:1px solid #eef2f7;
}

.panel-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
    gap:12px;
}

.panel-header h2{
    font-size:22px;
    color:#111827;
}

.view-all{
    color:#0d6efd;
    text-decoration:none;
    font-size:14px;
    font-weight:700;
}

.view-all:hover{
    color:#0b5ed7;
}

.quick-actions{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.action-card{
    display:block;
    text-decoration:none;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:16px;
    padding:20px;
    transition:.2s;
}

.action-card:hover{
    background:#eff6ff;
    border-color:#bfdbfe;
    transform:translateY(-3px);
}

.action-card strong{
    display:block;
    color:#111827;
    font-size:16px;
    margin-bottom:6px;
}

.action-card span{
    color:#64748b;
    font-size:13px;
    line-height:1.5;
}

.account-list{
    list-style:none;
}

.account-list li{
    display:flex;
    justify-content:space-between;
    gap:12px;
    padding:14px 0;
    border-bottom:1px solid #f1f5f9;
    font-size:14px;
}

.account-list li:last-child{
    border-bottom:0;
    padding-bottom:0;
}

.account-list span{
    color:#64748b;
}

.account-list strong{
    color:#111827;
    text-align:right;
    word-break:break-word;
}

.status-active{
    color:#059669;
    background:#ecfdf5;
    padding:4px 10px;
    border-radius:999px;
    font-size:12px;
}

.products-preview{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.preview-card{
    text-decoration:none;
    color:inherit;
    border:1px solid #eef2f7;
    border-radius:16px;
    overflow:hidden;
    transition:.2s;
}

.preview-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 25px rgba(15,23,42,.08);
}

.preview-img{
    height:120px;
    background:linear-gradient(180deg,#f8fafc,#eef2ff);
    overflow:hidden;
}

.preview-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.preview-body{
    padding:14px;
}

.preview-body h3{
    font-size:15px;
    color:#111827;
    margin-bottom:6px;
}

.preview-body p{
    color:#0d6efd;
    font-weight:800;
    font-size:16px;
}

.empty-products{
    text-align:center;
    padding:30px 20px;
    color:#64748b;
    background:#f8fafc;
    border-radius:16px;
    border:1px dashed #dbeafe;
}

.empty-products a{
    display:inline-block;
    margin-top:14px;
    color:#0d6efd;
    font-weight:700;
    text-decoration:none;
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
    </div>

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

    <div class="dashboard-grid">
        <div class="panel">
            <div class="panel-header">
                <h2>Quick Actions</h2>
            </div>

            <div class="quick-actions">
                <a href="{{ route('products') }}" class="action-card">
                    <strong>Browse Products</strong>
                    <span>Explore all items by category and price.</span>
                </a>

                <a href="{{ route('products') }}?sort=newest" class="action-card">
                    <strong>New Arrivals</strong>
                    <span>See the latest products added to the store.</span>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
