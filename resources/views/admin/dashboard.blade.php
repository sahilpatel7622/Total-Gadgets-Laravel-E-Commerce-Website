@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Dashboard Overview</h3>
            <small class="text-muted">Dashboard / Home</small>
        </div>
    </div>

        @if(session('successe'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'success',
                title: 'Congrats!',
                text: '{{ session("successe") }}',
                confirmButtonText: 'OK'
            });
        });
        </script>
        @endif
</div>

@endsection