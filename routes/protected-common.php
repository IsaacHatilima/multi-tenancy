<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LogoutController;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    require __DIR__.'/protected/security.php';

    require __DIR__.'/protected/profile.php';

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('/sign-out', [LogoutController::class, 'destroy'])->name('sign.out');
});
