<!DOCTYPE html>
<html>
<head>
    <title>Verify Order OTP</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#36d1dc,#5b86e5);
        }

        .box{
            width:380px;
            background:#fff;
            padding:35px;
            border-radius:15px;
            box-shadow:0 15px 35px rgba(0,0,0,.15);
        }

        h2{
            text-align:center;
            margin-bottom:10px;
        }

        p{
            text-align:center;
            color:#666;
            margin-bottom:25px;
        }

        input{
            width:100%;
            height:48px;
            padding:0 15px;
            border:1px solid #ddd;
            border-radius:8px;
            font-size:16px;
            margin-bottom:15px;
        }

        button{
            width:100%;
            height:48px;
            border:none;
            border-radius:8px;
            background:#4f46e5;
            color:#fff;
            font-size:16px;
            cursor:pointer;
        }

        button:hover{
            background:#4338ca;
        }

        .error{
            color:red;
            font-size:13px;
            margin-bottom:10px;
        }

        .success{
            color:green;
            margin-bottom:10px;
            text-align:center;
        }
        .alert{
            padding:12px;
            border-radius:8px;
            margin-bottom:18px;
            font-size:14px;
            text-align:center;
            font-weight:500;
        }

        .alert-success{
            background:#d1fae5;
            color:#065f46;
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

<div class="box">
<div id="loader">
    <div class="spinner"></div>
</div>

    <h2>Order Verification</h2>

    <p>Enter the OTP sent to your email.</p>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <form id="forgotForm"  action="{{ route('order.otp.verify') }}" method="POST">

        @csrf

        <input
            type="text"
            name="otp"
            value="{{ old('otp') }}"
            maxlength="6"
            placeholder="Enter 6 Digit OTP"
            inputmode="numeric"
            oninput="this.value=this.value.replace(/[^0-9]/g,'');"
        >

        @error('otp')
            <div class="error">
                {{ $message }}
            </div>
        @enderror

        <button type="submit">
            Verify OTP
        </button>

    </form>

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