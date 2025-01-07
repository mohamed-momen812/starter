<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialiteController;
use Illuminate\Support\Facades\Route;

// Platform Routes (User-Facing)
Route::group(['middleware' => ['api']], function () {

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

    // Route::middleware('auth:sanctum')->group(function () {
    //     // === Payment Routes === must be named
    //     Route::post('pay', [PaymentController::class, 'pay'])->name('payment');
    //     Route::get('success', [PaymentController::class, 'success'])->name('success');
    //     Route::get('error', [PaymentController::class, 'error'])->name('error');

    //     // Subscription routes
    //     Route::get('/plans', [SubscriptionController::class, 'plans']);
    //     Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    //     Route::get('/premium-content', function () {
    //         dd('Premium content');
    //         })->middleware('subscription');
    // });
});
