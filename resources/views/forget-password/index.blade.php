<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            min-height:100vh;
            background:linear-gradient(135deg,#35c9c9,#5b7cf0);
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .forgot-box{
            width:380px;
            background:#fff;
            padding:35px;
            border-radius:15px;
            box-shadow:0 15px 35px rgba(0,0,0,0.15);
        }

        .forgot-box h2{
            text-align:center;
            margin-bottom:10px;
            font-size:30px;
            color:#222;
        }

        .forgot-box p{
            text-align:center;
            color:#666;
            margin-bottom:25px;
            font-size:15px;
        }

        .form-control{
            width:100%;
            height:46px;
            padding:0 15px;
            border:1px solid #ccc;
            border-radius:7px;
            font-size:15px;
            outline:none;
        }

        .form-control:focus{
            border-color:#5b7cf0;
        }

        .btn-send{
            width:100%;
            height:45px;
            background:#5b7cf0;
            border:none;
            color:white;
            border-radius:7px;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
            margin-top:18px;
        }

        .btn-send:hover{
            background:#4b68d8;
        }

        .back-login{
            text-align:center;
            margin-top:20px;
            font-size:15px;
        }

        .back-login a{
            color:#5b7cf0;
            text-decoration:none;
            font-weight:bold;
        }

        .alert{
            padding:10px;
            border-radius:6px;
            margin-bottom:15px;
            font-size:14px;
        }

        .alert-success{
            background:#d1fae5;
            color:#065f46;
        }

        .alert-danger{
            background:#fee2e2;
            color:#991b1b;
        }

        .invalid-feedback{
            color:red;
            font-size:13px;
            margin-top:5px;
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
    </style>
</head>
<body>

<div id="loader">
    <div class="spinner"></div>
</div>

<div class="forgot-box">

    <h2>Forgot Password</h2>
    <p>Enter your email to receive OTP.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form id="forgotForm" action="{{ route('send.otp') }}" method="POST">
        @csrf

        <input
            type="email"
            name="email"
            class="form-control"
            placeholder="Enter Email"
            value="{{ old('email') }}"
        >

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <button type="submit" class="btn-send">
            Send OTP
        </button>
    </form>

    <div class="back-login">
        Remember password?
        <a href="{{ url('/login') }}">Login</a>
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


</html>