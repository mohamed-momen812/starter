<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Platform Routes (User-Facing)
Route::group(['middleware' => ['api']], function () {

    // === Auth Routes ===
    Route::prefix('auth')->group(function () {
        // Public routes (No auth required for register and login)
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Protected routes (Auth required)
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });
    });

    // === User Routes ===
    Route::prefix('user')->middleware('auth:sanctum')->group(function () {
        Route::put('/update-profile', [UserController::class, 'updateProfile']);
    }); // TODO:
});
