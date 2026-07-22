<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

        .reset-box{
            width:390px;
            background:#fff;
            border-radius:15px;
            padding:35px;
            box-shadow:0 15px 35px rgba(0,0,0,.15);
        }

        .reset-box h2{
            text-align:center;
            font-size:32px;
            color:#222;
            margin-bottom:10px;
        }

        .reset-box p{
            text-align:center;
            color:#666;
            margin-bottom:25px;
            font-size:15px;
        }

        .input-group{
            margin-bottom:6px;
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

        .btn-reset{
            width:100%;
            height:46px;
            border:none;
            border-radius:8px;
            background:#5b7cf0;
            color:#fff;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        .btn-reset:hover{
            background:#4967df;
        }

        .invalid-feedback{
            color:red;
            font-size:13px;
            margin-top:5px;
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

        .password-box{
            position:relative;
        }

        .password-box .form-control{
            padding-right:45px;
        }

        .toggle-password{
            position:absolute;
            right:15px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:#666;
            font-size:18px;
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

<div class="reset-box">

    <h2>Reset Password</h2>
    <p>Create a new password for your account.</p>



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

    <form id="forgotForm" action="{{ route('reset.password') }}" method="POST">
        @csrf

        <div class="input-group password-box">
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                maxlength="15"
                placeholder="Enter New Password"
            >

            <i class="fa-solid fa-eye toggle-password"
            id="togglePassword"
            onclick="togglePassword('password','togglePassword')"></i>
        </div>

        @error('password')
            @if($message != 'Confirm password does not match.')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @endif
        @enderror<br>

        <div class="input-group password-box">
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                maxlength="15"
                placeholder="Confirm Password"
            >

            <i class="fa-solid fa-eye toggle-password"
            id="toggleConfirmPassword"
            onclick="togglePassword('password_confirmation','toggleConfirmPassword')"></i>
        </div>

        @if($errors->has('password') && $errors->first('password') == 'Confirm password does not match.')
            <div class="invalid-feedback">
                {{ $errors->first('password') }}
            </div>
        @endif<br>

        <button type="submit" class="btn-reset" id="submitBtn">
            Update Password
        </button>
    </form>

    @if(!Auth::check())
    <div class="back-login">
        <a href="{{ url('/login') }}">Back to Login</a>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

<script>
function togglePassword(inputId, iconId)
{
    let input = document.getElementById(inputId);
    let icon = document.getElementById(iconId);

    if(input.type === "password"){
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }else{
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
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

</html>