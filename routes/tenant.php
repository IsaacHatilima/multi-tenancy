<?php

declare(strict_types=1);

use App\Http\Controllers\Tenants\UsersController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\ScopeSessions;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    InitializeTenancyBySubdomain::class,
    'web',
    PreventAccessFromCentralDomains::class,
    ScopeSessions::class,
])->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    });

    //    require __DIR__.'/guest-common.php';

    require __DIR__.'/protected-common.php';
});
