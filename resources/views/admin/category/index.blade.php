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
                <i class="fa-solid fa-layer-group"></i> Category List
            </h5>

            <form action="{{ url('/admin/category') }}" method="GET" class="d-flex">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search ID, Name or Status..."
                    value="{{ request('search') }}">

                <button class="btn btn-primary ms-2">
                    <i class="fa-solid fa-search"></i>
                </button>
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
                            <th width="120">Status</th>
                            <th width="170" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($record as $r)
                        <tr>
                            <td style="color:green">#{{$r->id}}</td>
                            <td>{{$r->name}}</td>
                            <td>{{$r->slug}}</td>
                            <td class="text-center">
                                <label class="switch">
                                    <input type="checkbox"
                                        {{ $r->status == 1 ? 'checked' : '' }}
                                        onchange="window.location='{{ route('category_status', $r->id) }}'">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('edit_category',$r->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <a href="{{ Route('delete_category',$r->id)}}" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
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


@endsection