<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/fruits', 'pages.fruits')->name('fruits');
Route::view('/my-orders', 'pages.my-orders')->name('my-orders');
Route::view('/inventory', 'pages.inventory')->name('inventory');
Route::view('/orders', 'pages.orders')->name('orders');
Route::view('/admin/users', 'admin.users')->name('admin.users');


Route::get('/admin/products', function () {
    return view('admin.products.index');
});
Route::get('/admin/products/create', function () {
    return view('admin.products.create');
});
Route::get('/admin/products/{id}/edit', function ($id) {
    return view('admin.products.edit', ['id' => $id]);
});


Route::view('manager/inventory', 'manager.inventory')
    ->name('manager.inventory');
