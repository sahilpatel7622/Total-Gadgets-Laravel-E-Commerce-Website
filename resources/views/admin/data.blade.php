@extends('layouts.admin')

@section('title', 'Data')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Data Management</h3>
            <small class="text-muted">Dashboard / Data</small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-table"></i> Data List
            </h5>

            <input type="text" class="form-control w-25" placeholder="Search Data...">
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Address Line-1</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Gender</th>
                            <th>Image</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($record as $r)
                            <tr>
                                <td style="color:green">#{{ $r->user_id }}</td>
                                <td>{{ $r->name }}</td>
                                <td>{{ $r->email }}</td>
                                <td>{{ $r->number }}</td>
                                <td>{{ $r->address }}</td>

                                @php
                                    $currentLocation = \App\Models\location_mapping::where('user_id', $r->user_id)
                                        ->skip($loop->index)
                                        ->first();
                                @endphp

                                <td>{{ $currentLocation?->city?->name ?? 'N/A' }}</td>
                                <td>{{ $currentLocation?->state?->name ?? 'N/A' }}</td>
                                <td>{{ $currentLocation?->country?->name ?? 'N/A' }}</td>

                                <td>{{ $r->gender }}</td>

                                <td>
                                    @if($r->image)
                                        <img src="{{ asset('uploads/' . $r->image) }}"
                                             alt="User Image"
                                             class="rounded"
                                             style="width:50px;height:50px;object-fit:cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin_data_delete', $r->id) }}"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

@endsection