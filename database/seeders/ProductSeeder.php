<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // -----------------------------
        // 1. Categories
        // -----------------------------
        $categories = [
            'Beverages',
            'Snacks',
            'Cosmetics',
            'Stationery',
            'Electronics',
        ];

        $categoryIds = [];

        foreach ($categories as $cat) {
            $id = (string) Str::uuid();
            DB::table('categories')->insert([
                'id' => $id,
                'name' => $cat,
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $categoryIds[$cat] = $id;
        }

        // -----------------------------
        // 2. Products
        // -----------------------------
        $products = [
            ['name' => 'Matcha Green Tea', 'sku' => 'MT-001', 'category' => 'Beverages', 'price' => 500],
            ['name' => 'Pocky Chocolate', 'sku' => 'PK-002', 'category' => 'Snacks', 'price' => 200],
            ['name' => 'Shiseido Lipstick', 'sku' => 'SH-003', 'category' => 'Cosmetics', 'price' => 1500],
            ['name' => 'Uni-ball Signo Pen', 'sku' => 'UB-004', 'category' => 'Stationery', 'price' => 100],
            ['name' => 'Sony Headphones', 'sku' => 'SN-005', 'category' => 'Electronics', 'price' => 8000],
        ];

        $productIds = [];

        foreach ($products as $prod) {
            $id = (string) Str::uuid();
            DB::table('products')->insert([
                'id' => $id,
                'name' => $prod['name'],
                'sku' => $prod['sku'],
                'category_id' => $categoryIds[$prod['category']],
                'price' => $prod['price'],
                'stock' => rand(10, 100),
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $productIds[$prod['sku']] = $id;
        }

        // -----------------------------
        // 3. Product Variations (opsional)
        // -----------------------------
        $variations = [
            ['product_sku' => 'MT-001', 'size' => '250ml', 'color' => null, 'sku' => 'MT-001-S'],
            ['product_sku' => 'MT-001', 'size' => '500ml', 'color' => null, 'sku' => 'MT-001-L'],
            ['product_sku' => 'SH-003', 'size' => null, 'color' => 'Red', 'sku' => 'SH-003-R'],
            ['product_sku' => 'SH-003', 'size' => null, 'color' => 'Pink', 'sku' => 'SH-003-P'],
        ];

        foreach ($variations as $var) {
            DB::table('product_variations')->insert([
                'id' => (string) Str::uuid(),
                'product_id' => $productIds[$var['product_sku']],
                'size' => $var['size'],
                'color' => $var['color'],
                'sku' => $var['sku'],
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
