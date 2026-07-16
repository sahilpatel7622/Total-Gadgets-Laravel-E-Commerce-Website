<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return User::query()->where('role', 'user')->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Number',
            'Status',
            'Registered Date',
        ];
    }

    public function map($user): array
    {
        return [
            '#' . $user->id,
            $user->name,
            $user->email,
            $user->number ? ' ' . $user->number : '-',
            ucfirst($user->status),
            $user->created_at?->format('d M Y, h:i A'),
        ];
    }
}
