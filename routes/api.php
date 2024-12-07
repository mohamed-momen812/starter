<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

// auth routes
Route::group(['middleware' => ['api', 'auth:sanctum'], 'prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:sanctum');
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
