<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    Route::get('/google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.redirect');

    Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');

    Route::get('/', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/', [LoginController::class, 'authenticate'])
        ->name('login.post');

    Route::get('/two-factor-challenge', function () {
        return Inertia::render('Auth/TwoFactorChallenge');
    })->name('two-factor.login');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::get('verify-email/{id}', VerifyEmailController::class)
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');
