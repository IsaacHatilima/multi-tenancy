<?php

namespace App\Http\Controllers\Tenants;

use App\Actions\Auth\RegisterAction;
use App\Actions\TenantAction;
use App\Actions\TenantUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CurrentPasswordRequest;
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
            'filters' => $request->only(['first_name', 'last_name', 'email', 'role', 'verified', 'active']),
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

    public function destroy(CurrentPasswordRequest $request, User $user)
    {
        $this->authorize('delete', [User::class, tenant()]);
        $this->tenantUserAction->delete_user($user);

        return redirect()->back();
    }

    public function toggle_status(CurrentPasswordRequest $request, User $user)
    {
        $this->authorize('delete', [User::class, tenant()]);
        $this->tenantUserAction->toggle_user_status($user);

        return redirect()->back();
    }
}
