<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SecurityController;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/security', [SecurityController::class, 'edit'])->name('security.edit');
    Route::put('/security', [SecurityController::class, 'copy_recovery_codes'])->name('security.put');
    Route::put('/password', [SecurityController::class, 'update'])->name('password.update');
    Route::put('/manage-email-two-factor/{type}', [SecurityController::class, 'email_two_factor'])->name('email.fa');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Using /logout conflicts with fortify route preventing tenant from login out
    Route::post('/sign-out', [LogoutController::class, 'destroy'])->name('sign.out');
});
