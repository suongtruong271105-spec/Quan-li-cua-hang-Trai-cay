<?php

use Illuminate\Support\Facades\Route;
use InventoryService\Controllers\InventoryController;


// INVENTORY SERVICE - Quản lý kho & lô hàng
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::post('/', [InventoryController::class, 'store']);
    Route::post('/deduct', [InventoryController::class, 'deduct']);
});
