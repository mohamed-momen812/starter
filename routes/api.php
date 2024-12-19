<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api', 'auth:sanctum']
    ], function () {

        // auth routes
            Route::group([
                'prefix' => 'auth'
                ], function () {
                    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:sanctum');
                    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
                    Route::post('/logout', [AuthController::class, 'logout']);
                    Route::post('/refresh', [AuthController::class, 'refresh']);
                    Route::post('/change-password' , [AuthController::class , 'changePassword']);
                });

        // users routes
            Route::apiResource('users', UserController::class)->middleware('can:manage_users');
            Route::put('user/change-prefered-color', [UserController::class , 'changePreferedColor']);
            Route::put('user/update-profile', [UserController::class , 'updateProfile']);

        // roles routes
            Route::apiResource('/roles', RoleController::class);

        // permissions routes
            Route::apiResource('/permissions', PermissionController::class);
            Route::get('/user-permissions/{id}', [PermissionController::class, 'getPermissions']);
            Route::post('/user-permissions/{id}', [PermissionController::class, 'updatePermissions']);

        // product routes
            Route::resource('products', ProductController::class);

    });


