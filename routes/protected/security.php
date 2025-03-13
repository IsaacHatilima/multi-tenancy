<?php

use App\Http\Controllers\Auth\CustomFortifyController;
use App\Http\Controllers\Auth\SecurityController;

Route::put('/enable-fortify', [CustomFortifyController::class, 'enable'])->name('enable.fortify');
Route::put('/disable-fortify', [CustomFortifyController::class, 'disable'])->name('disable.fortify');
Route::put('/confirm-fortify-2fa', [CustomFortifyController::class, 'confirm'])->name('confirm.fortify');

Route::get('/security', [SecurityController::class, 'edit'])->name('security.edit');
Route::put('/security', [SecurityController::class, 'copy_recovery_codes'])->name('security.put');
Route::put('/password', [SecurityController::class, 'update'])->name('password.update');
Route::put('/manage-email-two-factor/{type}', [SecurityController::class, 'email_two_factor'])->name('email.fa');
