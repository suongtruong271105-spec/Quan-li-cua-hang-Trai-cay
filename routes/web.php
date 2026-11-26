<?php

use Illuminate\Support\Facades\Route;


// =====================================
// CUSTOMER - THẢO
// =====================================

Route::view('/customer/store', 'customer.store')
    ->name('customer.store');

Route::view('/customer/orders', 'customer.orders')
    ->name('customer.orders');


// =====================================
// MAIN PAGES
// =====================================

Route::view('/', 'pages.home')->name('home');

Route::view('/login', 'auth.login')->name('login');

Route::view('/register', 'auth.register')->name('register');

Route::view('/fruits', 'pages.fruits')->name('fruits');

Route::view('/my-orders', 'pages.my-orders')->name('my-orders');

Route::view('/inventory', 'pages.inventory')->name('inventory');

Route::view('/orders', 'pages.orders')->name('orders');


// =====================================
// ADMIN
// =====================================

Route::view('/admin/users', 'admin.users')
    ->name('admin.users');

Route::get('/admin/orders', function () {
    return view('admin.orders');
})->name('admin.orders');


// =====================================
// ADMIN - PRODUCT MANAGEMENT
// =====================================

Route::get('/admin/products', function () {
    return view('admin.products.index');
})->name('admin.products.index');

Route::get('/admin/products/create', function () {
    return view('admin.products.create');
})->name('admin.products.create');

Route::get('/admin/products/{id}/edit', function ($id) {
    return view('admin.products.edit', ['id' => $id]);
})->name('admin.products.edit');