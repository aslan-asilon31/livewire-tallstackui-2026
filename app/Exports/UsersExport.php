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
        return User::with('roles');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Queue Number',
            'Active',
            'Role',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->queue_number,
            $user->is_activated ? 'Aktif' : 'Non Aktif',
            $user->roles->pluck('name')->join(', '),
            $user->created_at?->format('Y-m-d'),
        ];
    }
}
