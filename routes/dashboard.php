<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard Routes (Admin-Facing)
Route::group(['middleware' => ['api', 'auth:sanctum', 'can:access_dashboard'], 'prefix' => 'admin'], function () {

    // === User Management ===
    Route::apiResource('users', UserController::class);

    // === Role Routes ===
    Route::apiResource('roles', RoleController::class);

    // === Permission Routes ===
    Route::prefix('permissions')->group(function () {
        Route::apiResource('/', PermissionController::class)->parameters(['' => 'permission']); // must use parameter cause i use just /
        Route::get('/user/{id}', [PermissionController::class, 'getPermissions']);
        Route::post('/user/{id}', [PermissionController::class, 'updatePermissions']);
    });

    // === Product Management ===
    // Route::apiResource('products', ProductController::class);
    // Route::get('/plans', [SubscriptionController::class, 'plans']);
});
