<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-code', [AuthController::class, 'resendCode']);



// Public Services (Active Services Only)
Route::get('/services', [ServiceController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (User & Admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Services
    Route::get('/services/{service}', [ServiceController::class, 'show']);

    // Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{cart}', [CartController::class, 'update']);
        Route::delete('/clear', [CartController::class, 'clear']); // Clear all cart items
        Route::delete('/{cart}', [CartController::class, 'destroy']);
    });

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'admin'])->group(function () {
    // Services Management
    Route::prefix('admin/services')->group(function () {
        Route::get('/', [ServiceController::class, 'all']); // Get all services (including inactive)
        Route::post('/', [ServiceController::class, 'store']);
        Route::post('/{service}', [ServiceController::class, 'update']);
        Route::delete('/{service}', [ServiceController::class, 'destroy']);
    });

    // Orders Management
    Route::prefix('admin/orders')->group(function () {
        Route::get('/', [AdminController::class, 'getAllOrders']);
        Route::get('/{order}', [AdminController::class, 'getOrder']);
        Route::put('/{order}/status', [AdminController::class, 'updateOrderStatus']);
    });

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
