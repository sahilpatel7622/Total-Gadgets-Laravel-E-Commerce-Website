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
</script>
@endif

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

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: "Please fix the errors below.",
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#333',
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
                                      id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $admin->name) }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Phone Number</label>
                            <input type="text"
                                   id="number"
                                   name="number"
                                   class="form-control @error('number') is-invalid @enderror"
                                   value="{{ old('number', $admin->number) }}"
                                   maxlength="10">
                            @error('number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Email Address</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $admin->email) }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div><br>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Update Profile
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
                            <div class="input-group">
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password" tabindex="-1">
                                    <i class="bi bi-eye" id="icon-current_password"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">New Password</label>
                            <div class="input-group">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password" tabindex="-1">
                                    <i class="bi bi-eye" id="icon-password"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation" tabindex="-1">
                                    <i class="bi bi-eye" id="icon-password_confirmation"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div><br>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-lock me-1"></i> Change Password
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

@section('script')
<script>
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var input    = document.getElementById(targetId);
            var icon     = document.getElementById('icon-' + targetId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
</script>
@endsection

@endsection