<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', fn (Request $request) => $request->user())->middleware(['auth:sanctum', 'verified'])->name('user.info');

Route::prefix('/auth')->group(function () {
    Route::post('/register', RegisterController::class)->name('auth.register');
    Route::post('/login', LoginController::class)->name('auth.login');
    Route::get('/logout', LogoutController::class)->middleware('auth:sanctum')->name('auth.logout');

    Route::prefix('/email')->controller(EmailVerificationController::class)->group(function () {
        Route::get('/verify', 'notice')->middleware('auth:sanctum')->name('verification.notice');
        Route::get('/verify/{id}/{hash}', 'verify')->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
        Route::post('/verification-notification', 'resendMail')->middleware(['auth:sanctum', 'throttle:3,1'])->name('verification.send');
    });

});

Route::prefix('/forgot-password')->controller(ResetPasswordController::class)->middleware('guest')->group(function () {
    Route::post('/send', 'sendNotification')->name('password.email');
    Route::post('/reset', 'resetPassword')->name('password.update');
});
