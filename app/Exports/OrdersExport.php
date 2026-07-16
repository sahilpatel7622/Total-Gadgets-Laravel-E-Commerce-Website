<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Order::query()
            ->with('Detail')
            ->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Order Number',
            'Customer',
            'Email',
            'Mobile',
            'Coupon Code',
            'Coupon Discount',
            'Amount',
            'Status',
            'Order Date',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->order_number,
            $order->orderDetail?->name ?? '-',
            $order->orderDetail?->email ?? '-',
            $order->orderDetail?->number ?? '-',
            $order->coupon_code ?? '-',
            $order->coupon_discount ?? 0,
            $order->amount,
            $order->status,
            $order->created_at?->format('d-m-Y H:i'),
        ];
    }
}