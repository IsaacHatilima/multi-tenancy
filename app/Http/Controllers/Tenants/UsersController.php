<?php

namespace App\Http\Controllers\Tenants;

use App\Actions\Auth\RegisterAction;
use App\Actions\TenantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTenantUsersRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    use AuthorizesRequests;

    private TenantAction $tenantAction;

    private RegisterAction $registerAction;

    public function __construct(TenantAction $tenantAction, RegisterAction $registerAction)
    {
        $this->tenantAction = $tenantAction;
        $this->registerAction = $registerAction;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, tenant()]);

        return Inertia::render('Tenant/TenantPages/Users', [
            'users' => $this->tenantAction->get_tenant_users(tenant(), $request),
            'filters' => $request->all(),
        ]);
    }

    public function store(CreateTenantUsersRequest $request)
    {
        $this->registerAction->create_user($request);

        return redirect()->back();
    }
}
