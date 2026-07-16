<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Payment::query()->with('order')->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Order Number',
            'Razorpay ID',
            'Method',
            'Amount',
            'Status',
            'Date',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->order?->order_number ?? '-',
            $payment->razorpay_payment_id ?? '-',
            $payment->payment_method ?? $payment->method ?? '-',
            $payment->amount ?? 0,
            $payment->payment_status ?? '-',
            $payment->created_at?->format('d-m-Y H:i'),
        ];
    }
}
