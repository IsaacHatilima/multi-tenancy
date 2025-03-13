<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    require __DIR__.'/guest/index.php';
});

Route::get('verify-email/{id}', VerifyEmailController::class)
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');
