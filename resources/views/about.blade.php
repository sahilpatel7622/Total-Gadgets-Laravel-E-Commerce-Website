@extends('layouts.user')

@section('title','About Us')

@section('content')

<style>

.about-page{
    max-width:1250px;
    margin:auto;
}

.hero{
       background:linear-gradient(135deg,#2563eb,#7c3aed);
    color:#fff;
    padding:70px 50px;
    border-radius:20px;
    text-align:center;
    margin-bottom:50px;
}

.hero h1{
    font-size:48px;
    margin-bottom:15px;
    font-weight:800;
}

.hero p{
    max-width:800px;
    margin:auto;
    font-size:18px;
    line-height:1.8;
}

.about-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
    align-items:center;
    margin-bottom:60px;
}

.about-image{
    background:#fff;
    border-radius:18px;
    padding:40px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    text-align:center;
}

.about-image img{
    width:100%;
    max-width:420px;
}

.about-content h2{
    font-size:36px;
    margin-bottom:20px;
}

.about-content p{
    color:#555;
    line-height:1.9;
    margin-bottom:15px;
}

.features{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
    margin-bottom:60px;
}

.feature-card{
    background:#fff;
    padding:30px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
}

.feature-card:hover{
    transform:translateY(-8px);
}

.feature-icon{
    font-size:45px;
    margin-bottom:15px;
}

.feature-card h3{
    margin-bottom:10px;
}

.feature-card p{
    color:#666;
    font-size:15px;
    line-height:1.7;
}

.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
}

.stat-card{
    background:#2563eb;
    color:#fff;
    border-radius:18px;
    padding:35px;
    text-align:center;
}

.stat-card h2{
    font-size:40px;
    margin-bottom:10px;
}

@media(max-width:900px){

.about-grid{
grid-template-columns:1fr;
}

.features,
.stats{
grid-template-columns:repeat(2,1fr);
}

}

@media(max-width:600px){

.features,
.stats{
grid-template-columns:1fr;
}

.hero h1{
font-size:36px;
}

}

</style>

<div class="about-page">

    <section class="hero">
        <h1>About Total Gadgets</h1>

        <p>
            Total Gadgets is your trusted online destination for the latest
            Mobile Phones, Smart TVs, Laptops, Accessories, and Electronics.
            We provide genuine products, affordable prices, and fast delivery
            with excellent customer support.
        </p>
    </section>

    <section class="about-grid">

        <div class="about-image">
            <img src="{{ asset('images/tg-logo.png') }}" alt="Total Gadgets">
        </div>

        <div class="about-content">

            <h2>Who We Are</h2>

            <p>
                Total Gadgets is committed to bringing the newest technology
                to every customer at competitive prices.
            </p>

            <p>
                Whether you are searching for a flagship smartphone,
                premium laptop, smart television, or accessories,
                we make shopping easy, secure, and enjoyable.
            </p>

            <p>
                Customer satisfaction is our highest priority and
                we continuously improve our services to deliver
                the best online shopping experience.
            </p>

        </div>

    </section>

    <section class="features">

        <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>Latest Products</h3>
            <p>Newest smartphones, laptops and smart TVs.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🚚</div>
            <h3>Fast Delivery</h3>
            <p>Quick and secure delivery across India.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Secure Shopping</h3>
            <p>100% genuine products with safe payment.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">💬</div>
            <h3>24/7 Support</h3>
            <p>Friendly customer support whenever you need.</p>
        </div>

    </section>

    <section class="stats">

        <div class="stat-card">
            <h2>10K+</h2>
            <p>Happy Customers</p>
        </div>

        <div class="stat-card">
            <h2>500+</h2>
            <p>Products</p>
        </div>

        <div class="stat-card">
            <h2>50+</h2>
            <p>Top Brands</p>
        </div>

        <div class="stat-card">
            <h2>99%</h2>
            <p>Customer Satisfaction</p>
        </div>

    </section>

</div>

@endsection