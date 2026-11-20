<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database cho OrderService (order_db.sqlite)
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu có
        DB::connection('order_sqlite')->table('order_items')->truncate();
        DB::connection('order_sqlite')->table('orders')->truncate();

        // 1. Đơn hàng hoàn thành
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
                'fruit_id' => 1, // Táo Envy
                'quantity_kg' => 3.0,
                'unit_price' => 120000,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);

        // 2. Đơn hàng đang xử lý
        $order2Id = DB::connection('order_sqlite')->table('orders')->insertGetId([
            'user_id' => 2,
            'total_amount' => 440000,
            'status' => 'processing',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
        DB::connection('order_sqlite')->table('order_items')->insert([
            [
                'order_id' => $order2Id,
                'fruit_id' => 2, // Cam Sành
                'quantity_kg' => 2.0,
                'unit_price' => 45000,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'order_id' => $order2Id,
                'fruit_id' => 3, // Nho Mẫu Đơn
                'quantity_kg' => 1.0,
                'unit_price' => 350000,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);

        // 3. Đơn hàng chờ thanh toán
        $order3Id = DB::connection('order_sqlite')->table('orders')->insertGetId([
            'user_id' => 3,
            'total_amount' => 170000,
            'status' => 'pending_payment',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::connection('order_sqlite')->table('order_items')->insert([
            [
                'order_id' => $order3Id,
                'fruit_id' => 4, // Xoài Cát
                'quantity_kg' => 2.0,
                'unit_price' => 85000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
