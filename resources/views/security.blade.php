@extends('layouts.user')

@section('title','Security Settings')

@section('content')

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session("success") }}',
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
}

.profile-sidebar,.security-card{
    background:#fff;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.profile-sidebar{
    overflow:hidden;
    height:fit-content;
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

.security-card{
    padding:40px;
}

.security-card h2{
    color:#5145e8;
    font-size:26px;
    margin-bottom:30px;
}

.form-group{
    margin-bottom:22px;
}

.form-group label{
    display:block;
    font-size:13px;
    font-weight:800;
    color:#6b7280;
    text-transform:uppercase;
    margin-bottom:10px;
}

.password-box{
    position:relative;
}

.password-box input{
    width:100%;
    height:50px;
    border:1px solid #dfe3ea;
    border-radius:8px;
    padding:0 48px 0 16px;
    font-size:16px;
    outline:none;
    background:#fff;
}

.password-box input:focus{
    border-color:#5145e8;
    box-shadow:0 0 0 3px rgba(81,69,232,.12);
}

.toggle-password{
    position:absolute;
    right:16px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
    user-select:none;
}

.forgot-link{
    display:inline-block;
    color:#0d6efd;
    margin-bottom:28px;
    font-weight:500;
}

.update-btn{
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

            <a href="{{ route('profile') }}" class="side-link">👤 Edit Profile</a>
            <a href="{{ route('profile.security') }}" class="side-link active">🛡️ Security</a>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="side-link logout">↪ Logout</button>
            </form>

        </div>

        <div class="security-card">

            <h2>Change Password</h2>

            <form id="forgotForm" action="{{ route('profile.updatePassword') }}" method="POST" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label>Current Password</label>
                    <div class="password-box">
                        <input type="password" id="current_password" name="current_password"
                            placeholder="Enter current password" inputmode="numeric"
                            autocomplete="new-password"
                            >
                        <span class="toggle-password" onclick="togglePassword('current_password', this)">👁</span>
                    </div>
                    @error('current_password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <div class="password-box">
                        <input type="password" id="password" name="password"
                            placeholder="Create new password" inputmode="numeric"
                            autocomplete="new-password"
                            >
                        <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
                    </div>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="password-box">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Confirm your new password" inputmode="numeric"
                            autocomplete="new-password"
                            >
                        <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">👁</span>
                    </div>
                </div>

                <a href="{{ route('forgot.password') }}" class="forgot-link">Forgot Password?</a>
                <br>

                <button type="submit" class="update-btn">UPDATE PASSWORD</button>
            </form>

        </div>

    </div>

</body>

<script>
function togglePassword(inputId, icon){
    const input = document.getElementById(inputId);

    if(input.type === 'password'){
        input.type = 'text';
        icon.textContent = '🙈';
    }else{
        input.type = 'password';
        icon.textContent = '👁';
    }
}
</script>

<script>
document.getElementById("forgotForm").addEventListener("submit", function () {
    document.getElementById("loader").style.display = "flex";
    document.getElementById("submitBtn").disabled = true;
    document.getElementById("submitBtn").innerHTML = "Sending...";
});
</script>

@endsection