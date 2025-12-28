<?php

use App\Http\Controllers\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.role:kasir'])->group(function () {
    Route::resource('transactions', TransactionController::class)->only('index', 'create', 'store', 'show');
});