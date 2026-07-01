@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Edit Product</h3>
            <small class="text-muted">Dashboard / Product / Edit</small>
        </div>

        <a href="{{ url('/admin/product') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-box"></i> Edit Product Details
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('update_product', $record->id) }}" method="POST" enctype="multipart/form-data">
                @csrf


                <!-- Product Name -->
                <div class="mb-3">
                    <label class="form-label">
                        Product Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $record->name) }}"
                           placeholder="Enter Product Name">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label class="form-label">
                        Slug <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="slug"
                           class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $record->slug) }}"
                           placeholder="Enter Product Slug">

                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label">
                        Price <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $record->price) }}"
                           placeholder="Enter Product Price">

                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label">
                        Category <span class="text-danger">*</span>
                    </label>

                    <select name="c_id" class="form-select @error('c_id') is-invalid @enderror">

                        @foreach($category as $cat)

                            <option value="{{ $cat->id }}"
                                {{ old('c_id', $record->c_id) == $cat->id ? 'selected' : '' }}>

                                {{ $cat->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('c_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Enter Product Description">{{ old('description', $record->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Current Image -->
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <br>

                    @if($record->image)
                        <img src="{{ asset('product/'.$record->image) }}"
                             width="100"
                             class="img-thumbnail">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </div>

                <!-- New Image -->
                <div class="mb-4">
                    <label class="form-label">Change Image</label>

                    <input type="file"
                           name="image"
                           class="form-control @error('image') is-invalid @enderror">

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Update Product
                    </button>

                    <a href="{{ url('/admin/product') }}" class="btn btn-light border">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection