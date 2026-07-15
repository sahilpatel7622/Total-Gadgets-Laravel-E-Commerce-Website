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
        timer: 1500,
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

    .switch input:disabled + .slider {
        cursor: not-allowed;
        opacity: 0.55;
    }

    .deleted-row > td {
        background-color: #c5bbc5 !important;
        color: #ffffff !important;
    }

    .deleted-row .slider {
        background-color: #9ca3af !important;
    }

    .deleted-row td:first-child {
        color: #ffffff !important;
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

                @if(request('search'))
                <a href="{{ url('/admin/users') }}" class="btn btn-secondary ms-2">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
                @endif
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
                            <tr class="{{ $r->trashed() ? 'deleted-row' : '' }}">
                                <td style="color:green">#{{ $r->id }}</td>
                                <td>{{ $r->name }}</td>
                                <td>{{ $r->email }}</td>
                                <td>{{ $r->number ?? 'N/A' }}</td>
                                <td>
                                    <label class="switch">
                                       <input type="checkbox"
                                            class="status-toggle"
                                            data-id="{{ $r->id }}"
                                            {{ $r->status == 'Active' ? 'checked' : '' }}
                                            {{ $r->trashed() ? 'disabled' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    @if($r->trashed())
                                        <button type="button"
                                                class="btn btn-success btn-sm"
                                                onclick="confirmRestore('{{ route('restore_user', $r->id) }}', '{{ $r->name }}')">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    @else
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete('{{ route('user_delete', $r->id) }}', '{{ $r->name }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
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
function confirmDelete(url, name) {
    Swal.fire({
        title: 'Delete User?',
        html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-trash"></i> Yes, Delete',
        cancelButtonText: '<i class="fa-solid fa-xmark"></i> Cancel',
        reverseButtons: true,
        focusCancel: true,
        customClass: {  
            popup: 'shadow-lg rounded-4',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>

<script>
function confirmRestore(url, name) {
    Swal.fire({
        title: 'Restore User?',
        html: `Are you sure you want to restore <strong>${name}</strong>?<br>
        <small class="text-muted">
            This user will be able to login again.
        </small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText:
            '<i class="fa-solid fa-rotate-left"></i> Yes, Restore',
        cancelButtonText:
            '<i class="fa-solid fa-xmark"></i> Cancel',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'shadow-lg rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>

<script>
$(document).ready(function () {
    $('.status-toggle').on('change', function () {
        if ($(this).prop('disabled')) {
            return;
        }
        let userId = $(this).data('id');
        window.location.href =
            "{{ url('/admin/user/status') }}/" + userId;
    });
});
</script>

@endsection