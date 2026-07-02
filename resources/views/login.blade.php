<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#36d1dc,#5b86e5);
        }

        .register-box{
            width:380px;
            background:#fff;
            padding:35px;
            border-radius:15px;
            box-shadow:0 15px 35px rgba(0,0,0,.2);
        }

        .register-box h2{
            text-align:center;
            color:#333;
            margin-bottom:8px;
            font-size:30px;
        }

        .register-box p.heading{
            text-align:center;
            color:#777;
            margin-bottom:25px;
            font-size:15px;
        }

        .register-box input{
            width:100%;
            padding:13px 15px;
            margin-bottom:18px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
            transition:.3s;
        }

        .register-box input:focus{
            border-color:#5b86e5;
            outline:none;
            box-shadow:0 0 5px rgba(91,134,229,.4);
        }

        .register-box button{
            width:100%;
            padding:13px;
            border:none;
            border-radius:8px;
            background:#5b86e5;
            color:#fff;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
            transition:.3s;
        }

        .register-box button:hover{
            background:#4169e1;
        }

        .success{
            color:green;
            text-align:center;
            margin-bottom:15px;
        }

        .error{
            color:red;
            text-align:center;
            margin-bottom:15px;
        }

        .error ul{
            list-style:none;
            padding:0;
        }

        .bottom-text{
            text-align:center;
            margin-top:20px;
            color:#555;
        }

        .bottom-text a{
            color:#5b86e5;
            text-decoration:none;
            font-weight:bold;
        }

        .bottom-text a:hover{
            text-decoration:underline;
        }
        .password-box{
            position: relative;
        }

        .password-box input{
            width:100%;
            padding:13px 45px 13px 15px;
            border:1px solid #ddd;
            border-radius:8px;
            font-size:15px;
        }

        .toggle-password{
            position:absolute;
            right:15px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            font-size:18px;
            user-select:none;
        }

        .forgot-link{
            margin:-5px 0 18px;
            text-align:right;
        }

        .forgot-link a{
            color:#4f46e5;
            text-decoration:none;
            font-size:15px;
            font-weight:500;
            transition:.3s;
        }

        .forgot-link a:hover{
            text-decoration:underline;
            color:#3b35c7;
        }

    </style>

</head>
<body>

<div class="register-box">

    <h2>User Login</h2>
    <p class="heading">Welcome Back! Please login to continue.</p>

    <form action="{{ route('login_store') }}" method="post">
        @csrf

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Enter Email"
        >
        @error('email')
            <small style="color:red;display:block;margin:-12px 0 12px;">
                {{ $message }}
            </small>
        @enderror

        <div class="password-box">
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter Password"
            inputmode="numeric"
            {{-- oninput="this.value=this.value.replace(/[^0-9]/g,'');" --}}
        >
        @error('password')
            <small style="color:red;display:block;margin:-12px 0 12px;">
                {{ $message }}
            </small>
        @enderror
            
                <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>

        <div class="forgot-link">
            <a href="{{ route('forgot.password') }}">
                Forgot Password?
            </a>
        </div>

        <button type="submit">Login</button>

        <div class="bottom-text">
            Don't have an account?
            <a href="/register">Register</a>
        </div>

    </form>

</div>

</body>

<script>
function togglePassword() {
    const password = document.getElementById('password');

    if (password.type === 'password') {
        password.type = 'text';
    } else {
        password.type = 'password';
    }
}
</script>

</html>