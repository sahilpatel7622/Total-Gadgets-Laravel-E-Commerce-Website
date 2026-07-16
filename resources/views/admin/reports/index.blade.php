@extends('layouts.admin')

@section('title', 'Reports & Analytics')

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

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-0">Reports & Analytics</h3>
        <small class="text-muted">Dashboard / Reports</small>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('reports.index') }}">

                <div class="row g-3 align-items-end" style="position: relative;right: 10px;">

                    <div class="col-lg-2 col-md-6" style="width: 180px">
                        <label class="form-label">From Date</label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="form-control"
                        >

                        @error('from_date')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="col-lg-2 col-md-6" style="width: 180px">
                        <label class="form-label">To Date</label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                            class="form-control"
                        >

                        @error('to_date')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-6" style="width: 230px">
                        <label class="form-label">Report Type</label>

                        <select name="report_type" class="form-select" required>
                            <option value="" @selected(!$reportType) disabled>Select Report Type</option>
                            <option value="users"
                                @selected($reportType === 'users')>
                                Users Report
                            </option>

                            <option value="categories"
                                @selected($reportType === 'categories')>
                                Categories Report
                            </option>

                            <option value="products"
                                @selected($reportType === 'products')>
                                Products Report
                            </option>

                            <option value="orders"
                                @selected($reportType === 'orders')>
                                Orders Report
                            </option>

                            <option value="payments"
                                @selected($reportType === 'payments')>
                                Payments Report
                            </option>

                            <option value="coupons"
                                @selected($reportType === 'coupons')>
                                Coupons Report
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6" style="width: 150px">
                        <label class="form-label">Status</label>

                        <select name="status" class="form-select">
                            <option value="">All Status</option>

                            @if ($reportType === 'orders')
                                <option value="Pending"
                                    @selected(request('status') === 'Pending')>
                                    Pending
                                </option>

                                <option value="Processing"
                                    @selected(request('status') === 'Processing')>
                                    Processing
                                </option>

                                <option value="Shipped"
                                    @selected(request('status') === 'Shipped')>
                                    Shipped
                                </option>

                                <option value="Delivered"
                                    @selected(request('status') === 'Delivered')>
                                    Delivered
                                </option>

                                <option value="Cancelled"
                                    @selected(request('status') === 'Cancelled')>
                                    Cancelled
                                </option>
                            @else
                                <option value="1"
                                    @selected(request('status') === '1')>
                                    Active
                                </option>

                                <option value="0"
                                    @selected(request('status') === '0')>
                                    Inactive
                                </option>
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-12">
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary flex-fill">
                                <i class="fas fa-filter me-1"></i>
                                Apply
                            </button>

                            <a href="{{ route('reports.index') }}"
                               class="btn btn-secondary">
                                Reset
                            </a>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @if($reportType)
        {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Records</small>
                    <h3 class="mb-0">{{ $totalRecords }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Amount</small>
                    <h3 class="mb-0">
                        ₹{{ number_format($totalAmount, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Discount</small>
                    <h3 class="mb-0">
                        ₹{{ number_format($totalDiscount, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Average Value</small>
                    <h3 class="mb-0">
                        ₹{{ number_format($averageValue, 2) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        {{ ucfirst($reportType) }} Report ({{ $records->total() }})
                    </h5>

                    @if (request('from_date') || request('to_date'))
                        <small class="text-muted">
                            {{ request('from_date') ?: 'Beginning' }}
                            to
                            {{ request('to_date') ?: 'Today' }}
                        </small>
                    @endif
                </div>

                @if($records->total() > 0)
                    <a
                        href="{{ route(
                            'admin.reports.date-wise.export',
                            request()->query()
                        ) }}"
                        class="btn btn-success"
                    >
                        <i class="fas fa-file-excel me-1"></i>
                        Export Filtered Excel
                    </a>
                @endif

            </div>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        @foreach ($headings as $heading)
                            <th>{{ $heading }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>

                    @forelse ($records as $record)

                        @if ($reportType === 'orders')
                            <tr>
                                <td style="color: green">#{{ $record->id }}</td>
                                <td>{{ $record->order_number }}</td>
                                <td>
                                    {{ $record->detail?->name ?? '-' }}
                                </td>
                                <td>
                                    ₹{{ number_format($record->amount, 2) }}
                                </td>
                                <td>
                                    ₹{{ number_format(
                                        $record->coupon_discount ?? 0,
                                        2
                                    ) }}
                                </td>
                                <td>{{ $record->status }}</td>
                                <td>
                                    {{ $record->created_at?->format(
                                        'd M Y, h:i A'
                                    ) }}
                                </td>
                            </tr>

                        @elseif ($reportType === 'payments')
                            <tr>
                                <td style="color: green">#{{ $record->id }}</td>
                                <td>{{ $record->order?->order_number ?? '-' }}</td>
                                <td>{{ $record->razorpay_payment_id ?? '-' }}</td>
                                <td>
                                    {{ $record->payment_method
                                        ?? $record->method
                                        ?? '-' }}
                                </td>
                                <td>
                                    ₹{{ number_format(
                                        $record->amount ?? 0,
                                        2
                                    ) }}
                                </td>
                                <td>{{ $record->payment_status ?? '-' }}</td>
                                <td>
                                    {{ $record->created_at?->format(
                                        'd M Y, h:i A'
                                    ) }}
                                </td>
                            </tr>

                        @elseif ($reportType === 'users')
                            <tr>
                                <td style="color: green">#{{ $record->id }}</td>
                                <td>{{ $record->name }}</td>
                                <td>{{ $record->email }}</td>
                                <td>{{ $record->number ?? '-' }}</td>
                                <td>
                                    {{ $record->status }}
                                </td>
                                <td>
                                    {{ $record->created_at?->format(
                                        'd M Y, h:i A'
                                    ) }}
                                </td>
                            </tr>

                        @elseif ($reportType === 'products')
                            <tr>
                                <td style="color: green">#{{ $record->id }}</td>
                                <td>{{ $record->name }}</td>
                                <td>
                                    {{ $record->category?->name ?? '-' }}
                                </td>
                                <td>
                                    ₹{{ number_format($record->price, 2) }}
                                </td>
                                <td>
                                    {{ $record->status
                                        ? 'Active'
                                        : 'Inactive' }}
                                </td>
                                <td>
                                    {{ $record->created_at?->format(
                                        'd M Y, h:i A'
                                    ) }}
                                </td>
                            </tr>

                        @elseif ($reportType === 'categories')
                            <tr>
                                <td style="color: green">#{{ $record->id }}</td>
                                <td>{{ $record->name }}</td>
                                <td>{{ $record->slug }}</td>
                                <td>
                                    {{ $record->status
                                        ? 'Active'
                                        : 'Inactive' }}
                                </td>
                                <td>
                                    {{ $record->created_at?->format(
                                        'd M Y, h:i A'
                                    ) }}
                                </td>
                            </tr>

                        @elseif ($reportType === 'coupons')
                            <tr>
                                <td style="color: green">#{{ $record->id }}</td>
                                <td>
                                    {{ $record->code ?? '-' }}
                                </td>
                                <td>{{ ucfirst($record->type) }}</td>
                                <td>
                                    {{ $record->discount_value ?? 0 }}
                                </td>
                                <td>
                                    ₹{{ number_format(
                                        $record->minimum_order_amount ?? 0,
                                        2
                                    ) }}
                                </td>
                                <td>
                                    @if($record->end_date && now()->gt($record->end_date))
                                        Inactive
                                    @else
                                        {{ $record->status ? 'Active' : 'Inactive' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $record->created_at?->format(
                                        'd M Y, h:i A'
                                    ) }}
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="{{ count($headings) }}"
                                class="text-center py-4">
                                No records found for the selected filters.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            <div class="mt-3">
                {{ $records->links() }}
            </div>

        </div>
        </div>
    </div>
    @else
        <div class="alert alert-info text-center mt-4">
            <i class="fas fa-info-circle mb-2" style="font-size: 24px;"></i><br>
            Please select a <strong>Report Type</strong> and click <strong>Apply</strong> to view the data.
        </div>
    @endif

</div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.querySelector('select[name="report_type"]');
    const statusSelect = document.querySelector('select[name="status"]');
    
    const currentStatus = "{{ request('status') }}";
    const initialReportType = "{{ request('report_type', '') }}";

    const optionsMap = {
        'orders': [
            { value: 'Pending', text: 'Pending' },
            { value: 'Processing', text: 'Processing' },
            { value: 'Shipped', text: 'Shipped' },
            { value: 'Delivered', text: 'Delivered' },
            { value: 'Cancelled', text: 'Cancelled' }
        ],
        'payments': [
            { value: 'Pending', text: 'Pending' },
            { value: 'Paid', text: 'Paid' },
            { value: 'Failed', text: 'Failed' },
            { value: 'Refund', text: 'Refund' }
        ],
        'users': [
            { value: '1', text: 'Active' },
            { value: '0', text: 'Inactive' }
        ],
        'categories': [
            { value: '1', text: 'Active' },
            { value: '0', text: 'Inactive' }
        ],
        'products': [
            { value: '1', text: 'Active' },
            { value: '0', text: 'Inactive' }
        ],
        'coupons': [
            { value: '1', text: 'Active' },
            { value: '0', text: 'Inactive' }
        ]
    };

    function updateStatusOptions() {
        const type = reportTypeSelect.value;
        const options = optionsMap[type] || [];
        
        statusSelect.innerHTML = '<option value="">All Status</option>';
        
        options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.value;
            option.textContent = opt.text;
            
            if (opt.value === currentStatus && type === initialReportType) {
                option.selected = true;
            }
            
            statusSelect.appendChild(option);
        });
    }

    reportTypeSelect.addEventListener('change', updateStatusOptions);
    updateStatusOptions(); // Initialize on load
});
</script>
@endsection