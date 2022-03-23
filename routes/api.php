<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\UpdateEmailController;
use App\Http\Controllers\User\UpdateNameController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\DeleteAccountController;
use App\Http\Controllers\User\UserController;
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

Route::prefix('/user')->middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', UserController::class)->name('user.info');

    Route::post('/password', ChangePasswordController::class)->name('user.change.password');
    Route::put('/name', UpdateNameController::class)->name('user.update.name');
    Route::put('/email', UpdateEmailController::class)->name('user.update.email');

    Route::delete('/delete', DeleteAccountController::class)->name('user.delete');
});
