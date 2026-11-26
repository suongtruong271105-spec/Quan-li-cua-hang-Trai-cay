<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use UserService\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database cho toàn bộ hệ thống (User, Product, Inventory, Order)
     */
    public function run(): void
    {
        // 1. Seed UserService (Tài khoản mẫu)
        if (class_exists(User::class)) {
            User::firstOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password'),
                    'role' => 'customer',
                ]
            );

            User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin User',
                    'password' => bcrypt('admin123'),
                    'role' => 'admin',
                ]
            );
        }

        // 2. Seed ProductService (15 loại trái cây)
        if (class_exists(ProductSeeder::class)) {
            $this->call(ProductSeeder::class);
        }

        // 3. Seed InventoryService (Các lô hàng tồn kho)
        DB::connection('inventory_sqlite')->table('inventory_batches')->truncate();
        DB::connection('inventory_sqlite')->table('inventory_batches')->insert([
            [
                'fruit_id' => 1, // Xoài Cát Hòa Lộc
                'quantity_kg' => 50.0,
                'import_date' => '2026-11-01',
                'expiry_date' => '2026-12-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fruit_id' => 1,
                'quantity_kg' => 70.0,
                'import_date' => '2026-11-10',
                'expiry_date' => '2026-12-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fruit_id' => 2, // Sầu Riêng Ri6
                'quantity_kg' => 80.0,
                'import_date' => '2026-11-05',
                'expiry_date' => '2026-12-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fruit_id' => 3, // Táo Envy Mỹ
                'quantity_kg' => 100.0,
                'import_date' => '2026-11-08',
                'expiry_date' => '2026-12-08',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. Seed OrderService (Các đơn hàng mẫu)
        DB::connection('order_sqlite')->table('order_items')->truncate();
        DB::connection('order_sqlite')->table('orders')->truncate();

        // Đơn 1: Hoàn thành
        $order1Id = DB::connection('order_sqlite')->table('orders')->insertGetId([
            'user_id' => 1,
            'total_amount' => 360000,
            'status' => 'completed',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
        DB::connection('order_sqlite')->table('order_items')->insert([
            [
                'order_id' => $order1Id,
                'fruit_id' => 3, // Táo Envy
                'quantity_kg' => 3.0,
                'unit_price' => 120000,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);

        // Đơn 2: Đang xử lý
        $order2Id = DB::connection('order_sqlite')->table('orders')->insertGetId([
            'user_id' => 2,
            'total_amount' => 290000,
            'status' => 'processing',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
        DB::connection('order_sqlite')->table('order_items')->insert([
            [
                'order_id' => $order2Id,
                'fruit_id' => 2, // Sầu Riêng
                'quantity_kg' => 2.0,
                'unit_price' => 145000,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);

        // Đơn 3: Chờ thanh toán
        $order3Id = DB::connection('order_sqlite')->table('orders')->insertGetId([
            'user_id' => 3,
            'total_amount' => 130000,
            'status' => 'pending_payment',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::connection('order_sqlite')->table('order_items')->insert([
            [
                'order_id' => $order3Id,
                'fruit_id' => 1, // Xoài Cát
                'quantity_kg' => 2.0,
                'unit_price' => 65000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
