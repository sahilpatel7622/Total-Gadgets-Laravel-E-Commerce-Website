<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Exports\CouponsExport;
use App\Exports\DateWiseReportExport;
use App\Exports\OrdersExport;
use App\Exports\PaymentsExport;
use App\Exports\ProductsExport;
use App\Exports\UsersExport;
use App\Models\category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\product;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],

            'report_type' => [
                'nullable',
                'in:orders,payments,users,products,categories,coupons',
            ],

            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $reportType = $validated['report_type'] ?? null;

        $records = null;
        $headings = [];

        $totalRecords = 0;
        $totalAmount = 0;
        $totalDiscount = 0;
        $averageValue = 0;

        if ($reportType) {
            switch ($reportType) {
            case 'orders':
                $query = Order::query()
                    ->with('detail');

                $this->applyDateFilter(
                    $query,
                    $request->from_date,
                    $request->to_date
                );

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                $summaryQuery = clone $query;

                $totalRecords = (clone $summaryQuery)->count();
                $totalAmount = (clone $summaryQuery)->sum('amount');
                $totalDiscount = (clone $summaryQuery)
                    ->sum('coupon_discount');

                $records = $query
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();

                $headings = [
                    'ID',
                    'Order Number',
                    'Customer',
                    'Amount',
                    'Discount',
                    'Status',
                    'Order Date',
                ];

                break;
            case 'payments':
                $query = Payment::query()->with('order');

                $this->applyDateFilter(
                    $query,
                    $request->from_date,
                    $request->to_date
                );

                if ($request->filled('status')) {
                    $query->where('payment_status', $request->status);
                }

                $summaryQuery = clone $query;

                $totalRecords = (clone $summaryQuery)->count();
                $totalAmount = (clone $summaryQuery)->sum('amount');

                $records = $query
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();

                $headings = [
                    'ID',
                    'Order Number',
                    'Razorpay ID',
                    'Method',
                    'Amount',
                    'Status',
                    'Payment Date',
                ];

                break;
            case 'users':
                $query = User::query()->where('role', 'user');

                $this->applyDateFilter(
                    $query,
                    $request->from_date,
                    $request->to_date
                );

                if ($request->filled('status')) {
                    $userStatus = $request->status == '1' ? 'Active' : 'Inactive';
                    $query->where('status', $userStatus);
                }

                $totalRecords = (clone $query)->count();

                $records = $query
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();

                $headings = [
                    'ID',
                    'Name',
                    'Email',
                    'Number',
                    'Status',
                    'Registered Date',
                ];

                break;
            case 'products':
                $query = product::query()
                    ->with('category');

                $this->applyDateFilter(
                    $query,
                    $request->from_date,
                    $request->to_date
                );

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                $totalRecords = (clone $query)->count();

                $records = $query
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();

                $headings = [
                    'ID',
                    'Product',
                    'Category',
                    'Price',
                    'Status',
                    'Created Date',
                ];

                break;
            case 'categories':
                $query = category::query();

                $this->applyDateFilter(
                    $query,
                    $request->from_date,
                    $request->to_date
                );

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                $totalRecords = (clone $query)->count();

                $records = $query
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();

                $headings = [
                    'ID',
                    'Category',
                    'Slug',
                    'Status',
                    'Created Date',
                ];

                break;
            case 'coupons':
                $query = Coupon::query();

                $this->applyDateFilter(
                    $query,
                    $request->from_date,
                    $request->to_date
                );

                if ($request->filled('status')) {
                    if ($request->status == '1') {
                        $query->where('status', '1')
                              ->where(function($q) {
                                  $q->whereNull('end_date')
                                    ->orWhere('end_date', '>=', now());
                              });
                    } else {
                        $query->where(function($q) {
                            $q->where('status', '0')
                              ->orWhere('end_date', '<', now());
                        });
                    }
                }

                $summaryQuery = clone $query;

                $totalRecords = (clone $summaryQuery)->count();

                $totalDiscount = (clone $summaryQuery)->sum('discount_value');

                $records = $query
                    ->latest()
                    ->paginate(10)
                    ->withQueryString();

                $headings = [
                    'ID',
                    'Coupon Code',
                    'Type',
                    'Discount',
                    'Minimum Order',
                    'Status',
                    'Created Date',
                ];

                break;

            default:
                abort(404);
            }
        }

        if ($totalRecords > 0 && $totalAmount > 0) {
            $averageValue = $totalAmount / $totalRecords;
        }

        return view('admin.reports.index', compact(
            'records',
            'headings',
            'reportType',
            'totalRecords',
            'totalAmount',
            'totalDiscount',
            'averageValue'
        ));
    }

    public function dateWiseExport(Request $request)
    {
        $validated = $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],

            'report_type' => [
                'required',
                'in:orders,payments,users,products,categories,coupons',
            ],

            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $reportType = $validated['report_type'];

        $fileName = $reportType
            . '-report-'
            . now()->format('Y-m-d-H-i-s')
            . '.xlsx';

        return Excel::download(
            new DateWiseReportExport(
                reportType: $reportType,
                fromDate: $validated['from_date'] ?? null,
                toDate: $validated['to_date'] ?? null,
                status: $validated['status'] ?? null
            ),
            $fileName
        );
    }

    private function applyDateFilter(
        Builder $query,
        ?string $fromDate,
        ?string $toDate
    ): void {
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }
    }

    public function ordersExport()
    {
        return Excel::download(
            new OrdersExport(),
            'orders-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function paymentsExport()
    {
        return Excel::download(
            new PaymentsExport(),
            'payments-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function couponsExport()
    {
        return Excel::download(
            new CouponsExport(),
            'coupons-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function usersExport()
    {
        return Excel::download(
            new UsersExport(),
            'users-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function productsExport()
    {
        return Excel::download(
            new ProductsExport(),
            'products-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function categoriesExport()
    {
        return Excel::download(
            new CategoriesExport(),
            'categories-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}