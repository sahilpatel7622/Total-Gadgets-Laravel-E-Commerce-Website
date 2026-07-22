@extends('layouts.admin')

@section('title', 'Category')

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
        color: '#333'
    });
});
</script>
@endif

<style>
    .switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #ccc;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background: #fff;
    transition: .4s;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:checked + .slider:before {
    transform: translateX(24px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.deleted-row > td {
    background-color: #c5bbc5 !important;
    color: #ffffff !important;
}

.deleted-row .slider {
    background-color: #9ca3af !important;
    cursor: not-allowed;
}

.switch input:disabled + .slider {
    cursor: not-allowed;
    opacity: 0.55;
}
</style>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Category Management</h3>
            <small class="text-muted">Dashboard / Category</small>
        </div>

        <a href="{{ url('/admin/category/add_category') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Category
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-layer-group"></i> Category List ({{ $record->total() }})
            </h5>

            <form action="{{ url('/admin/category') }}" method="GET" class="d-flex align-items-center" style="gap: 20px;">
                <div class="d-flex align-items-center gap-3">
                    <select name="per_page" class="form-select" style="width: 80px;">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>

                    <a href="{{ route('admin.category.export') }}" class="btn btn-success text-nowrap">
                        Export Excel
                    </a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search ID, Name or Status..."
                        value="{{ request('search') }}"
                        style="width:250px;">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-search"></i>
                    </button>

                    @if(request('search'))
                    <a href="{{ url('/admin/category') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
            
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="70">Id</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th width="120" class="text-center">Status</th>
                            <th width="170" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($record as $r)
                        <tr class="{{ $r->trashed() ? 'deleted-row' : '' }}">
                            <td style="color:green">#{{$r->id}}</td>
                            <td>{{$r->name}}</td>
                            <td>{{$r->slug}}</td>
                            <td class="text-center">
                                <label class="switch">
                                    <input type="checkbox"
                                        {{ $r->status == 1 ? 'checked' : '' }}
                                        {{ $r->trashed() ? 'disabled' : '' }}
                                        @if(!$r->trashed()) onchange="window.location='{{ route('category_status', $r->id) }}'" @endif>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                @if($r->trashed())
                                    <button type="button"
                                            class="btn btn-success btn-sm"
                                            onclick="confirmRestore('{{ route('restore_category', $r->id) }}', '{{ $r->name }}')">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                @else
                                    <a href="{{ route('edit_category',$r->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ Route('delete_category', $r->id) }}', '{{ $r->name }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No Categories Found
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
                    Showing {{ $record->firstItem() }} to {{ $record->lastItem() }} of {{ $record->total() }} categories
                </small>
                {{ $record->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </div>

</div>

<script>
function confirmDelete(url, name) {
    Swal.fire({
        title: 'Delete Category?',
        html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">The category will be soft-deleted and can be restored later.</small>`,
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
        title: 'Restore Category?',
        html: `Are you sure you want to restore <strong>${name}</strong>?<br>
        <small class="text-muted">
            This category will be visible to users again.
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

@endsection