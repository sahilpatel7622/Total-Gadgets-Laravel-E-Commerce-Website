<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .register-box {
            width: 400px;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .register-box h1 {
            margin-bottom: 8px;
            color: #333;
        }

        .register-box p {
            color: #777;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 13px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #667eea;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5568d3;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .error ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error{
            background:#ffe5e5;
            color:#d10000;
            border:1px solid #ffb3b3;
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
            text-align:center;
            font-size:14px;
            font-weight:600;
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
    <h1 style="text-align: center">Admin Portal</h1>
    <p style="text-align: center">Sign in to manage your application</p>

    <form action="{{ route('admin_login_store') }}" method="post">
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

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>

        <div class="password-box">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter Password"
                required
            >

            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>
        <button type="submit" name="login">Login</button>
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