<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ShippingAddressController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SocialiteController;
use Illuminate\Support\Facades\Route;

// Platform Routes (User-Facing)

    // === Auth Routes ===
    Route::prefix('auth')->group(function () {

        // Public routes (No auth required for register and login)
        // Socialite routes (Login with Google)
        Route::get('/google/redirect', [SocialiteController::class, 'redirectToProvider']);
        Route::get('/google/callback', [SocialiteController::class, 'handleProviderCallback']);

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        // Protected routes (Auth required)
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });
    });

    // === User Routes ===
    Route::middleware('auth:sanctum')->group(function () {

        // === Product Routes ===
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{id}', [ProductController::class, 'show']);
        });

        // === Category Routes ===
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/{id}', [CategoryController::class, 'show']);
        });

        // === Cart Routes ===
        Route::prefix('cart')->group(function () {
            Route::post('/', [CartController::class, 'addToCart']);
            Route::get('/', [CartController::class, 'viewCart']);
            Route::put('/{id}', [CartController::class, 'updateCart']);
            Route::delete('/{id}', [CartController::class, 'removeFromCart']);
        });

        // === Shipping Address Routes ===
        Route::prefix('shipping-addresses')->group(function () {
            Route::apiResource('/', ShippingAddressController::class);
        });

        // === Order Routes ===
        Route::prefix('orders')->group(function () {
            Route::post('/', [OrderController::class, 'placeOrder']);
            Route::get('/{id}', [OrderController::class, 'getOrderDetails']);
        });

    });
    // === Payment Routes === must be named
        Route::post('pay', [PaymentController::class, 'pay'])->name('payment');
        Route::get('success', [PaymentController::class, 'success'])->name('success');
        Route::get('error', [PaymentController::class, 'error'])->name('error');

