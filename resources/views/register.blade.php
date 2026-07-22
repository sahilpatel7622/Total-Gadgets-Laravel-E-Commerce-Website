<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .register-box {
            background: #fff;
            padding: 35px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .register-box h2 {
            text-align: center;
            color: #333;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .heading {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .register-box input {
            width: 100%;
            padding: 13px 15px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .register-box input:focus {
            border-color: #5b86e5;
            box-shadow: 0 0 5px rgba(91,134,229,.4);
        }

        .register-box button {
            width: 100%;
            padding: 13px;
            background: #5b86e5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 5px;
        }

        .register-box button:hover {
            background: #4169e1;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 12px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 12px;
        }

        .error ul {
            list-style: none;
            padding: 0;
        }

        .bottom-text {
            text-align: center;
            margin-top: 20px;
            color: #555;
            font-size: 14px;
        }

        .bottom-text a {
            color: #5b86e5;
            font-weight: bold;
            text-decoration: none;
            margin-left: 5px;
        }

        .bottom-text a:hover {
            text-decoration: underline;
        }
        .password-box{
            position: relative;
        }

        .password-box input{
            width:100%;
            padding-right:45px;
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
    </style>
</head>

<body>

<div class="register-box">
    <h2>Register</h2>
    <p class="heading">Create your account to continue</p>

    <form id="userregister" action="{{ route('register_store') }}" method="post">
        @csrf

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="text" name="name"  maxlength="25   " value="{{ old('name') }}" placeholder="Enter Name">

        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email">

        <input type="text" name="number" value="{{ old('number') }}" id="number" placeholder="Enter Number">

        <div class="password-box">
            <input
                type="password"
                id="password"
                name="password"
                maxlength="15"
                placeholder="Enter Password"
            >
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>
        <button type="submit" name="register">Register</button>

        <p class="bottom-text">
            Already registered?
            <a href="login">Login</a>
        </p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

</body>
</html>