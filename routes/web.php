<?php

use Illuminate\Support\Facades\Route;

Route::view('manager/inventory', 'manager.inventory')
    ->name('manager.inventory');

Route::get('/', function () {
    return view('welcome');
});
