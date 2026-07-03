@extends('layouts.admin')

@section('title','Profile Setting')

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

});
@endif
</script>

@if(session('info'))
<script>
document.addEventListener('DOMContentLoaded', function () {

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: "{{ session('info') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

});
</script>
@endif

<div class="container-fluid">

    <div class="row">

        <!-- Profile Information -->
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Profile Information</h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="fw-semibold">Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ $admin->name }}">
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email',$admin->email) }}">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div><br>

                        <button class="btn btn-success">
                            Update Profile
                        </button>

                    </form>

                </div>
            </div>

        </div>

        <!-- Change Password -->
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Change Password</h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="fw-semibold">Current Password</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror">

                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">New Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror">

                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary">
                            Change Password
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection