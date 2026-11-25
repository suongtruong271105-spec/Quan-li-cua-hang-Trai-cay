<?php

use Illuminate\Support\Facades\Route;
use InventoryService\Controllers\InventoryController;
use ProductService\Controllers\ProductController;
use OrderService\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes - Fruit Store SOA Architecture
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ORDER SERVICE (Phân hệ Xử lý Đơn hàng)
// ==========================================
Route::prefix('orders')->group(function () {
    Route::get('/statistics', [OrderController::class, 'statistics']);
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::match(['put', 'patch'], '/{id}/status', [OrderController::class, 'updateStatus']);
});

// ==========================================
// 2. PRODUCT SERVICE (Quản lý Trái cây)
// ==========================================
Route::apiResource('products', ProductController::class);

// Alias cho ProductService (hỗ trợ cả /api/fruits/{id} cho OrderService gọi HTTP)
Route::prefix('fruits')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

// ==========================================
// 3. INVENTORY SERVICE (Quản lý kho & FIFO)
// ==========================================
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::post('/', [InventoryController::class, 'store']);
});
