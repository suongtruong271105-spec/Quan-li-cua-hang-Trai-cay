<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.orders');
});

Route::get('/admin/orders', function () {
    return view('admin.orders');
});
