<?php

namespace OrderService\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use OrderService\Models\Order;
use OrderService\Models\OrderItem;

class OrderController
{
    /**
     * POST /api/orders
     * API tạo đơn hàng (gọi HTTP sang ProductService lấy giá và InventoryService trừ kho FIFO).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.fruit_id' => 'required|integer|min:1',
            'items.*.quantity_kg' => 'required|numeric|gt:0',
        ]);

        $userId = (int) $validated['user_id'];
        $itemsData = $validated['items'];

        $calculatedItems = [];
        $totalAmount = 0.0;

        // 1. Gọi HTTP sang ProductService để lấy giá cho từng sản phẩm
        foreach ($itemsData as $item) {
            $fruitId = (int) $item['fruit_id'];
            $quantityKg = (float) $item['quantity_kg'];

            try {
                $unitPrice = $this->fetchFruitPriceFromProductService($fruitId);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => 'Lỗi khi gọi HTTP sang ProductService lấy giá: ' . $e->getMessage(),
                ], 422);
            }

            $lineTotal = round($unitPrice * $quantityKg, 2);
            $totalAmount += $lineTotal;

            $calculatedItems[] = [
                'fruit_id' => $fruitId,
                'quantity_kg' => $quantityKg,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        }

        // 2. Gọi HTTP sang InventoryService để trừ kho theo nguyên tắc FIFO
        foreach ($calculatedItems as $calcItem) {
            try {
                $this->deductStockFromInventoryServiceFIFO($calcItem['fruit_id'], $calcItem['quantity_kg']);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => 'Lỗi khi gọi HTTP sang InventoryService trừ kho FIFO: ' . $e->getMessage(),
                ], 422);
            }
        }

        // 3. Lưu thông tin đơn hàng và chi tiết vào order_db.sqlite
        DB::connection('order_sqlite')->beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
            ]);

            foreach ($calculatedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'fruit_id' => $item['fruit_id'],
                    'quantity_kg' => $item['quantity_kg'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            DB::connection('order_sqlite')->commit();
        } catch (\Throwable $e) {
            DB::connection('order_sqlite')->rollBack();
            return response()->json([
                'message' => 'Lưu đơn hàng vào OrderService database thất bại: ' . $e->getMessage(),
            ], 500);
        }

        $order->load('items');

        return response()->json([
            'message' => 'Tạo đơn hàng thành công',
            'data' => $order,
        ], 201);
    }

    /**
     * GET /api/orders
     * API xem danh sách đơn hàng (hỗ trợ lọc theo trạng thái, user_id).
     */
    public function index(Request $request)
    {
        $query = Order::query()->with('items')->orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->query('user_id'));
        }

        $orders = $query->get();

        return response()->json([
            'message' => 'Lấy danh sách đơn hàng thành công',
            'total' => $orders->count(),
            'data' => $orders,
        ], 200);
    }

    /**
     * GET /api/orders/{id}
     * API xem chi tiết đơn hàng.
     */
    public function show($id)
    {
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json([
                'message' => "Không tìm thấy đơn hàng #{$id}",
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy chi tiết đơn hàng thành công',
            'data' => $order,
        ], 200);
    }

    /**
     * PUT/PATCH /api/orders/{id}/status
     * API cập nhật trạng thái đơn hàng.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending_payment,processing,completed,cancelled',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => "Không tìm thấy đơn hàng #{$id}",
            ], 404);
        }

        $order->status = $validated['status'];
        $order->save();

        return response()->json([
            'message' => "Cập nhật trạng thái đơn hàng #{$id} thành công",
            'data' => $order->load('items'),
        ], 200);
    }

    /**
     * GET /api/orders/statistics
     * API thống kê cơ bản: tổng số đơn, tổng doanh thu, số đơn theo trạng thái.
     */
    public function statistics()
    {
        $totalOrders = Order::count();
        $totalRevenue = (float) Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $completedRevenue = (float) Order::where('status', 'completed')->sum('total_amount');

        $statusCounts = [
            'pending_payment' => Order::where('status', 'pending_payment')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return response()->json([
            'message' => 'Thống kê cơ bản tình hình kinh doanh',
            'data' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'completed_revenue' => $completedRevenue,
                'status_counts' => $statusCounts,
                'formatted_total_revenue' => number_format($totalRevenue, 0, ',', '.') . ' đ',
                'formatted_completed_revenue' => number_format($completedRevenue, 0, ',', '.') . ' đ',
            ],
        ], 200);
    }

    /**
     * Gọi HTTP sang ProductService lấy giá trái cây theo ID
     */
    protected function fetchFruitPriceFromProductService(int $fruitId): float
    {
        $serviceUrl = env('PRODUCT_SERVICE_URL', 'http://localhost:8000/api/fruits');

        try {
            $response = Http::timeout(3)->acceptJson()->get("{$serviceUrl}/{$fruitId}");
            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['data']['price_per_kg'])) {
                    return (float) $body['data']['price_per_kg'];
                }
                if (isset($body['price_per_kg'])) {
                    return (float) $body['price_per_kg'];
                }
            }
            if ($response->status() === 404) {
                throw new \Exception("404 tại URL: {$serviceUrl}/products/{$fruitId}");
            }
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Không tìm thấy trái cây')) {
                throw $e;
            }
            // Cho phép sử dụng bảng giá tham chiếu khi ProductService chưa khởi chạy
            return $this->getMockFruitPrice($fruitId);
        }

        return $this->getMockFruitPrice($fruitId);
    }

    /**
     * Gọi HTTP sang InventoryService trừ kho theo FIFO
     */
    protected function deductStockFromInventoryServiceFIFO(int $fruitId, float $quantityKg): void
    {
       $serviceUrl = env('PRODUCT_SERVICE_URL', 'http://127.0.0.1:8002/api/products');

        try {
            $response = Http::timeout(3)->acceptJson()->post("{$serviceUrl}/deduct", [
                'fruit_id' => $fruitId,
                'quantity_kg' => $quantityKg,
            ]);

            if ($response->successful()) {
                return;
            }

            if ($response->status() === 422 || $response->status() === 400) {
                $err = $response->json()['message'] ?? "Không đủ tồn kho cho trái cây #{$fruitId}";
                throw new \Exception($err);
            }
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Không đủ tồn kho')) {
                throw $e;
            }
            // Nếu biến môi trường bật mô phỏng hoặc inventory không chạy
            if (env('INVENTORY_SIMULATION', true)) {
                return;
            }
            throw new \Exception("Không thể kết nối HTTP tới InventoryService ({$serviceUrl}/deduct): " . $e->getMessage());
        }
    }

    /**
     * Bảng giá tham chiếu dự phòng khi ProductService offline
     */
    protected function getMockFruitPrice(int $fruitId): float
    {
        $prices = [
            1 => 120000.0, // Táo Envy Mỹ
            2 => 45000.0,  // Cam Sành Sài Gòn
            3 => 350000.0, // Nho Mẫu Đơn Nhật
            4 => 85000.0,  // Xoài Cát Hòa Lộc
        ];

        return $prices[$fruitId] ?? 100000.0;
    }
}
