<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Game\BestsellersController;
use App\Http\Controllers\Game\FreebiesController;
use App\Http\Controllers\Game\GameDetailsController;
use App\Http\Controllers\Game\ObserveGameController;
use App\Http\Controllers\Game\SearchGameController;
use App\Http\Controllers\SimulateChangesController;
use App\Http\Controllers\User\DeleteAccountController;
use App\Http\Controllers\User\UpdateEmailController;
use App\Http\Controllers\User\UpdateNameController;
use App\Http\Controllers\User\UpdatePasswordController;
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

Route::middleware(['auth:sanctum', 'verified'])
    ->as('game.')
    ->group(function () {
        Route::get('search/{gameName}', SearchGameController::class)->name('search');
        Route::get('game/{appId}', GameDetailsController::class)->name('details');
        Route::get('freebies', FreebiesController::class)->name('freebies');
        Route::get('bestsellers', BestsellersController::class)->name('bestsellers');
        Route::get('attach/{game:appid}', [ObserveGameController::class, 'attach'])->name('observe.attach');
        Route::get('detach/{game:appid}', [ObserveGameController::class, 'detach'])->name('observe.detach');
    });

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/fire/{price}', SimulateChangesController::class);
