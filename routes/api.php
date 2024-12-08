<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\ProductController;
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
            });
                
        // product routes
        Route::resource('products', ProductController::class);
    });


