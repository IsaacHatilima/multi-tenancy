<?php

use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
    ->name('register.store');
