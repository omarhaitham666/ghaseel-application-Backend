<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserLocationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-code', [AuthController::class, 'resendCode']);

Route::get('/services', [ServiceController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Single Service
    Route::get('/services/{service}', [ServiceController::class, 'show']);

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{cart}', [CartController::class, 'update']);
        Route::delete('/clear', [CartController::class, 'clear']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);
        
    });

    // Orders for USER
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
    });

    // User locations
    Route::prefix('user/locations')->group(function () {
        Route::get('/', [UserLocationController::class, 'index']);
        Route::post('/', [UserLocationController::class, 'store']);
        Route::put('/{id}', [UserLocationController::class, 'update']);
        Route::delete('/{id}', [UserLocationController::class, 'destroy']);
    });
      Route::get('/my-cart', [CartController::class, 'myCart']);   
      Route::delete('/cart/{cart}', [CartController::class, 'destroy']);
      Route::delete('/cart-clear', [CartController::class, 'clear']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'admin'])->group(function () {

    // Services Management
    Route::prefix('admin/services')->group(function () {
        Route::get('/', [ServiceController::class, 'all']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::post('/{service}', [ServiceController::class, 'update']);
        Route::delete('/{service}', [ServiceController::class, 'destroy']);
    });

    // Orders Management
    Route::prefix('admin/orders')->group(function () {
        Route::get('/', [AdminController::class, 'getAllOrders']);
        Route::get('/{order}', [AdminController::class, 'getOrder']);
        

        // Admin actions
        Route::post('/{order}/accept', [AdminController::class, 'acceptOrder']);
        Route::post('/{order}/reject', [AdminController::class, 'rejectOrder']);
        Route::put('/{order}/status', [AdminController::class, 'updateOrderStatus']);
    });

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::delete('admin/order/{orderId}', [AdminController::class, 'adminDeleteOrder']);

    
});
