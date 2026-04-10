<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DiscountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES ==========//

// Product Routes
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Category Routes
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Discount Routes
Route::get('discounts/check/{code}', [DiscountController::class, 'check']);

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// ========== PRIVATE ROUTES ==========//
Route::middleware('auth:sanctum')->group(function () {

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/add', [OrderController::class, 'store']);
    });

    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/profile', [UserController::class, 'index']);
        Route::put('/update', [UserController::class, 'update']);
    });

    // ========== ADMIN ROUTES ==========//
    Route::middleware('can:admin')->prefix('admin')->group(function () {

        // Category Routes
        Route::prefix('categories')->group(function () {
            Route::post('/add', [CategoryController::class, 'store']);
            Route::put('/update/{id}', [CategoryController::class, 'update']);
            Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
        });

        // Product Routes
        Route::prefix('products')->group(function () {
            Route::post('/add', [ProductController::class, 'store']);
            Route::put('/update/{id}', [ProductController::class, 'update']);
            Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
        });

        // Discount Routes
        Route::prefix('discounts')->group(function () {
            Route::get('/', [DiscountController::class, 'index']);
            Route::post('/add', [DiscountController::class, 'store']);
            Route::put('/update/{id}', [DiscountController::class, 'update']);
            Route::delete('/delete/{id}', [DiscountController::class, 'destroy']);
        });

        // Order Routes
        Route::put('/orders/update-status/{order}', [AdminOrderController::class, 'updateStatus']);

        // User Routes
        Route::prefix('users')->group(function () {
            Route::get('/customers', [AdminUserController::class, 'showCustomers']);
            Route::put('/update-is-banned/{user}', [AdminUserController::class, 'updateIsBanned']);
        });
    });
});
