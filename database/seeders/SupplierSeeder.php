<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'ABC Supplier', 'phone' => '080-1111-2222'],
            ['name' => 'XYZ Supplier', 'phone' => '080-3333-4444'],
        ];

        foreach ($suppliers as $sup) {
            DB::table('suppliers')->insert([
                'id' => (string) Str::uuid(),
                'name' => $sup['name'],
                'phone' => $sup['phone'],
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
