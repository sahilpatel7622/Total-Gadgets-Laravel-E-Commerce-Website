@extends('layouts.user')

@section('title', 'View Members')

@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f7fb;
            min-height: 100vh;
        }

        .user-box{
            display:flex;
            align-items:center;
            gap:20px;
        }

        .user-box h2{
            color:#fff;
            font-size:20px;
            margin:0;
        }

        .logout-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            background: #ff4d4d;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #e60000;
        }

        .container {
            width: 95%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background: #5b86e5;
            color: white;
            padding: 14px;
            text-align: center;
            font-size: 15px;
        }

        td {
            padding: 13px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
            color: #333;
            font-size: 14px;
        }

        tr:hover {
            background: #f1f6ff;
        }

        img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #5b86e5;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .error ul {
            list-style: none;
        }

        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            margin: 2px;
        }

        .edit-btn {
            background: #28a745;
        }

        .edit-btn:hover {
            background: #218838;
        }

        .delete-btn {
            background: #ff4d4d;
        }

        .delete-btn:hover {
            background: #e60000;
        }
    </style>

    <a href="{{ route('insert.form') }}"
        style="background:#0d6efd;color:white;padding:10px 16px;border-radius:6px;text-decoration:none;">
        Add Member
    </a>

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

    <h1>Member List</h1>

    <table>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Number</th>
            <th>Address Line-1</th>
            <th>City</th>
            <th>State</th>
            <th>Country</th>
            <th>Gender</th>
            <th>Image</th>
            <th>Action</th>
        </tr>

        @php
            $no = 1;
        @endphp

        @foreach($record as $r)
            @php
                $currentLocation = \App\Models\location_mapping::where('data_id', $r->id)->first();
            @endphp

            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $r->name }}</td>
                <td>{{ $r->email }}</td>
                <td>{{ $r->number }}</td>
                <td>{{ $r->address }}</td>
                <td>{{ $currentLocation?->city?->name ?? 'N/A' }}</td>
                <td>{{ $currentLocation?->state?->name ?? 'N/A' }}</td>
                <td>{{ $currentLocation?->country?->name ?? 'N/A' }}</td>
                <td>{{ $r->gender }}</td>

                <td>
                    <img src="{{ asset('uploads/'.$r->image) }}" alt="Member Image">
                </td>

                <td>
                    <a href="{{ route('edit',$r->id) }}" class="edit-btn">Update</a>
                    <a href="{{ route('delete',$r->id) }}" class="delete-btn">Delete</a>
                </td>
            </tr>
        @endforeach
    </table>


@endsection