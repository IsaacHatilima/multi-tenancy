<?php

namespace App\Http\Controllers\Tenants;

use App\Actions\TenantAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    use AuthorizesRequests;

    private TenantAction $tenantAction;

    public function __construct(TenantAction $tenantAction)
    {
        $this->tenantAction = $tenantAction;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, tenant()]);

        return Inertia::render('Tenant/TenantPages/Users', [
            'users' => $this->tenantAction->get_tenant_users(tenant(), $request),
            'filters' => $request->all(),
        ]);
    }
}
