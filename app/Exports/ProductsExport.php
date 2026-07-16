<?php

namespace App\Exports;

use App\Models\product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return product::query()->with('category')->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product',
            'Category',
            'Price',
            'Status',
            'Created Date',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category?->name ?? '-',
            $product->price,
            $product->status ? 'Active' : 'Inactive',
            $product->created_at?->format('d-m-Y H:i'),
        ];
    }
}
