@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Add Product</h3>
            <small class="text-muted">Dashboard / Product / Add</small>
        </div>

        <a href="{{ url('/admin/product') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-box"></i> Product Details
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('store_product') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Product Name -->
                <div class="mb-3">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>

                    <input type="text"
                           name="name"
                           maxlength="30"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Enter product name"
                           value="{{ old('name') }}">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>

                    <input type="text"
                           name="slug"
                           maxlength="30"
                           class="form-control @error('slug') is-invalid @enderror"
                           placeholder="enter-product-slug"
                           value="{{ old('slug') }}">

                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label">Price <span class="text-danger">*</span></label>

                    <input type="text"
                           name="price"
                           maxlength="9"
                           class="form-control @error('price') is-invalid @enderror"
                           placeholder="Enter price"
                           value="{{ old('price') }}"
                            min="1"
                            step="1"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>

                    <select name="c_id" class="form-select @error('c_id') is-invalid @enderror">
                        <option value="">-- Select Category --</option>

                        @foreach($record as $r)
                            <option value="{{ $r->id }}"
                                {{ old('c_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('c_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label class="form-label">Product Image</label>

                    <input type="file"
                           name="image"
                           class="form-control @error('image') is-invalid @enderror">

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Enter product description">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Save Product
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