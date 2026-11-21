<?php

use Illuminate\Support\Facades\Route;

Route::view('/customer/store', 'customer.store')
    ->name('customer.store');

Route::view('/customer/orders', 'customer.orders')
    ->name('customer.orders');

Route::get('/', function () {
    return view('welcome');
});
