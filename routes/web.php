<?php

use App\Http\Controllers\Api\SocialiteController;
use App\Http\Controllers\Api\PaymentController;
use App\Events\MyEvent;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


// just for testing payment and socialite

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('pay', [PaymentController::class, 'pay'])->name('payment');
Route::get('success', [PaymentController::class, 'success'])->name('success');
Route::get('error', [PaymentController::class, 'error'])->name('error');


Route::get('api/auth/google/redirect', [SocialiteController::class, 'redirectToProvider'])->name('GoogleRedirect'); // must be named
Route::get('api/auth/google/callback', [SocialiteController::class, 'handleProviderCallback']); // this route will not work in laragon because redirect url is 127.0.0.1:8000 not starter.test


// This route is hit when user try to make requests in routes has verified middleware and his email does not verified
Route::get('/email/verify', function () {
    return 'This page is appear when user try to make requests in routes has verified middleware and his email does not verified';
})->middleware('auth:sanctum')->name('verification.notice'); // must be named

// This route is the redirect form verfy from email verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return 'User verification successful';
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Route for resend email verification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return 'Verification email sent successfully';
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

// just for testing broadcasting
Route::get('/broadcast', function () {
    event(new MyEvent('Message from backend'));
    return 'Event fired!';
});
