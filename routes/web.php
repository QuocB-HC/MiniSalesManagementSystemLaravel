<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES (accessible to all users)
// Home page route
Route::get('/', [ProductController::class, 'index'])->name('home');

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
        Route::get('/success/{id}', [CartController::class, 'orderSuccess'])->name('success'); // checkout.success
    });

    // ADMIN ROUTES (only for users with admin role)
    Route::middleware('can:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', function() {
            return view('admin.dashboard');
        })->name('dashboard'); // admin.dashboard
        // Route::resource('products', ProductController::class)->except(['show']); // admin.products.index, admin.products.create, etc.
    });
});
