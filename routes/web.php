<?php

use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::middleware([
        ])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Dashboard');
            })->middleware(['auth'])->name('dashboard');

            Route::get('/tenants', [TenantController::class, 'index'])->name('tenants');

            require __DIR__.'/protected-common.php';
        });

    });
}

require __DIR__.'/guest-common.php';
