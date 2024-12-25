<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


// just for testing payment
Route::get('/', function () {
    return view('welcome'); 
});
Route::post('pay', [PaymentController::class, 'pay'])->name('payment');
Route::get('success', [PaymentController::class, 'success'])->name('success');
Route::get('error', [PaymentController::class, 'error'])->name('error');