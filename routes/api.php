<?php

use Illuminate\Support\Facades\Route;
use OrderService\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes - OrderService (Phân hệ Xử lý Đơn hàng)
|--------------------------------------------------------------------------
|
| Định tuyến các API phục vụ OrderService:
| - POST   /api/orders             : Tạo đơn hàng mới (gọi HTTP ProductService lấy giá, InventoryService trừ kho FIFO)
| - GET    /api/orders             : Xem danh sách đơn hàng (hỗ trợ lọc status, user_id)
| - GET    /api/orders/statistics  : Thống kê cơ bản (tổng số đơn, tổng doanh thu, phân loại trạng thái)
| - GET    /api/orders/{id}        : Xem chi tiết đơn hàng kèm các mặt hàng
| - PUT    /api/orders/{id}/status : Cập nhật trạng thái đơn (pending_payment, processing, completed, cancelled)
|
*/

Route::prefix('orders')->group(function () {
    Route::get('/statistics', [OrderController::class, 'statistics']);
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::match(['put', 'patch'], '/{id}/status', [OrderController::class, 'updateStatus']);
});
