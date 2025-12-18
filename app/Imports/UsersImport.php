<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'name'         => $row['nama'],
            'email'        => $row['email'],
            'queue_number' => $row['queue_number'] ?? null,
            'is_activated' => strtolower($row['active']) === 'aktif',
            'password'     => Hash::make('password123'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'  => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];
    }
}
