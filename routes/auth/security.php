<?php

use App\Http\Controllers\Auth\CustomFortifyController;

Route::put('/enable-fortify', [CustomFortifyController::class, 'enable'])->name('enable.fortify');
Route::put('/disable-fortify', [CustomFortifyController::class, 'disable'])->name('disable.fortify');
Route::put('/confirm-fortify-2fa', [CustomFortifyController::class, 'confirm'])->name('confirm.fortify');
