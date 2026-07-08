<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #eef2f7;
            color: #333;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #1e1e2f, #151522);
            color: #fff;
            padding: 22px;
            display: flex;
            overflow: hidden;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 5px 0 25px rgba(0,0,0,0.15);
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        .brand {
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 1px solid #34344a;
        }

        .brand a {
            color: #4caf50;
            text-decoration: none;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar a,
        .logout-link {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 15px;
            border-radius: 10px;
            color: #b8b8d4;
            text-decoration: none;
            font-size: 16px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: 0.25s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #4caf50;
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-footer {
            border-top: 1px solid #34344a;
            padding-top: 18px;
        }

        .logout-link {
            color: #ff6b6b;
        }

        .logout-link:hover {
            background: #ff4d4d;
            color: #fff;
            transform: translateX(4px);
        }

        .top-navbar{
            height:70px;
            background:#fff;
            padding:0 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0 4px 18px rgba(0,0,0,.06);
        }

        .top-navbar h2 {
            font-size: 22px;
            color: #222;
        }
        
        .admin-info{
            background:#f5f5f5;
            padding:8px 15px;
            border-radius:25px;
            font-size:16px;
        }

        .admin-info i {
            color: #4caf50;
            margin-right: 6px;
        }

        .container {
            padding: 35px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .card {
            background: #fff;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.07);
        }

        .card h3 {
            margin-bottom: 18px;
            color: #333;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            background: #fff;
            overflow: hidden;
            border-radius: 10px;
        }

        .data-table th {
            background: #4caf50;
            color: #fff;
            padding: 13px;
            text-align: left;
        }

        .data-table td {
            padding: 13px;
            border-bottom: 1px solid #eee;
        }

        .data-table tr:hover {
            background: #f6fff6;
        }

        .btn {
            padding: 8px 13px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-danger {
            background: #ff4d4d;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: #e60000;
        }

        .maintenance-btn{
            text-decoration:none;
            color:#fff;
            padding:8px 14px;
            font-size:13px;
            border-radius:8px;
            font-weight:600;
            transition:.3s;
        }

        .live-btn{
            background:#22c55e;
        }

        .live-btn:hover{
            background:#16a34a;
            color:#fff;
        }

        .maintenance-btn-danger{
            background:#ef4444;
        }

        .maintenance-btn-danger:hover{
            background:#dc2626;
            color:#fff;
        }

        .maintenance-on {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
        }

        .maintenance-off {
            background: linear-gradient(135deg, #22c55e, #15803d);
        }

    </style>
</head>

<body>

<div class="sidebar">
    <div>
        <div class="brand" style="position: relative; right: 20px;">
            <a href="{{ url('/admin/dashboard') }}" style="font-size: 18px">
                <i class="fa-solid fa-gauge"></i> Admin Panel
            </a>
        </div>

        <ul style="position: relative; right: 20px;">
            <li>
                <a href="{{ url('/admin/dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="{{ url('/admin/users') }}" class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Users
                </a>
            </li>
                <li>
                <a href="{{ url('/admin/category') }}" class="{{ Request::is('admin/category*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Category
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/product') }}" class="{{ Request::is('admin/product*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i> Products
                </a>
            </li>

            <li>
                <a href="{{ route('admin.orders') }}"class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping"></i> Orders                
                </a>
            </li>

            <li>
                <a href="{{ route('admin.payments') }}"class="{{ Request::is('admin/payment*') ? 'active' : '' }}">
                    <i class="fa-solid fa-credit-card"></i> Payments                
                </a>
            </li>

            {{--
            <li>
                <a href="{{ url('/admin/data') }}" class="{{ Request::is('admin/data') ? 'active' : '' }}">
                    <i class="fa-solid fa-database"></i> Data
                </a>
            </li>
            --}}
        </ul>
    </div>

    <div class="sidebar-footer" style="position: relative; right: 20px;">
        <ul>
            <li>
                <a href="{{ route('admin.profile') }}">
                    <i class="fa-solid fa-user-gear"></i> Profile Setting
                </a>
            </li>

            <li>
                <form action="{{ route('admin_logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<div class="main-content">

    @php
        $setting = \App\Models\MaintenanceModel::firstOrCreate(
            ['id' => 1],
            ['maintenance_mode' => 1]
        );
    @endphp

    <div class="top-navbar">
        <h2>@yield('title')</h2>

        <div class="d-flex align-items-center gap-2">

            @if($setting->maintenance_mode == 0)
                <a href="{{ route('maintenance') }}" class="maintenance-btn live-btn">
                    <i class="fa-solid fa-power-off"></i> Live
                </a>
            @else
                <a href="{{ route('maintenance') }}" class="maintenance-btn maintenance-btn-danger">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Maintenance
                </a>
            @endif

            <div class="admin-info">
                <i class="fa-solid fa-circle-user"></i>
                Welcome, <b>{{ ucfirst(auth('admin')->user()->name ?? 'Admin') }}</b>
            </div>

        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>
</div>

@yield('script')
</body>
</html>