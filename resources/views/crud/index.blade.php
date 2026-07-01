@extends('layouts.user')

@section('title', 'Insert Data')

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

        .user-box {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-box h2 {
            font-size: 20px;
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
            max-width: 750px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            margin-top: 15px;
            color: #333;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #5b86e5;
            box-shadow: 0 0 5px rgba(91,134,229,.4);
        }

        .submit-btn {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #5b86e5;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #4169e1;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .error ul {
            list-style: none;
        }
    </style>

@if(session('successe'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Congrats!',
        text: '{{ session("successe") }}',
        confirmButtonText: 'OK'
    });
</script>
@endif

    <a href="{{ route('view') }}"
        style="background:#0d6efd;color:white;padding:10px 16px;border-radius:6px;text-decoration:none;">
        View Member
    </a>

    <h1>Add New Member</h1>

    <form id="memberForm" action="{{ route('insert') }}" method="POST" enctype="multipart/form-data">
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

        <label>Name</label>
        <input type="text" value="{{ old('name') }}" name="name" placeholder="Enter name">

        <label>Email</label>
        <input type="email" value="{{ old('email') }}" name="email" placeholder="Enter email">

        <label>Number</label>
        <input type="text" value="{{ old('number') }}" name="number" id="number" placeholder="Enter 10 digit number">

        <label>Address Line-1</label>
        <textarea name="address" placeholder="Enter address">{{ old('address') }}</textarea>

        <label>Country</label>
        <select id="country" name="country_id">
            <option value="">Select Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>

        <label>State</label>
        <select id="state" name="state_id">
            <option value="">Select State</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>

        <label>City</label>
        <select id="city" name="city_id">
            <option value="">Select City</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                </option>
            @endforeach
        </select>

        <label>Gender</label>
        <select name="gender">
            <option value="">Select Gender</option>
            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>

        <label>Image</label>
        <input type="file" name="image">

        <button type="submit" class="submit-btn">Submit</button>
    </form>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#country').change(function(){
        let country_id = $(this).val();

        $('#state').html('<option value="">Select State</option>');
        $('#city').html('<option value="">Select City</option>');

        if(country_id == '') {
            return false;
        }

        $.ajax({
            url:'/crud/insert/states/' + country_id,
            type:'GET',
            success:function(data){
                $.each(data,function(key,value){
                    $('#state').append(
                        '<option value="'+value.id+'">'+value.name+'</option>'
                    );
                });
            }
        });
    });

    $('#state').change(function(){
        let state_id = $(this).val();

        $('#city').html('<option value="">Select City</option>');

        if(state_id == '') {
            return false;
        }

        $.ajax({
            url:'/crud/insert/cities/' + state_id,
            type:'GET',
            success:function(data){
                $.each(data,function(key,value){
                    $('#city').append(
                        '<option value="'+value.id+'">'+value.name+'</option>'
                    );
                });
            },
            error:function(xhr){
                console.log(xhr.responseText);
            }
        });
    });

    $('#memberForm').submit(function(e){
        let name = $('input[name="name"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let number = $('input[name="number"]').val().trim();
        let address = $('textarea[name="address"]').val().trim();
        let gender = $('select[name="gender"]').val();
        let image = $('input[name="image"]').val();
        let country = $('#country').val();
        let state = $('#state').val();
        let city = $('#city').val();

        if(name == ''){
            Swal.fire('Error','Name is required','error');
            return false;
        }

        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailPattern.test(email)){
            Swal.fire('Error','Enter valid email address','error');
            return false;
        }

        if(number == ''){
            Swal.fire('Error','Number is required','error');
            return false;
        }

        if(number.length != 10){
            Swal.fire('Error','Number must be 10 digits','error');
            return false;
        }

        if(address == ''){
            Swal.fire('Error','Address is required','error');
            return false;
        }

        if(country == ''){
            Swal.fire('Error','Please select country','error');
            return false;
        }

        if(state == ''){
            Swal.fire('Error','Please select state','error');
            return false;
        }

        if(city == ''){
            Swal.fire('Error','Please select city','error');
            return false;
        }

        if(gender == ''){
            Swal.fire('Error','Please select gender','error');
            return false;
        }

        if(image == ''){
            Swal.fire('Error','Please select image','error');
            return false;
        }
    });

    $(document).ready(function(){
        $('#number').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    });
</script>
@endsection