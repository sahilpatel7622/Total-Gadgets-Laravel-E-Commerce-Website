@extends('layouts.user')

@section('title','My Profile')

@section('content')

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session("success") }}',
    timer: 3000,
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    customClass: {
        popup: 'swal-popup',
        title: 'swal-title',
        confirmButton: 'swal-btn'
    }
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'info',
    title: 'No Changes',
    text: '{{ session("error") }}',
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif  

<style>
.profile-wrapper{
    max-width:1200px;
    margin:30px auto;
    display:grid;
    grid-template-columns:300px 1fr;
    gap:24px;
    gap:24px;
    align-items:start; 
}

.profile-sidebar{
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.profile-top{
    margin:20px;
    background:linear-gradient(135deg,#5b4df5,#4b3ee8);
    border-radius:12px;
    padding:30px 20px;
    text-align:center;
    color:white;
}

.profile-avatar{
    width:100px;
    height:100px;
    border-radius:50%;
    background:white;
    color:#5145e8;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:42px;
    font-weight:800;
    margin:0 auto 15px;
}
.side-link{
    display:flex;
    align-items:center;
    gap:14px;
    width:100%;
    padding:20px 28px;
    text-decoration:none;
    color:#667085;
    font-weight:700;
    border:0;
    border-left:4px solid transparent;
    background:#fff;
    cursor:pointer;
    font-size:16px;
    text-align:left;
}

.side-link:hover{
    background:#f8f8fb;
}

.side-link.active{
    background:#f8f8fb;
    color:#5145e8;
    border-left-color:#5145e8;
}

.side-link.logout{
    color:#ff3344;
}

.side-link.logout:hover{
    background:#fff1f2;
    color:#dc2626;
}

.profile-content{
    background:#fff;
    border-radius:14px;
    padding:40px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.profile-content h2{
    color:#5145e8;
    font-size:26px;
    margin-bottom:30px;
}

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
}

.form-group{
    margin-bottom:24px;
}

.form-group label{
    display:block;
    font-size:13px;
    font-weight:800;
    color:#6b7280;
    text-transform:uppercase;
    margin-bottom:10px;
}

.form-group input,
.form-group select{
    width:100%;
    height:50px;
    border:1px solid #dfe3ea;
    border-radius:8px;
    padding:0 16px;
    font-size:16px;
    outline:none;
    background:#fff;
}

.form-group input.readonly{
    background:#e9edf2;
    color:#333;
}

.save-btn{
    border:0;
    background:linear-gradient(135deg,#6c5ce7,#d946ef);
    color:white;
    padding:15px 32px;
    border-radius:30px;
    font-weight:800;
    font-size:15px;
    cursor:pointer;
}

.error-text{
    color:#dc2626;
    font-size:13px;
    margin-top:6px;
}

.swal-popup{
    border-radius:8px !important;
    padding:25px !important;
}

.swal-title{
    font-size:30px !important;
    font-weight:700 !important;
    color:#444 !important;
}

.swal2-html-container{
    font-size:20px !important;
    color:#555 !important;
}

.swal-btn{
    background:#6c5ce7 !important;
    border:none !important;
    border-radius:4px !important;
    font-size:16px !important;
    padding:10px 35px !important;
}

.swal2-success-ring{
    border-color:#cfe8bf !important;
}

.swal2-success-line-tip,
.swal2-success-line-long{
    background:#8bc34a !important;
}

#loader{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,.7);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.spinner{
    width:60px;
    height:60px;
    border:6px solid #ddd;
    border-top:6px solid #5b7cf0;
    border-radius:50%;
    animation:spin .8s linear infinite;
}

@keyframes spin{
    100%{
        transform:rotate(360deg);
    }
}

@media(max-width:900px){
    .profile-wrapper{
        grid-template-columns:1fr;
    }

    .form-row{
        grid-template-columns:1fr;
    }
}
</style>
<body>

<div id="loader">
    <div class="spinner"></div>
</div>

    <div class="profile-wrapper">

        <div class="profile-sidebar">

            <div class="profile-top">
                <div class="profile-avatar">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>

                <h3>{{ ucfirst(Auth::user()->name) }}</h3>
                <p>{{ Auth::user()->email }}</p>
            </div>

            <a href="{{ route('profile') }}" class="side-link active">
                👤 Edit Profile
            </a>

            <a href="{{ route('profile.security') }}" class="side-link">
                🛡️ Security
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="side-link logout">↪ Logout</button>
            </form>

        </div>

        <div class="profile-content">

            <h2>Personal Details</h2>

            <form id="forgotForm" action="{{ route('profile.updateProfile') }}" method="POST">
                @csrf

            <div class="form-group">
                <label>Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', Auth::user()->name) }}"
                    maxlength="30"
                    pattern="[A-Za-z ]+"
                    oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
                >
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">

                <div class="form-group">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', Auth::user()->email) }}"
                    >
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="number"
                        value="{{ old('number', Auth::user()->number) }}"
                        maxlength="10"
                        pattern="[0-9]{10}"
                        inputmode="numeric"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                    >
                    @error('number')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

            </div>

                <button type="submit" class="save-btn" style="position: relative; top: 10px">
                    SAVE CHANGES
                </button>

            </form>

        </div>

    </div>
</body>

<script>
document.getElementById("forgotForm").addEventListener("submit", function () {
    document.getElementById("loader").style.display = "flex";
    document.getElementById("submitBtn").disabled = true;
    document.getElementById("submitBtn").innerHTML = "Sending...";
});
</script>

@endsection