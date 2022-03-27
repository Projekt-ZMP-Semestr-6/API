<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\UpdateEmailController;
use App\Http\Controllers\User\UpdateNameController;
use App\Http\Controllers\User\UpdatePasswordController;
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

Route::prefix('auth')
    ->group(function () {
        Route::post('register', RegisterController::class)->name('auth.register');
        Route::post('login', LoginController::class)->name('auth.login');
        Route::get('logout', LogoutController::class)->name('auth.logout')->middleware('auth:sanctum');

        Route::controller(EmailVerificationController::class)
            ->middleware('auth:sanctum')
            ->prefix('email')
            ->as('verification.')
            ->group(function () {
                Route::get('verify', 'notice')->name('notice');
                Route::get('verify/{id}/{hash}', 'verify')->name('verify')->middleware('signed');
                Route::post('verification-notification', 'resendMail')->name('send')->middleware('throttle:6,1');
            });
    });

Route::controller(ResetPasswordController::class)
    ->middleware('guest')
    ->prefix('forgot-password')
    ->as('password.')
    ->group(function () {
        Route::post('send', 'sendNotification')->name('email');
        Route::post('reset', 'resetPassword')->name('update');
    });

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('user')
    ->as('user.')
    ->group(function () {
        Route::get('/', UserController::class)->name('info');

        Route::put('password', UpdatePasswordController::class)->name('update.password');
        Route::put('name', UpdateNameController::class)->name('update.name');
        Route::put('email', UpdateEmailController::class)->name('update.email');

        Route::delete('delete', DeleteAccountController::class)->name('delete');
});
