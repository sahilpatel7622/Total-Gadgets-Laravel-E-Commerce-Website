@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Edit Category</h3>
            <small class="text-muted">Dashboard / Category / Edit</small>
        </div>

        <a href="{{ url('/admin/category') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-pen"></i> Edit Category Details
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ url('/admin/category/update/'.$record->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $record->name) }}"
                           placeholder="Enter category name">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text"
                           name="slug"
                           class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $record->slug) }}"
                           placeholder="enter-category-slug">

                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
{{-- 
                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', $record->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $record->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select> --}}

                    {{-- @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror --}}
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Update Category
                    </button>

                    <a href="{{ url('/admin/category') }}" class="btn btn-light border">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection 