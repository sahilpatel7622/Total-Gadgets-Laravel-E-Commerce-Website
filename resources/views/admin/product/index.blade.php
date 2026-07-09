@extends('layouts.admin')

@section('title', 'Products')

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

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Products Management</h3>
            <small class="text-muted">Dashboard / Product</small>
        </div>

        <a href="{{ url('/admin/product/add_product') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Product
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-box"></i> Products List
            </h5>

            <form action="{{ url('/admin/product') }}" method="GET" class="d-flex">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search Name and Price..."
                    value="{{ request('search') }}"
                    style="width:250px;">

                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fa-solid fa-search"></i>
                </button>

                @if(request('search'))
                <a href="{{ url('/admin/product') }}" class="btn btn-secondary ms-2">
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
                            <th width="60">Id</th>
                            <th width="50">C_Name</th>
                            <th width="80">Name</th>
                            <th width="70">Slug</th>
                            <th width="80">Price</th>
                            <th width="350">Description</th>
                            <th width="100">Image</th>
                            <th width="120" class="text-center">Action</th>
                        </tr>
                    </thead>
                        @forelse($product as $p)
                    <tbody>
                        <tr>
                            <td style="color:green">#{{ $p->id }}</td>
                               <td>{{ $p->category->name ?? 'No Category' }}</td>                            <td>{{ $p->name }}</td>
                            <td>{{ $p->slug }}</td>
                            <td>{{ $p->price }}</td>
                            <td>{{ $p->description }}</td>
                            <td>
                                @if($p->image)
                                    <img src="{{ asset('product/'.$p->image) }}"
                                        width="80"
                                        height="60">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('edit_product', $p->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <a href="{{ route('delete_product', $p->id) }}" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No Product Found
                            </td>
                        </tr>
                         @endforelse
                    </tbody>
                </table>

            </div>

            {{-- Pagination --}}
            @if($product->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <small class="text-muted">
                    Showing {{ $product->firstItem() }} to {{ $product->lastItem() }} of {{ $product->total() }} products
                </small>
                {{ $product->links('pagination::bootstrap-5') }}
            </div>
            @endif

        </div>
    </div>

</div>

@endsection