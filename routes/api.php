<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use InventoryService\Controllers\InventoryController;
use ProductService\Controllers\ProductController;
use UserService\Controllers\AuthController;
use UserService\Controllers\UserController;

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


Route::apiResource('products', ProductController::class);


// INVENTORY SERVICE - Quản lý kho & lô hàng
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::get('/expiring', [InventoryController::class, 'expiring']);
    Route::post('/', [InventoryController::class, 'store']);
    Route::post('/deduct', [InventoryController::class, 'deduct']);
});
