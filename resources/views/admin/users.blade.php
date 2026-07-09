@extends('layouts.admin')

@section('title', 'Users')

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
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #ccc;
        border-radius: 24px;
    }

    .slider:before {
        content: "";
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .3s;
    }

    input:checked + .slider {
        background: #2196F3;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Users Management</h3>
            <small class="text-muted">Dashboard / Users</small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-users"></i> Users List
            </h5>

            <form action="{{ url('/admin/users') }}" method="GET" class="d-flex">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search Name, Email or Number..."
                    value="{{ request('search') }}"
                    style="width:250px;">

                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>        
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="70">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th width="120">Status</th>
                            <th width="170" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($record as $r)
                            <tr>
                                <td style="color:green">#{{ $r->id }}</td>
                                <td>{{ $r->name }}</td>
                                <td>{{ $r->email }}</td>
                                <td>{{ $r->number ?? 'N/A' }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox"
                                               class="status-toggle"
                                               data-id="{{ $r->id }}"
                                               {{ $r->status == 'Active' ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('user_delete', $r->id) }}"
                                       onclick="return confirm('Delete this user?')"
                                       class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No Users Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            {{-- Pagination --}}
            @if($record->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <small class="text-muted">
                    Showing {{ $record->firstItem() }} to {{ $record->lastItem() }} of {{ $record->total() }} users
                </small>
                {{ $record->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('.status-toggle').on('change', function () {
        let userId = $(this).data('id');
        window.location.href = "{{ url('/admin/user/status') }}/" + userId;
    });
});
</script>

@endsection