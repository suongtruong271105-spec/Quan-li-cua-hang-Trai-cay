<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InventoryService\Controllers\InventoryController;
use ProductService\Controllers\ProductController;
use OrderService\Controllers\OrderController;
use UserService\Controllers\AuthController;
use UserService\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes - Fruit Store SOA Architecture
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. USER SERVICE (Xác thực & Người dùng)
// ==========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/me', fn(Request $request) => response()->json([
        'user' => $request->user('api'),
    ]));

    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::patch('/users/{id}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});

// ==========================================
// 2. ORDER SERVICE (Phân hệ Xử lý Đơn hàng)
// ==========================================
Route::prefix('orders')->group(function () {
    Route::get('/statistics', [OrderController::class, 'statistics']);
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::match(['put', 'patch'], '/{id}/status', [OrderController::class, 'updateStatus']);
});

// ==========================================
// 3. PRODUCT SERVICE (Quản lý Trái cây)
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
// 4. INVENTORY SERVICE (Quản lý kho & FIFO)
// ==========================================
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::get('/expiring', [InventoryController::class, 'expiring']);
    Route::post('/', [InventoryController::class, 'store']);
    Route::post('/deduct', [InventoryController::class, 'deduct']);
});
