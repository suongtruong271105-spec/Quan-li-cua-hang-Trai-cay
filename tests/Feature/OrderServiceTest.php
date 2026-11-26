<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--force' => true]);
    }

    /**
     * Test API thống kê kinh doanh: /api/orders/statistics
     */
    public function test_can_get_order_statistics(): void
    {
        $response = $this->getJson('/api/orders/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'total_orders',
                    'total_revenue',
                    'completed_revenue',
                    'status_counts' => [
                        'pending_payment',
                        'processing',
                        'completed',
                        'cancelled',
                    ],
                    'formatted_total_revenue',
                    'formatted_completed_revenue',
                ],
            ]);

        $this->assertGreaterThanOrEqual(3, $response->json('data.total_orders'));
    }

    /**
     * Test API lấy danh sách đơn hàng: /api/orders
     */
    public function test_can_list_orders(): void
    {
        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'total',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'total_amount',
                        'status',
                        'items',
                    ],
                ],
            ]);
    }

    /**
     * Test lọc đơn hàng theo status: /api/orders?status=completed
     */
    public function test_can_filter_orders_by_status(): void
    {
        $response = $this->getJson('/api/orders?status=completed');

        $response->assertStatus(200);
        foreach ($response->json('data') as $order) {
            $this->assertEquals('completed', $order['status']);
        }
    }

    /**
     * Test API chi tiết đơn hàng: /api/orders/{id}
     */
    public function test_can_get_order_detail(): void
    {
        $response = $this->getJson('/api/orders/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'total_amount',
                    'status',
                    'items' => [
                        '*' => [
                            'id',
                            'order_id',
                            'fruit_id',
                            'quantity_kg',
                            'unit_price',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test API cập nhật trạng thái đơn: PUT /api/orders/{id}/status
     */
    public function test_can_update_order_status(): void
    {
        $response = $this->putJson('/api/orders/1/status', [
            'status' => 'processing',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'processing');

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'status' => 'processing',
        ], 'order_sqlite');
    }

    /**
     * Test API tạo đơn hàng mới: gọi HTTP sang ProductService lấy giá và InventoryService trừ kho FIFO
     */
    public function test_can_create_order_with_http_calls_to_product_and_inventory_service(): void
    {
        // Mock HTTP responses từ ProductService và InventoryService
        Http::fake([
            '*/api/fruits/1' => Http::response([
                'message' => 'Lấy thông tin trái cây thành công',
                'data' => [
                    'id' => 1,
                    'name' => 'Táo Envy Mỹ',
                    'price_per_kg' => 120000,
                ],
            ], 200),
            '*/api/inventory/deduct' => Http::response([
                'message' => 'Trừ kho FIFO thành công',
                'fruit_id' => 1,
                'deducted_kg' => 2.5,
            ], 200),
        ]);

        $payload = [
            'user_id' => 10,
            'items' => [
                [
                    'fruit_id' => 1,
                    'quantity_kg' => 2.5,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'total_amount',
                    'status',
                    'items',
                ],
            ]);

        // 2.5kg * 120.000 = 300.000đ
        $this->assertEquals(300000, $response->json('data.total_amount'));
        $this->assertEquals('pending_payment', $response->json('data.status'));

        // Kiểm tra đã gửi HTTP request tới ProductService và InventoryService
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/fruits/1');
        });

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/inventory/deduct')
                && $request['fruit_id'] === 1
                && $request['quantity_kg'] == 2.5;
        });

        // Kiểm tra đơn đã được lưu vào SQLite của OrderService
        $this->assertDatabaseHas('orders', [
            'user_id' => 10,
            'total_amount' => 300000,
            'status' => 'pending_payment',
        ], 'order_sqlite');
    }

    /**
     * Test khi InventoryService trả về lỗi hết hàng/không đủ kho FIFO
     */
    public function test_cannot_create_order_when_inventory_service_returns_out_of_stock(): void
    {
        Http::fake([
            '*/api/fruits/1' => Http::response([
                'data' => ['price_per_kg' => 120000],
            ], 200),
            '*/api/inventory/deduct' => Http::response([
                'message' => 'Không đủ tồn kho cho trái cây #1',
            ], 422),
        ]);

        $payload = [
            'user_id' => 10,
            'items' => [
                [
                    'fruit_id' => 1,
                    'quantity_kg' => 500.0,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Lỗi khi gọi HTTP sang InventoryService trừ kho FIFO: Không đủ tồn kho cho trái cây #1',
            ]);
    }

    /**
     * Test trang Web Frontend Admin OrderService tải thành công
     */
    public function test_admin_orders_frontend_page_loads_successfully(): void
    {
        $response = $this->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('OrderService');
        $response->assertSee('order_db.sqlite');
    }
}
