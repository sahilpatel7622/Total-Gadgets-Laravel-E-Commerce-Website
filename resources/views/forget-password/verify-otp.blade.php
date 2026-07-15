<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>

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
            background:linear-gradient(135deg,#35c9c9,#5b7cf0);
        }

        .otp-box{
            width:380px;
            background:#fff;
            border-radius:15px;
            padding:35px;
            box-shadow:0 15px 35px rgba(0,0,0,.15);
        }

        .otp-box h2{
            text-align:center;
            font-size:32px;
            color:#222;
            margin-bottom:10px;
        }

        .otp-box p{
            text-align:center;
            color:#666;
            margin-bottom:25px;
            font-size:15px;
        }

        .form-control{
            width:100%;
            height:46px;
            border:1px solid #d1d5db;
            border-radius:8px;
            padding:0 15px;
            font-size:15px;
            outline:none;
            transition:.3s;
        }

        .form-control:focus{
            border-color:#5b7cf0;
        }

        .btn-verify{
            width:100%;
            height:46px;
            border:none;
            border-radius:8px;
            background:#5b7cf0;
            color:#fff;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            margin-top:20px;
            transition:.3s;
        }

        .btn-verify:hover{
            background:#4967df;
        }

        .alert{
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
            font-size:14px;
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

        .back-link{
            text-align:center;
            margin-top:20px;
        }

        .back-link a{
            color:#5b7cf0;
            text-decoration:none;
            font-weight:bold;
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
                border:1px solid #10b981;
            }

            .alert-danger{
                background:#fee2e2;
                color:#991b1b;
                border:1px solid #ef4444;
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

<div class="otp-box">

    <h2>Verify OTP</h2>
    <p>Enter the 6-digit OTP sent to your email.</p>


    <form id="forgotForm" action="{{ route('verify.otp') }}" method="POST">
        @csrf

        <input
            type="text"
            name="otp"
            maxlength="6"
            class="form-control @error('otp') is-invalid @enderror"
            placeholder="Enter OTP"
            value="{{ old('otp') }}"
        >

        @error('otp')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <button type="submit" class="btn-verify">
            Verify OTP
        </button>

    </form>

    <div class="back-link">
        <a href="{{ route('forgot.password') }}">Back</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}",
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}",
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

</body>

<script>
document.getElementById("forgotForm").addEventListener("submit", function () {

    document.getElementById("loader").style.display = "flex";

    document.getElementById("submitBtn").disabled = true;
    document.getElementById("submitBtn").innerHTML = "Sending...";

});
</script>

</html>