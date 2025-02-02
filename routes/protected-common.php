<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SecurityController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/security', [SecurityController::class, 'edit'])->name('security.edit');
    Route::put('/security', [SecurityController::class, 'copy_recovery_codes'])->name('security.put');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::put('password', [SecurityController::class, 'update'])->name('password.update');

    // Using /logout conflicts with fortify route preventing tenant from login out
    Route::post('/sign-out', [LogoutController::class, 'destroy'])->name('sign.out');
});
