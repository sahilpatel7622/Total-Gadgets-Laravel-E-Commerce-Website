<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Coupon;
use App\Models\product;
use App\Models\category;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DateWiseReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private string $reportType,
        private ?string $fromDate = null,
        private ?string $toDate = null,
        private ?string $status = null
    ) {
    }

    public function collection(): Collection
    {
        $query = match ($this->reportType) {
            'payments' => Payment::query()->with('order'),
            'users' => User::query()->where('role', 'user'),
            'products' => product::with('category'),
            'categories' => category::query(),
            'coupons' => Coupon::query(),
            default => Order::with('detail'),
        };

        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        if (
            $this->status !== null &&
            in_array($this->reportType, [
                'orders',
                'payments',
                'users',
                'categories',
                'products',
                'coupons'
            ])
        ) {
            $column = $this->reportType === 'payments' ? 'payment_status' : 'status';
            
            if ($this->reportType === 'users') {
                $statusVal = $this->status == '1' ? 'Active' : 'Inactive';
                $query->where($column, $statusVal);
            } elseif ($this->reportType === 'coupons') {
                if ($this->status == '1') {
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
            } else {
                $query->where($column, $this->status);
            }
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return match ($this->reportType) {
            'payments' => [
                'ID',
                'Order Number',
                'Razorpay ID',
                'Method',
                'Amount',
                'Status',
                'Date',
            ], 

            'users' => [
                'ID',
                'Name',
                'Email',
                'Number',
                'Status',
                'Registered Date',
            ],

            'products' => [
                'ID',
                'Product',
                'Category',
                'Price',
                'Status',
                'Created Date',
            ],

            'categories' => [
                'ID',
                'Category',
                'Slug',
                'Status',
                'Created Date',
            ],

            'coupons' => [
                'ID',
                'Coupon Code',
                'Type',
                'Discount',
                'Minimum Order',
                'Status',
                'Created Date',
            ],

            default => [
                'ID',
                'Order Number',
                'Customer',
                'Email',
                'Amount',
                'Discount',
                'Status',
                'Order Date',
            ],
        };
    }

    public function map($record): array
    {
        return match ($this->reportType) {
            'payments' => [
                '#' . $record->id,
                $record->order?->order_number ?? '-',
                $record->razorpay_payment_id ?? '-',
                $record->payment_method ?? $record->method ?? '-',
                $record->amount ?? 0,
                $record->payment_status ?? '-',
                $record->created_at?->format('d M Y, h:i A'),
            ],

            'users' => [
                '#' . $record->id,
                $record->name,
                $record->email,
                $record->number ? ' ' . $record->number : '-',
                ucfirst($record->status),
                $record->created_at?->format('d M Y, h:i A'),
            ],

            'products' => [
                '#' . $record->id,
                $record->name,
                $record->category?->name ?? '-',
                $record->price,
                $record->status ? 'Active' : 'Inactive',
                $record->created_at?->format('d M Y, h:i A'),
            ],

            'categories' => [
                '#' . $record->id,
                $record->name,
                $record->slug,
                $record->status ? 'Active' : 'Inactive',
                $record->created_at?->format('d M Y, h:i A'),
            ],

            'coupons' => [
                '#' . $record->id,
                $record->code ?? '-',
                $record->type,
                $record->discount_value ?? 0,
                $record->minimum_order_amount ?? 0,
                ($record->end_date && now()->gt($record->end_date)) ? 'Inactive' : ($record->status ? 'Active' : 'Inactive'),
                $record->created_at?->format('d M Y, h:i A'),
            ],

            default => [
                '#' . $record->id,
                $record->order_number,
                $record->detail?->name ?? '-',
                $record->detail?->email ?? '-',
                $record->amount,
                $record->coupon_discount ?? 0,
                $record->status,
                $record->created_at?->format('d M Y, h:i A'),
            ],
        };
    }
}