<?php

use Illuminate\Support\Facades\Route;

Route::view('manager/inventory', 'manager.inventory')
    ->name('manager.inventory');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/products', function () {
    return view('admin.products.index');
});

Route::get('/admin/products/create', function () {
    return view('admin.products.create');
});

Route::get('/admin/products/{id}/edit', function ($id) {
    return view('admin.products.edit', ['id' => $id]);
});