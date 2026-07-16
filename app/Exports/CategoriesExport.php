<?php

namespace App\Exports;

use App\Models\category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return category::query()->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Slug',
            'Status',
            'Created At',
        ];
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->slug,
            $category->status == 1 ? 'Active' : 'Inactive',
            $category->created_at?->format('d-m-Y H:i'),
        ];
    }
}
