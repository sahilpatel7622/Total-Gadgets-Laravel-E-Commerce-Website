@extends('layouts.user')

@section('title','Contact Us')

@section('content')

<style>
.contact-section{
    max-width:1200px;
    margin:10px auto;
    padding:0 15px;
}

.contact-banner{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    color:#fff;
    padding:60px 40px;
    text-align:center;
    border-radius:20px;
    margin-bottom:40px;
}

.contact-banner h1{
    font-size:42px;
    font-weight:800;
    margin-bottom:10px;
}

.contact-banner p{
    font-size:17px;
    opacity:.9;
}

.contact-wrapper{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
}

.contact-card{
    background:#fff;
    border-radius:18px;
    padding:30px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.contact-card h3{
    font-size:26px;
    margin-bottom:20px;
    color:#222;
    font-weight:800;
}

.info-box{
    display:flex;
    gap:14px;
    margin-bottom:22px;
    padding:16px;
    background:#f8fafc;
    border-radius:14px;
    border:1px solid #e5e7eb;
}

.info-icon{
    width:42px;
    height:42px;
    border-radius:12px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.info-box h5{
    margin-bottom:5px;
    color:#2563eb;
    font-weight:800;
}

.info-box p{
    color:#666;
    margin:0;
    line-height:1.6;
}

@media(max-width:768px){
    .contact-wrapper{
        grid-template-columns:1fr;
    }

    .contact-banner{
        padding:45px 25px;
    }

    .contact-banner h1{
        font-size:32px;
    }
}
</style>

<div class="contact-section">

    <div class="contact-banner">
        <h1>Contact Us</h1>
        <p>We're here to help. Get in touch with Total Gadgets anytime.</p>
    </div>

    <div class="contact-wrapper" style="position: relative; top: 20px;">

        <div class="contact-card">
            <h3>Our Location</h3>

            <iframe
                src="https://www.google.com/maps?q=Ahmedabad,Gujarat,India&output=embed"
                width="100%"
                height="310"
                style="border:0;border-radius:15px;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>

        <!-- Right Side : Contact Information -->
        <div class="contact-card">

            <h3>Get In Touch</h3>

            <div class="info-box">
                <div class="info-icon">📍</div>
                <div>
                    <h5>Address</h5>
                    <p>
                        Total Gadgets<br>
                        Ahmedabad, Gujarat, India
                    </p>
                </div>
            </div>

            <div class="info-box">
            <div class="info-icon">📞</div>
                <div>
                    <h5>Phone</h5>
                    <p>+91 9876543210</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">📧</div>
                <div>
                    <h5>Email</h5>
                    <p>support@totalgadgets.com</p>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection