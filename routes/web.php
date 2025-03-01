<?php

use App\Http\Controllers\DomainController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::middleware([
        ])->group(function () {

            Route::middleware('auth')->group(function () {
                Route::get('/tenants', [TenantController::class, 'index'])->name('tenants');
                Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
                Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
                Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
                Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');
                Route::put('/domain/{domain}', [DomainController::class, 'update'])->name('domains.update');
            });

            require __DIR__.'/protected-common.php';

            require __DIR__.'/guest-common.php';
        });

    });
}
