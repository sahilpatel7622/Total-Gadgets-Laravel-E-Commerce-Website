<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Member</title>

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
            padding: 30px 15px;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: #fff;
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
            margin-top: 15px;
            margin-bottom: 7px;
            font-weight: bold;
            color: #333;
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

        .current-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 3px solid #5b86e5;
            border-radius: 10px;
            margin-top: 8px;
        }

        .update-btn {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
            background: #5b86e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .update-btn:hover {
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

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #5b86e5;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="{{ url('crud/view') }}" class="back-link">← Back to View Data</a>

    <h1>Update Member</h1>

    <form action="{{ route('update',$record->id) }}" method="POST" enctype="multipart/form-data">
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
        <input type="text" name="name" value="{{ $record->name }}" placeholder="Enter name">

        <label>Email</label>
        <input type="email" name="email" value="{{ $record->email }}" placeholder="Enter email">

        <label>Number</label>
        <input type="text" name="number" id="number" value="{{ $record->number }}" placeholder="Enter 10 digit number">

        <label>Address Line-1</label>
        <textarea name="address" placeholder="Enter address">{{ $record->address }}</textarea>

        <label>Country</label>
        <select id="country" name="country_id">
            <option value="">Select Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ isset($mapping) && $mapping->countries_id == $country->id ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>

        <label>State</label>
        <select id="state" name="state_id">
            <option value="">Select State</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ isset($mapping) && $mapping->states_id == $state->id ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>

        <label>City</label>
        <select id="city" name="city_id">
            <option value="">Select City</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ isset($mapping) && $mapping->cities_id == $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                </option>
            @endforeach
        </select>

        <input type="hidden" name="location" id="location" value="{{ $record->location }}">

        <label>Gender</label>
        <select name="gender">
            <option value="">Select Gender</option>
            <option value="Male" {{ $record->gender=='Male'?'selected':'' }}>Male</option>
            <option value="Female" {{ $record->gender=='Female'?'selected':'' }}>Female</option>
            <option value="Other" {{ $record->gender=='Other'?'selected':'' }}>Other</option>
        </select>

        <label>Current Image</label>
        @if($record->image)
            <img src="{{ asset('uploads/'.$record->image) }}" class="current-img">
        @else
            <p>No image uploaded</p>
        @endif

        <label>Change Image</label>
        <input type="file" name="image">

        <button type="submit" class="update-btn">Update</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $('#country').change(function () {
        let countryId = $(this).val();

        $('#state').html('<option value="">Select State</option>');
        $('#city').html('<option value="">Select City</option>');

        if(countryId) {
            $.get('/dashboard/states/' + countryId, function (data) {
                $.each(data, function (key, value) {
                    $('#state').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            });
        }
    });

    $('#state').change(function () {
        let stateId = $(this).val();

        $('#city').html('<option value="">Select City</option>');

        if(stateId) {
            $.get('/dashboard/cities/' + stateId, function (data) {
                $.each(data, function (key, value) {
                    $('#city').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            });
        }
    });

    $('form').submit(function () {
        let country = $('#country option:selected').text().trim();
        let state = $('#state option:selected').text().trim();
        let city = $('#city option:selected').text().trim();

        $('#location').val(country + ', ' + state + ', ' + city);
    });

    $('#number').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });
</script>

</body>
</html>