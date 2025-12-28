<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.role:admin'])->group(function () {
    Route::resource('users', UserController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');
});