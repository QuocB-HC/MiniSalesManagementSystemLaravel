<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController; // Rename to avoid conflict with public ProductController
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController; // Rename to avoid conflict with public CategoryController
use App\Http\Controllers\Admin\UserController as AdminUserController; // Rename to avoid conflict with public UserController
use App\Http\Controllers\Admin\OrderController as AdminOrderController; // Rename to avoid conflict with public OrderController
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController; // Rename to avoid conflict with public DiscountController
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES (accessible to all users)
// Home page route
Route::get('/', [ProductController::class, 'homePage'])->name('home');

// Product routes
Route::prefix('products')->as('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index'); // products.index
    Route::get('/category/{category}', [ProductController::class, 'showByCategory'])->name('byCategory'); // products.byCategory
    Route::get('/detail/{id}', [ProductController::class, 'detail'])->name('detail'); // products.detail
    Route::get('/search', [ProductController::class, 'search'])->name('search'); // products.search
});


// Cart routes
Route::prefix('cart')->as('cart.')->group(function () {
    Route::post('/add/{id}', [CartController::class, 'addToCart'])->name('add'); // cart.add
    Route::get('/', [CartController::class, 'index'])->name('index'); // cart.index
    Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('update'); // cart.update
    Route::delete('/remove/{id}', [CartController::class, 'removeFromCart'])->name('remove'); // cart.remove
});

// 2. AUTHENTICATION ROUTES
// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/send-code', [AuthController::class, 'sendVerificationCode'])->name('send.code');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::get('/register-complete', [AuthController::class, 'showCompleteRegister'])->name('register.complete');

// 3. PROTECTED ROUTES (only for authenticated users)
Route::middleware('auth')->group(function () {
    // 1. USER PROFILE ROUTES
    // User profile routes
    Route::prefix('profile')->as('profile.')->group(function () {
        Route::get('/', [UserController::class, 'show'])->name('index'); // profile.index
        Route::get('/edit', [UserController::class, 'edit'])->name('edit'); // profile.edit
        Route::put('/update', [UserController::class, 'update'])->name('update'); // profile.update
    });

    // Checkout routes
    Route::prefix('checkout')->as('checkout.')->group(function () {
        Route::get('/', [CartController::class, 'checkout'])->name('index'); // checkout.index
        Route::post('/place-order', [CartController::class, 'placeOrder'])->name('placeOrder'); // checkout.placeOrder
        Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('applyDiscount');
        Route::get('/success/{id}', [CartController::class, 'orderSuccess'])->name('success'); // checkout.success
        Route::get('/vnpay-return', [CartController::class, 'vnpayReturn'])->name('vnpayReturn'); // checkout.returnVnpay
    });

    // Orders routes
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index'); // orders.index
        Route::get('/{id}', [OrderController::class, 'show'])->name('detail'); // orders.detail
    });

    Route::get('/return-vnpay', [CartController::class, 'vnpayReturn']);

    // ADMIN ROUTES (only for users with admin role)
    Route::middleware('can:admin')->prefix('admin')->as('admin.')->group(function () {
        // Admin dashboard route
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // admin.dashboard
        
        // Admin product routes
        Route::resource('products', AdminProductController::class)->except(['show']); // admin.products.index, admin.products.create, etc.
        
        // Admin category routes
        Route::resource('categories', AdminCategoryController::class)->except(['show']); // admin.categories.index, admin.categories.create, etc.
        
        // Admin user routes
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index'); // admin.users.index
        Route::put('/users/{user}', [AdminUserController::class, 'updateIsBanned'])->name('users.updateIsBanned'); // admin.users.updateIsBanned
        
        // Admin order routes
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index'); // admin.orders.index
        Route::put('/orders/{order}', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus'); // admin.orders.updateStatus

        // Admin discount routes
        Route::resource('discounts', AdminDiscountController::class)->except(['show']); // admin.discounts.index, admin.discounts.create, etc.
    });
});
