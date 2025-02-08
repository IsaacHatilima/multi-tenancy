<?php

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

class CheckTenant
{
    public function handle($request, Closure $next)
    {
        if (in_array($request->getHost(), config('tenancy.central_domains'))) {
            return $next($request);
        } else {
            return app(InitializeTenancyBySubdomain::class)->handle($request, $next);
        }
    }
}
