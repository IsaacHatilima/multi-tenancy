<?php

namespace App\Http\Controllers\Tenants;

use App\Actions\Auth\RegisterAction;
use App\Actions\TenantAction;
use App\Actions\TenantUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTenantUsersRequest;
use App\Http\Requests\UpdateTenantUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    use AuthorizesRequests;

    private TenantAction $tenantAction;

    private RegisterAction $registerAction;

    private TenantUserAction $tenantUserAction;

    public function __construct(TenantAction $tenantAction, RegisterAction $registerAction, TenantUserAction $tenantUserAction)
    {
        $this->tenantAction = $tenantAction;
        $this->registerAction = $registerAction;
        $this->tenantUserAction = $tenantUserAction;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, tenant()]);

        return Inertia::render('Tenant/TenantPages/Users', [
            'users' => $this->tenantUserAction->get_tenant_users(tenant(), $request),
            'filters' => $request->all(),
        ]);
    }

    public function store(CreateTenantUsersRequest $request)
    {
        $this->authorize('create', [User::class, tenant()]);

        $this->tenantUserAction->create_user($request);

        return redirect()->back();
    }

    public function update(UpdateTenantUserRequest $request, User $user)
    {
        $this->authorize('update', [User::class, tenant()]);
        $this->tenantUserAction->update_profile($request, $user);

        return redirect()->back();
    }
}
