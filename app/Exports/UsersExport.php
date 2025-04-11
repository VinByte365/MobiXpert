<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        // Sample data
        return [
            ['John Doe', 'john@example.com', 'password123', 'user', 'active'],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'password',
            'role',
            'status',
        ];
    }
}