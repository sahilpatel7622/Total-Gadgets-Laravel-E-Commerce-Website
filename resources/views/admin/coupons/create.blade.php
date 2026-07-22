@extends('layouts.admin')

@section('title', 'Add Coupon')

@section('content')

<style>
    .coupon-form-card {
        border-radius: 15px;
    }

    .form-label {
        font-weight: 600;
    }

    .required {
        color: red;
    }

    .user-list-box {
        max-height: 260px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">Add Coupon</h3>
            <small class="text-muted">Dashboard / Coupons / Add</small>
        </div>

        <a href="{{ route('coupons.index') }}"
           class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>

    </div>

    <div class="card shadow-sm border-0 coupon-form-card">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fa-solid fa-ticket me-1"></i>
                Coupon Information
            </h5>
        </div>

        <div class="card-body">

            <form  id="couponForm" action="{{ route('coupons.store') }}"
                  method="POST">

                @csrf

                <div class="row">

                    {{-- Coupon Code --}}
                    <div class="col-md-6 mb-3">

                        <label for="couponCode" class="form-label">
                            Coupon Code
                            <span class="required">*</span>
                        </label>

                        <div class="input-group">
                            <input type="text"
                                name="code"
                                id="couponCode"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code') }}"
                                placeholder="Click Generate Code"
                                readonly>

                            <button type="button"
                                    class="btn btn-primary"
                                    id="generateCodeButton">

                                <i class="fa-solid fa-rotate"></i>
                                Generate
                            </button>
                        </div>

                        <div id="couponCodeError"
                            class="text-danger small mt-1 @if(!$errors->has('code')) d-none @endif">

                            @error('code')
                                {{ $message }}
                            @enderror
                        </div>

                    </div>

                    {{-- Coupon Type --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Coupon Type
                            <span class="required">*</span>
                        </label>

                        <select name="type"
                                id="couponType"
                                class="form-select @error('type') is-invalid @enderror">

                            <option value="">Select Coupon Type</option>

                            <option value="fixed"
                                {{ old('type') === 'fixed' ? 'selected' : '' }}>
                                Fixed Amount
                            </option>

                            <option value="percentage"
                                {{ old('type') === 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>

                        </select>

                        @error('type')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Discount Value --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Discount Value
                            <span class="required">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text"
                                  id="discountSymbol">
                                ₹
                            </span>

                            <input type="number"
                                   name="discount_value"
                                   class="form-control @error('discount_value') is-invalid @enderror"
                                   value="{{ old('discount_value') }}"
                                   min="0.01"
                                   step="0.01"
                                   placeholder="Enter discount value">

                        </div>

                        @error('discount_value')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Minimum Order --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Minimum Order Amount
                            <span class="required"></span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">₹</span>

                            <input type="number"
                                   name="minimum_order_amount"
                                   class="form-control @error('minimum_order_amount') is-invalid @enderror"
                                   value="{{ old('minimum_order_amount', 2000   ) }}"
                                   min="0"
                                   step="0.01"
                                   placeholder="Minimum order amount">

                        </div>

                        @error('minimum_order_amount')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- User Type --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Apply Coupon For
                            <span class="required"></span>
                        </label>

                        <select name="user_type"
                                id="userType"
                                class="form-select @error('user_type') is-invalid @enderror">

                            <option value="all"
                                {{ old('user_type', 'all') === 'all' ? 'selected' : '' }}>
                                All Users
                            </option>

                            <option value="selected"
                                {{ old('user_type') === 'selected' ? 'selected' : '' }}>
                                Selected Users
                            </option>

                        </select>

                        @error('user_type')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Usage Limit --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Overall Usage Limit
                        </label>

                        <input type="number"
                               name="usage_limit"
                               class="form-control @error('usage_limit') is-invalid @enderror"
                               value="{{ old('usage_limit') }}"
                               min="1"
                               placeholder="Blank = Unlimited">

                        @error('usage_limit')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Per User Limit --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Per User Limit
                            <span class="required"></span>
                        </label>

                        <input type="number"
                               name="per_user_limit"
                               class="form-control @error('per_user_limit') is-invalid @enderror"
                               value="{{ old('per_user_limit', 1) }}"
                               min="1">
                        
                        @error('per_user_limit')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Start Date
                            <span class="required">*</span>
                        </label>

                        <input type="date"
                               name="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date') }}"
                               min="{{ date('Y-m-d') }}">

                        @error('start_date')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- End Date --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            End Date
                            <span class="required">*</span>
                        </label>

                        <input type="date"
                               name="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date') }}"
                               min="{{ date('Y-m-d') }}">

                        @error('end_date')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Status
                            <span class="required"></span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="1"
                                {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status') == '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Selected Users --}}
                    <div class="col-12 mb-3"
                         id="selectedUsersBox"
                         style="display: none;">

                        <label class="form-label">
                            Select Users
                            <span class="required">*</span>
                        </label>

                        <div class="user-list-box">

                            @forelse($users as $user)

                                <div class="form-check mb-2">

                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="user_ids[]"
                                           value="{{ $user->id }}"
                                           id="user{{ $user->id }}"
                                           {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}>

                                    <label class="form-check-label"
                                           for="user{{ $user->id }}">
                                        <strong>{{ $user->name }}</strong>
                                        <span class="text-muted">
                                            - {{ $user->email }}
                                        </span>
                                    </label>

                                </div>

                            @empty

                                <div class="text-muted">
                                    No active users found.
                                </div>

                            @endforelse

                        </div>

                        @error('user_ids')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                </div>

                <div class="text-end mt-3">

                    <a href="{{ route('coupons.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        Save Coupon
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('couponForm');

    const couponCode = document.getElementById('couponCode');
    const couponError = document.getElementById('couponCodeError');

    const generateButton = document.getElementById('generateCodeButton');

    const userType = document.getElementById('userType');
    const selectedUsersBox = document.getElementById('selectedUsersBox');

    const couponType = document.getElementById('couponType');
    const discountSymbol = document.getElementById('discountSymbol');

    function toggleUsers() {
        selectedUsersBox.style.display =
            userType.value === 'selected' ? 'block' : 'none';
    }

    function changeDiscountSymbol() {
        discountSymbol.innerHTML =
            couponType.value === 'percentage' ? '%' : '₹';
    }

    userType.addEventListener('change', toggleUsers);
    couponType.addEventListener('change', changeDiscountSymbol);

    toggleUsers();
    changeDiscountSymbol();

    generateButton.addEventListener('click', function () {

        fetch("{{ route('coupons.generateCode') }}")
        .then(res => res.json())
        .then(data => {

            couponCode.value = data.code;

            couponCode.classList.remove('is-invalid');

            couponError.innerHTML = '';

            couponError.classList.add('d-none');

        });

    });

    form.addEventListener('submit', function(e){

        if(couponCode.value.trim() == ''){

            e.preventDefault();

            couponCode.classList.add('is-invalid');

            couponError.innerHTML =
                'Please click Generate button to generate coupon code.';

            couponError.classList.remove('d-none');

            return false;
        }

    });

});
</script>

@endsection