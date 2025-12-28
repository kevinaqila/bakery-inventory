<?php

use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\StockController;
use Illuminate\Support\Facades\Route;

// Products & Categories - accessible by all authenticated users
// but create/edit/delete only by admin (checked in controller)
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('categories', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

// Stocks - only Karyawan
Route::middleware(['auth', 'check.role:karyawan'])->group(function () {
    Route::resource('stocks', StockController::class)->only(['index', 'create', 'store']);
});