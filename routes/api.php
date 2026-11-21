<?php

use Illuminate\Support\Facades\Route;
use InventoryService\Controllers\InventoryController;
use ProductService\Controllers\ProductController;

// INVENTORY SERVICE - Quản lý kho & lô hàng
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::post('/', [InventoryController::class, 'store']);
});


Route::apiResource('products', ProductController::class);