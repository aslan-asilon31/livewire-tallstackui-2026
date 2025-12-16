<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RetailSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------
        // 1. Categories
        // -----------------------------
        $categories = ['Electronics', 'Beauty', 'Food', 'Clothing', 'Stationery'];
        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'id' => (string) Str::uuid(),
                'name' => $cat,
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        // -----------------------------
        // 2. Products
        // -----------------------------
        $products = [
            ['name' => 'Sakura Shampoo', 'sku' => 'PROD001', 'price' => 12000, 'stock' => 50],
            ['name' => 'Matcha Tea', 'sku' => 'PROD002', 'price' => 8000, 'stock' => 100],
            ['name' => 'Kawaii Notebook', 'sku' => 'PROD003', 'price' => 5000, 'stock' => 200],
            ['name' => 'Japanese Green Tea Kit', 'sku' => 'PROD004', 'price' => 15000, 'stock' => 80],
        ];
        foreach ($products as $prod) {
            DB::table('products')->insert([
                'id' => (string) Str::uuid(),
                'name' => $prod['name'],
                'sku' => $prod['sku'],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'price' => $prod['price'],
                'stock' => $prod['stock'],
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $productIds = DB::table('products')->pluck('id')->toArray();

        // -----------------------------
        // 3. Customers
        // -----------------------------
        $customers = [
            ['name' => 'Satoshi Tanaka', 'email' => 'satoshi@example.com', 'phone' => '080-1111-2222'],
            ['name' => 'Yuki Nakamura', 'email' => 'yuki@example.com', 'phone' => '080-2222-3333'],
        ];
        foreach ($customers as $cus) {
            DB::table('customers')->insert([
                'id' => (string) Str::uuid(),
                'name' => $cus['name'],
                'email' => $cus['email'],
                'phone' => $cus['phone'],
                'address' => 'Tokyo, Japan',
                'loyalty_points' => rand(0, 100),
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $customerIds = DB::table('customers')->pluck('id')->toArray();

        // -----------------------------
        // 4. Sales & Sale Items
        // -----------------------------
        foreach ($customerIds as $customerId) {
            for ($i = 0; $i < 2; $i++) {
                $saleId = (string) Str::uuid();
                DB::table('sales')->insert([
                    'id' => $saleId,
                    'customer_id' => $customerId,
                    'sale_date' => now()->subDays(rand(0, 10)),
                    'total' => rand(5000, 20000),
                    'discount' => rand(0, 1000),
                    'status' => 'completed',
                    'created_by' => 'system',
                    'updated_by' => 'system',
                    'is_activated' => true,
                    'queued_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                for ($j = 0; $j < 2; $j++) {
                    DB::table('sale_items')->insert([
                        'id' => (string) Str::uuid(),
                        'sale_id' => $saleId,
                        'product_id' => $productIds[array_rand($productIds)],
                        'quantity' => rand(1, 5),
                        'price' => rand(1000, 5000),
                        'created_by' => 'system',
                        'updated_by' => 'system',
                        'is_activated' => true,
                        'queued_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // -----------------------------
        // 5. Procurements & Procurement Items
        // -----------------------------
        foreach ($productIds as $prodId) {
            $procurementId = (string) Str::uuid();
            DB::table('procurements')->insert([
                'id' => $procurementId,
                'supplier_id' => DB::table('suppliers')->inRandomOrder()->first()->id ?? null,
                'order_date' => now()->subDays(rand(1, 15)),
                'status' => 'pending',
                'total' => rand(10000, 50000),
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'queued_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('procurement_items')->insert([
                'id' => (string) Str::uuid(),
                'procurement_id' => $procurementId,
                'product_id' => $prodId,
                'quantity' => rand(5, 20),
                'price' => rand(1000, 5000),
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'queued_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // -----------------------------
        // 6. Promotions
        // -----------------------------
        $promos = [
            ['name' => 'Summer Sale', 'discount_value' => 10],
            ['name' => 'Winter Sale', 'discount_value' => 15],
        ];
        foreach ($promos as $promo) {
            DB::table('promotions')->insert([
                'id' => (string) Str::uuid(),
                'name' => $promo['name'],
                'discount_value' => $promo['discount_value'],
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(10),
                'created_by' => 'system',
                'updated_by' => 'system',
                'is_activated' => true,
                'queued_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // -----------------------------
        // 7. Customer Transactions
        // -----------------------------
        foreach ($customerIds as $cusId) {
            foreach (DB::table('sales')->where('customer_id', $cusId)->get() as $sale) {
                DB::table('customer_transactions')->insert([
                    'id' => (string) Str::uuid(),
                    'customer_id' => $cusId,
                    'sale_id' => $sale->id,
                    'amount' => $sale->total,
                    'type' => 'payment',
                    'created_by' => 'system',
                    'updated_by' => 'system',
                    'is_activated' => true,
                    'queued_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
