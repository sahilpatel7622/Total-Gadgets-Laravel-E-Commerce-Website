<?php

namespace App\Exports;

use App\Models\Coupon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CouponsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Coupon::query()->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Coupon Code',
            'Type',
            'Discount',
            'Minimum Order',
            'Usage Limit',
            'Per User Limit',
            'Start Date',
            'End Date',
            'Status',
            'Created Date',
        ];
    }

    public function map($coupon): array
    {
        return [
            '#' . $coupon->id,
            $coupon->code,
            $coupon->type,
            $coupon->discount_value,
            $coupon->minimum_order_amount,
            $coupon->usage_limit,
            $coupon->per_user_limit,
            $coupon->start_date?->format('d M Y, h:i A'),
            $coupon->end_date?->format('d M Y, h:i A'),
            ($coupon->end_date && now()->gt($coupon->end_date)) ? 'Inactive' : ($coupon->status ? 'Active' : 'Inactive'),
            $coupon->created_at?->format('d M Y, h:i A'),
        ];
    }
}
